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
use App\Models\InvoiceDetail;

class InvoiceForm extends Component
{
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;

    public $customerName = '';
    public $newCustomer = false;
    public $showPaymentModal = false;


    public $phone, $email, $name, $address, $customer;
    public $input_quantity;

    public $amount_payable = 0.00;
    public $discount = 0;
    public  $discount_type = 'flat';
    public $raw_discount = 0;
    public $quantities = [];

    public $customerPhone;

    #[Rule('required|numeric')]
    public $amount_paid, $due_date;

    public $paymentMethod = 'cash';

    public $products, $product, $quantity, $customers;

    public $order;
    public $orderDetails = [];

    public bool $showBalance = false;
    public string $changeMessage = '';
    public string $changeAmount = '';
    public $search = '';
    public $selectedProductId = null;
    public $searchCustomer;

    public $payment_status = 'unpaid';


    public function mount()
    {
        $this->customer = CustomersModel::where('name', 'Walk-In Customer')->first()->id ?? null;
        $this->searchCustomer = CustomersModel::where('name', 'Walk-In Customer')->first()->name ?? null;
        $this->calculateTotals();
    }


    public function addToCart()
    {
        $this->validate([
            'product' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $productId = $this->product;
        $product = ProductsModel::find($productId);

        if (!$product) {
            toastr()->error('Product not found.');
            return;
        }

        if ($product->stock > 0) {
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
                    'quantity' => $this->quantity,
                ]);
            }

            toastr()->success('Product added to cart.');
            $this->dispatch('playAddToCartSound');
        } else {
            toastr()->error('Product is out of stock.');
        }

