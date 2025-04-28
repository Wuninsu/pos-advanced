<?php

namespace App\Livewire\Forms;

use App\Livewire\Admin\Products;
use App\Models\CartItemsModel;
use App\Models\CustomersModel;
use App\Models\OrderDetailsModel;
use App\Models\OrdersModel;
use App\Models\ProductsModel;
use App\Models\TransactionsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Rule;

use function Illuminate\Log\log;

class OrderForm extends Component
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
    public $amount_paid;

    public $due_date;

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
            $this->customers = CustomersModel::all();
            $this->customer = $customer->id;
            $this->searchCustomer = $customer->name;
            $this->customers = [];
            $this->dispatch('close-customer-modal');
            toastr()->success('Customer added successfully!');
        }
    }


    public function updated($propertyName)
    {
        if ($propertyName === 'customerPhone') {
            $customer = CustomersModel::where('phone', $this->customerPhone)->first();

            if ($customer) {
                $this->customerPhone = $customer->phone;
                $this->customerName = $customer->name;
                $this->customer = $customer->id;
                $this->newCustomer = false;
            } else {
                $this->phone = $this->customerPhone;
                $this->newCustomer = true;
            }
        }

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
        $payable = (float) $this->subtotal - (float)  $discountAmount;
        $this->amount_payable = $payable;
        $this->discount = $discountAmount;
        $this->total = $payable;
    }


    public function validateAndShowModal()
    {
        $cartItems = CartItemsModel::where('user_id', auth('web')->id())->get();

        if ($cartItems->isEmpty()) {
            toastr()->error('Cart is empty. Please add items to proceed.');
            return;
        }

        // Validate required payment fields
        $this->validate([
            'customer' => 'required|exists:customers,id',
            'paymentMethod' => 'required|string|in:cash,bank,cheque,credit,mobile_money',
            'subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'amount_payable' => 'required|numeric|min:0',
        ]);

        // Optional: Check amount paid is not more than total
        if ($this->amount_paid > $this->total) {
            toastr()->error('Amount paid cannot exceed the total payable amount.');
            return;
        }

        // Dispatch modal
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
        $rules = [
            'paymentMethod' => 'required|string',
            // 'amount_paid' => 'required|numeric|min:0',
        ];

        if ($this->paymentMethod === 'credit') {
            $rules['amount_paid'] = ['nullable', 'numeric', 'min:0'];
            $rules['due_date'] = ['required', 'date', 'after_or_equal:today'];
        } else {
            $this->amount_paid = $this->amount_payable;
        }


        $validated = $this->validate($rules);

        DB::beginTransaction();

        try {
            $orderNumber = generateOrderNumber();

            $amountPaid = $this->amount_paid;
            $balance = 0;

            if ($this->amount_paid > $this->amount_payable) {
                // Customer overpaid
                $balance = (float) $this->amount_paid - (float) $this->amount_payable;
                $amountPaid = $this->amount_payable; // Save only what's due
            } elseif ($this->amount_paid < $this->amount_payable) {
                // Customer underpaid
                $balance = $this->amount_payable - $this->amount_paid; // This is what they still owe
            }

            // Step 1: Save Order
            $order = OrdersModel::create([
                'user_id' => auth('web')->id(),
                'customer_id' => $this->customer,
                'order_number' => $orderNumber,
                'order_amount' => $this->subtotal,
                'discount' => $this->discount,
                'amount_payable' => $this->amount_payable,
                'amount_paid' => $amountPaid,
                'payment_method' => $this->paymentMethod,
                'status' => 'pending',
                'due_date' => $this->paymentMethod === 'credit' ? $this->due_date : null,
                // 'balance' => $balance,
            ]);

            $orderBalance = (float) $order->amount_payable - (float) $order->amount_paid;
            $order->update([
                'balance' => $orderBalance,
            ]);

            if ($orderBalance == 0) {
                $order->update([
                    'status' => 'completed',
                ]);
            }

            // Step 2: Save payment details
            TransactionsModel::create([
                'user_id' => auth('web')->id(),
                'transaction_number' => generateTransactionNumber(), // Generate the transaction number
                'order_id' => $order->id, // Link the transaction to the order
                'payment_method' => $this->paymentMethod, // Payment method selected
                'balance' =>  $balance ?? 0.00, // The remaining balance
                'transaction_amount' => $this->amount_paid, // The total paid amount in the transaction
                'transaction_date' => now(), // Transaction timestamp
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

            // Reset order data if needed


            $change = (float) $this->amount_paid - (float) $this->amount_payable;

            $this->showBalance = true;

            if ($change < 0) {
                $this->changeMessage = "Customer owes:";
                $this->changeAmount = ($this->settings['currency'] ?? 'GHS') . number_format(abs($change), 2);
            } elseif ($change > 0) {
                $this->changeMessage = "Change due to customer:";
                $this->changeAmount = ($this->settings['currency'] ?? 'GHS') . number_format($change, 2);
            } else {
                $this->changeMessage = "Fully paid:";
                $this->changeAmount = ($this->settings['currency'] ?? 'GHS') . number_format($change, 2);
            }

            $this->dispatch('printReceipt', order_id: $order->id);
            $this->dispatch('close-modal');
            $this->dispatch('orderCompleted');
            toastr()->success('Order successfully completed!');

            $this->resetTransactionData();
            $this->getLastOrder();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr('error', 'Error placing order: ' . $e->getMessage());
        }
    }

    public function getLastOrder()
    {
        $lastOrder = OrdersModel::with('orderDetails.product')->latest()->first();

        if ($lastOrder) {
            $this->order = $lastOrder;
            $this->orderDetails = $lastOrder->orderDetails;
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
        toastr()->success('Order has been cancelled and cart has been cleared.');

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

    #[Title('Create Sales Order')]
    public function render()
    {
        $cartItems = CartItemsModel::with('product')->where('user_id', 1)->latest()->get();
        $this->quantities = $cartItems->pluck('quantity', 'id')->toArray();
        return view('livewire.forms.order-form', [
            'cartItems' => $cartItems,
        ]);
    }
}
