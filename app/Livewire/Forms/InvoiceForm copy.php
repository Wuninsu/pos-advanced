<?php

namespace App\Livewire\Forms;

use App\Livewire\Admin\Products;
use App\Models\CartItemsModel;
use App\Models\CustomersModel;
use App\Models\Invoices;
use App\Models\OrderDetailsModel;
use App\Models\OrdersModel;
use App\Models\ProductsModel;
use App\Models\TransactionsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Rule;

use function Illuminate\Log\log;

class InvoiceForm extends Component
{
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;
    public $discount = 0;

    public $customerName = '';
    public $newCustomer = false;
    public $showPaymentModal = false;

    public $phone, $email, $name, $address, $customer_id;

    #[Validate('required')]
    #[Validate('regex:/^\d{10,13}$/')]
    #[Validate('exists:customers,phone', message: 'The provided phone number does not match any existing customer. Please check the number or click "Add New" to create a new customer.')]
    public $customerPhone;

    #[Rule('required|numeric')]
    public $amount_paid;

    public $paymentStatus = 'unpaid';

    public $search = '';
    public $products = [];
    public $product, $quantity, $customers;

    #[Rule('array')] // Ensure it's an array
    #[Rule('required|string|min:3|max:500', as: 'Product Description')]
    public $description = [];

    public function mount()
    {
        $this->customers = CustomersModel::all();
    }