        // Update totals after adding to cart
        $this->calculateTotals();
    }


    public function addItem()
    {
        $this->validate([
            'product' => 'required|exists:products,id',
            'input_quantity' => 'integer|min:1',
        ]);

        $productId = $this->product;
        $product = ProductsModel::find($productId);

        if (!$product) {
            toastr()->error('Product not found.');
            return;
        }

        if ($product->stock > 0) {
            // Check if the item already exists in the cart
            $existingCartItem = CartItemsModel::where('product_id', $product->id)
                ->where('user_id', auth('web')->id()) // Assuming you have user authentication
                ->first();

            if ($existingCartItem) {
                // Update the quantity if the product already exists in the cart
                $existingCartItem->update(['quantity' => $this->input_quantity]);
            } else {
                // Add a new item to the cart
                CartItemsModel::create([
                    'user_id' => auth('web')->id(),
                    'product_id' => $product->id,
                    'quantity' => $this->input_quantity,
                ]);
            }

            toastr()->success('Product added to cart.');
            $this->product = null;
            $this->search = null;
            $this->input_quantity = null;
        } else {
            toastr()->error('Product is out of stock.');
        }

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

    public function saveCustomer()
    {
        $this->validate([
            'email' => 'nullable|email|max:255|unique:customers,email,' . $this->customer,
            'name' => 'required|min:4|max:255',
            'phone' => 'required|regex:/^\d{10,13}$/|unique:customers,phone,' . $this->customer,
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
            $this->customer = $customer->id;
            $this->searchCustomer = $customer->name;
            $this->customers = [];
            $this->dispatch('close-customer-modal');
            toastr()->success('Customer added successfully!');
        }
    }


    public function updated($propertyName)
    {
        if ($propertyName === 'raw_discount' || $propertyName === 'discount_type') {
            $this->calculateTotals();
        }
    }


    public function calculateTotals()
    {
        $cartItems = CartItemsModel::where('user_id', auth('web')->id())->get();

        $this->subtotal = $cartItems->sum(function ($item) {
            return (float) $item->product->price * (int) $item->quantity;
        });

        $discount = (float) $this->raw_discount;

        if ($this->discount_type === 'percent') {
            $discount = min($discount, 100); // prevent >100%
            $discountAmount = ($discount / 100) * $this->subtotal;
        } else {
            $discountAmount = $discount;
        }

        // Prevent over-discount
        $discountAmount = min($discountAmount, $this->subtotal);
        $payable = $this->subtotal - $discountAmount;
        $this->amount_payable = $payable;
        $this->discount = $discountAmount;
        $this->total = $payable;
    }


    public function saveInvoice()
    {
        $cartItems = CartItemsModel::where('user_id', auth('web')->id())->get();

        if ($cartItems->isEmpty()) {
            toastr()->error('Cart is empty. Please add items to proceed.');
            return;
        }

        // Validate required payment fields
        $this->validate([
            'customer' => 'required|exists:customers,id',
            'payment_status' => 'required|string|in:unpaid,paid,cancelled',
            'subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'amount_payable' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $invoiceNumber = generateInvoiceNumber();

            // Step 1: Save Order
            $invoice = Invoices::create([
                'user_id' => auth('web')->id(),
                'customer_id' => $this->customer,
                'invoice_number' => $invoiceNumber, // make sure $invoiceNumber is generated
                'invoice_amount' => $this->subtotal,
                'discount' => $this->discount,
                'amount_payable' => $this->amount_payable,
                'status' => $this->payment_status,
                'invoice_date' => now(), // optional override, handled by default
            ]);

            $cartItems = CartItemsModel::where('user_id', auth('web')->id())->get();
            // Step 2: Save order details (WITH description)
            foreach ($cartItems as $cartItem) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $cartItem->product_id,
                    'unit_price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'total_amount' => $cartItem->product->price * $cartItem->quantity,
                    'description' => $cartItem->quantity . ' ' .
                        ($cartItem->product->unit->name ?? '') .
                        ' (' . ($cartItem->product->unit->abbreviation ?? '') . ')' .
                        ' × GHS' . number_format($cartItem->product->price, 2),
                ]);

                // Reduce stock
                $product = $cartItem->product;
                if ($product->stock < $cartItem->quantity) {
                    toastr()->error("Insufficient stock for product: {$product->name}");
                }
                $product->decrement('stock', $cartItem->quantity);
            }

            // Step 4: Clear cart after order completion
            CartItemsModel::where('user_id', auth('web')->id())->delete();

            // Reset invoice data if needed
            $this->resetTransactionData();
            toastr()->success('Invoice generated successfully');
            redirect(route('invoices.details', ['invoice' => $invoiceNumber]));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr('error', 'Error placing order: ' . $e->getMessage());
        }
    }

    public function resetTransactionData()
    {
        $this->subtotal = 0;
        $this->tax = 0;
        $this->total = 0;

        $this->customerName = '';
        $this->newCustomer = false;
        $this->showPaymentModal = false;

        $this->phone = null;
        $this->email = null;
        $this->name = null;
        $this->address = null;
        $this->customer = CustomersModel::where('name', 'Walk-In Customer')->first()->id ?? null;
        $this->searchCustomer = CustomersModel::where('name', 'Walk-In Customer')->first()->name ?? null;

        $this->input_quantity = null;

        $this->amount_payable = 0.00;
        $this->discount = 0;
        $this->discount_type = 'flat';
        $this->raw_discount = 0;
        $this->quantities = [];

        $this->customerPhone = null;

        $this->amount_paid = null;
        $this->due_date = null;

        $this->paymentMethod = 'cash';

        $this->product = null;
        $this->quantity = null;

        $this->products = [];
        $this->customers = [];

        $this->calculateTotals();
    }



    public function cancelOrder()
    {
        // Delete all cart items for the current user
        CartItemsModel::where('user_id', 1)->delete();

        // Optionally, you can reset any related Livewire properties (e.g., subtotal, tax, total)
        $this->resetTransactionData();

        // Provide feedback to the user
        toastr()->success('Invoice has been cancelled and cart has been cleared.');

        // You may also want to refresh the cart items or other UI elements
        $this->dispatch('cartUpdated'); // Example: emit an event to refresh the UI if needed
    }

    public function updateQuantity($itemId)
    {
        $qty = (int) $this->quantities[$itemId];

        if ($qty > 0) {
            $item = CartItemsModel::find($itemId);
            if ($item) {
                $product = $item->product;
                if ($product->stock >= $qty) {
                    // Increment the cart item's quantity
                    $item->quantity = $qty;
                    $item->save();
                } else {
                    // Feedback for insufficient stock
                    toastr()->warning("Insufficient stock for product: {$product->name}");
                }
            }
        }

        $this->calculateTotals(); // Refresh totals
    }

    public function closeBalanceModal()
    {
        $this->showBalance = false;

        // Dispatch a browser event
        $this->dispatch('balance-modal-closed', [
            'message' => 'Balance modal has been closed.',
        ]);
    }



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

        $this->product = $product->id;
        $this->search = $product->name; // Optional: show selected name in input
        $this->products = []; // Clear dropdown
    }



    public function updatedSearchCustomer()
    {
        $this->customers = CustomersModel::where('name', 'like', '%' . $this->searchCustomer . '%')
            ->orWhere('phone', 'like', '%' . $this->searchCustomer . '%') // optional
            ->limit(10)
            ->get();
    }

    public function setSelectedCustomer($id)
    {
        $customer = CustomersModel::find($id);

        if (!$customer) {
            // Handle case when customer is not found
            toastr()->error('Customer not found.');
            return;
        }

        $this->customer = $customer->id;
        $this->searchCustomer = $customer->name; // Optional: show selected name in input
        $this->customers = []; // Clear dropdown
    }

    #[Title('Create Sales Invoice')]
    public function render()
    {
        $cartItems = CartItemsModel::with('product')->where('user_id', 1)->latest()->get();
        $this->quantities = $cartItems->pluck('quantity', 'id')->toArray();
        return view('livewire.forms.invoice-form', [
            'cartItems' => $cartItems,
        ]);
    }
}