    public function addToCart($productId)
    {
        // $this->validate([
        //     'product' => 'required|exists:products,id',
        //     'quantity' => 'integer|min:1',
        // ]);

        // $productId = $this->product;
        $product = ProductsModel::find($productId);

        if (!$product) {
            toastr()->error('Product not found.');
            return;
        }

        if ($product->stock > 0) {

            $this->description[$product->id] = $product->description ?? '';
            // Check if the item already exists in the cart
            $existingCartItem = CartItemsModel::where('product_id', $product->id)
                ->where('user_id', auth('web')->id()) // Assuming you have user authentication
                ->first();

            if ($existingCartItem) {
                // Update the quantity if the product already exists in the cart
                $existingCartItem->increment('quantity');
            } else {
                // Add a new item to the cart
                CartItemsModel::create([
                    'user_id' => auth('web')->id(),
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }

            toastr()->success('Product added to cart.');
            $this->dispatch('playAddToCartSound');
        } else {
            toastr()->error('Product is out of stock.');
        }

        $this->search = '';
        $this->products = [];
        // Update totals after adding to cart
        $this->calculateTotals();
    }



    public function increaseQty($cartItemId)
    {
        $cartItem = CartItemsModel::find($cartItemId);

        if ($cartItem) {
            $product = $cartItem->product; // Assuming there's a relationship between CartItemsModel and the Product model

            if ($product->stock > $cartItem->quantity) {
                // Increment the cart item's quantity
                $cartItem->increment('quantity');
                $this->calculateTotals();
            } else {
                // Feedback for insufficient stock
                toastr()->warning("Insufficient stock for product: {$product->name}");
            }
        } else {
            // Feedback if cart item not found
            toastr()->error('Cart item not found.');
        }
    }


    public function decreaseQty($cartItemId)
    {
        $cartItem = CartItemsModel::find($cartItemId);

        if ($cartItem && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
            $this->calculateTotals();
        }
    }


    public function deleteItem($cartItemId)
    {
        CartItemsModel::find($cartItemId)->delete();
        $this->calculateTotals();
    }


    public function calculateTotals()
    {
        $cartItems = CartItemsModel::where('user_id', auth('web')->id())->get();

        $this->subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $this->total = $this->subtotal;
    }

    public function saveCustomer()
    {
        $this->validate([
            'email' => 'nullable|email|max:255|unique:customers,email,' . $this->customer_id,
            'name' => 'required|min:4|max:255',
            'phone' => 'required|regex:/^\d{10,13}$/|unique:customers,phone,' . $this->customer_id,
            'address' => 'nullable|string|max:1000',
        ]);
        $customer = CustomersModel::create(
            [
                'name' => $this->name,
                'address' => $this->address,
                'email' => $this->email,
                'phone' => $this->phone,
                'created_by' => Auth::user()->id,
            ]
        );

        if ($customer) {
            $this->customerPhone = $customer->phone;
            $this->customerName = $customer->name;
            $this->customer_id = $customer->id;
            // $this->newCustomer = false;
            $this->dispatch('close-customer-model');
            toastr()->success('Customer created successfully!');
        }
    }


    public function updated($propertyName)
    {
        if ($propertyName === 'customerPhone') {
            $customer = CustomersModel::where('phone', $this->customerPhone)->first();
            if ($customer) {
                $this->customerPhone = $customer->phone;
                $this->customerName = $customer->name;
                $this->customer_id = $customer->id;
                $this->newCustomer = false;
            } else {
                $this->phone = $this->customerPhone;
                $this->newCustomer = true;
            }
        }

        if ($propertyName === 'product') {

            $this->quantity = 1;
        }

        if ($propertyName === 'tax') {
            $this->validate([
                'total' => 'required|integer',
            ]);
            // $this->total = $this->total + $this->tax;
        }
    }



    public function validateCartAndShowModal()
    {
        $cartItems = CartItemsModel::where('user_id', auth('web')->id())->get();

        if ($cartItems->isEmpty()) {
            toastr()->error('Cart is empty. Please add items to proceed.');
            return;
        }

        // Dispatch event to show modal
        $this->dispatch('showPaymentModal');
        $this->showPaymentModal = true;
    }


    public function submitOrder()
    {
        $cartItems = CartItemsModel::where('user_id', auth('web')->id())->get();

        if ($cartItems->isEmpty()) {
            toastr()->error('Cart is empty. Please add items to proceed.');
            return;
        }

        // Validate customer, payment, and product descriptions
        $validated = $this->validate([
            'customerPhone' => 'required|exists:customers,phone',
            'paymentStatus' => 'required|in:paid,unpaid,canceled',
            'description.*' => 'required|string|min:3|max:500',
        ], [
            'description.*.required' => 'Product description is required.',
            'description.*.min' => 'Product description must be at least 3 characters.',
            'description.*.max' => 'Product description cannot exceed 500 characters.',
            'customerPhone' => 'The provided phone number does not match any existing customer. Please check the number or click "Add New" to create a new customer.',
        ]);



        DB::transaction(function () use ($validated) {
            $orderNumber = generateOrderNumber();
            if ($this->paymentStatus === 'paid') {
                $status = 'completed';
            } else {
                $status = 'pending';
            }
            // Step 1: Save order
            $order = OrdersModel::create([
                'user_id' => auth('web')->id(),
                'customer_id' => $this->customer_id,
                'order_amount' => $this->total,
                'status' => $status,
                'order_number' =>  $orderNumber,
            ]);

            $invoiceNumber = generateInvoiceNumber();
            Invoices::create([
                'invoice_number' => $invoiceNumber,
                'order_id' => $order->id,
                'status' => $this->paymentStatus,
                'total_amount' => $this->total,
                'tax_amount' => $this->tax,
                'discount' => 0.00,
                'invoice_date' => now(),
            ]);
            $cartItems = CartItemsModel::where('user_id', auth('web')->id())->get();
            // Step 3: Save order details (WITH description)
            foreach ($cartItems as $cartItem) {
                OrderDetailsModel::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'unit_price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'total_amount' => $cartItem->product->price * $cartItem->quantity,
                    'discount' => $cartItem->product->discount ?? 0,
                    'description' => $this->description[$cartItem->product_id] ?? '', // Save description
                ]);

                // Reduce stock
                $product = $cartItem->product;
                if ($product->stock < $cartItem->quantity) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }
                $product->decrement('stock', $cartItem->quantity);
            }

            // Step 4: Clear cart after order completion
            CartItemsModel::where('user_id', auth('web')->id())->delete();

            // Reset order data if needed
            $this->resetTransactionData();
            toastr()->success('Invoice generated successfully');
            redirect(route('invoices.details', ['invoice' => $invoiceNumber]));
        });
    }



    public function resetTransactionData()
    {
        $this->customerName = '';
        $this->customerPhone = '';
        $this->paymentStatus = 'unpaid';
        $this->amount_paid = null;
        $this->subtotal = 0;
        $this->tax = 0;
        $this->total = 0;
        $this->customer_id = null;
        $this->phone = null;
        $this->email = null;
        $this->address = null;
        $this->name = null;
    }


    public function cancelOrder()
    {
        // Delete all cart items for the current user
        CartItemsModel::where('user_id', auth('web')->user()->id)->delete();

        // Optionally, you can reset any related Livewire properties (e.g., subtotal, tax, total)
        $this->subtotal = 0;
        $this->tax = 0;
        $this->total = 0;

        // Provide feedback to the user
        toastr()->success('Invoice has been cancelled and cart has been cleared.');

        // You may also want to refresh the cart items or other UI elements
        $this->dispatch('cartUpdated'); // Example: emit an event to refresh the UI if needed
    }

    public $selectedProductId = null;

    public function updatedSearch()
    {
        $this->products = ProductsModel::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%') // optional
            ->limit(10)
            ->get();
    }

    public function setSelectedProduct($id)
    {
        $product = ProductsModel::find($id);

        if (!$product) {
            // Handle case when product is not found
            toastr()->error('Product not found.');
            return;
        }

        $this->selectedProductId = $product->id;
        $this->search = $product->name; // Optional: show selected name in input
        $this->products = []; // Clear dropdown
    }

    #[Title('Create Order')]
    public function render()
    {
        $cartItems = CartItemsModel::with('product')->where('user_id', auth('web')->user()->id)->latest()->get();
        return view('livewire.forms.invoice-form', [
            'cartItems' => $cartItems,
        ]);
    }
}
