<?php

namespace App\Livewire\Admin;

use App\Models\CartItemsModel;
use App\Models\CategoriesModel;
use App\Models\CustomersModel;
use App\Models\OrderDetailsModel;
use App\Models\OrdersModel;
use App\Models\ProductsModel;
use App\Models\TransactionsModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

use function Illuminate\Log\log;

class Pos extends Component
{
    public $products = [];
    public $search = '';

    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;

    public $categories = [];
    public $selectedCategory = null; // For category filtering

    public $customerName;
    public $newCustomer = false;
    public $showPaymentModal = false;


    public $phone, $email, $address, $customer_id;
    public $name = 'Walk-in Customer';

    #[Validate('required')]
    #[Validate('regex:/^\d{10,13}$/')]
    #[Validate('exists:customers,phone', message: 'The provided phone number does not match any existing customer. Please check the number or click "Add New" to create a new customer.')]
    public $customerPhone;

    #[Rule('required|numeric')]
    public $amount_paid;

    public $paymentMethod = 'cash';

    public $orders = [];

    public $customer;
    public bool $cartSoundEnabled;


    public $order;
    public $orderDetails = [];


    public function mount()
    {
        $this->cartSoundEnabled = DB::table('preferences')->where('key', 'enable_cart_sound')->value('value') ?? false;
        $this->categories = CategoriesModel::latest()->get(); // Replace with your category model
        $this->search = ''; // Initialize search as an empty string
        $this->products = collect(); // Initialize an empty collection for products
        $this->calculateTotals(); // Initialize totals
        $this->getLastOrder();
    }

    public function toggleCartSound()
    {
        $this->cartSoundEnabled = !$this->cartSoundEnabled;

        DB::table('preferences')->updateOrInsert(
            ['key' => 'enable_cart_sound'],
            ['value' => $this->cartSoundEnabled]
        );
    }
    public function getLastOrder()
    {
        $lastOrder = OrdersModel::with('orderDetails.product')->latest()->first();

        if ($lastOrder) {
            $this->order = $lastOrder;
            $this->orderDetails = $lastOrder->orderDetails;
        }
    }

    public function updatedSelectedCategory()
    {
        // Refresh the product list when the selected category changes
        $this->searchProducts();
    }

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId; // Update the selected category
        $this->searchProducts(); // Refresh product list
    }

    public function searchProducts()
    {
        $this->products = ProductsModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->where('status', '!=', '0')
            ->latest()
            ->get();
    }


    public function addToCart($productId)
    {
        $product = ProductsModel::find($productId);

        if (!$product) {
            toastr()->error('Product not found.');
            return;
        }

        if ($product->stock > 0) {
            // Check if the item already exists in the cart
            $existingCartItem = CartItemsModel::where('product_id', $product->id)
                ->where('user_id', auth('web')->id())
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

            // Check if preference is enabled
            $enableSound = DB::table('preferences')->where('key', 'enable_cart_sound')->value('value');
            if ($enableSound) {
                $this->dispatch('playAddToCartSound');
            }
        } else {
            toastr()->error('Product is out of stock.');
        }

        // Update totals after adding to cart
        $this->calculateTotals();
    }

    // public function increaseQty($cartItemId)
    // {
    //     $cartItem = CartItemsModel::find($cartItemId);

    //     if ($cartItem) {
    //         $cartItem->increment('quantity');
    //         $this->calculateTotals();
    //     }
    // }


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

        // $this->tax = $this->subtotal * 0.015;
        // $this->total = $this->subtotal + $this->tax;

        // $this->tax = $this->subtotal * 0.015;
        $this->total = $this->subtotal;
    }

    public function addToCartBySearch()
    {
        $product = ProductsModel::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('id', '=', $this->search)->first();

        if (!$product) {
            toastr()->error('No matching product found.');
            return;
        }

        $this->addToCart($product->id);
        $this->dispatch('playAddToCartSound');
        // Clear search input after adding to cart
        $this->search = '';
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

    #[On('transaction-confirmed')]
    public function submitOrder()
    {
        // Validate the customer and payment information
        $validated = $this->validate([
            'customerPhone' => 'required|exists:customers,phone',
            'paymentMethod' => 'required|string',
        ], [
            'customerPhone' => 'The provided phone number does not match any existing customer. Please check the number or click "Add New" to create a new customer.',
        ]);
        // If the payment method is 'cash', validate the amount paid
        if ($this->paymentMethod === 'cash') {
            $this->validate([
                'amount_paid' => [
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) {
                        if ($value < $this->total) {
                            $fail('The amount paid must not be less than the total order amount.');
                        }
                    },
                ],
            ]);
        }

        DB::transaction(function () use ($validated) {
            // Step 1: Save customer information in the orders table
            $order = OrdersModel::create([
                'user_id' => auth('web')->id(),
                'customer_id' => $this->customer_id,
                'order_amount' => $this->total,
                'status' => 'completed',
                'order_number' => generateOrderNumber(),
            ]);

            // Step 2: Save payment information in the transactions table
            TransactionsModel::create([
                'user_id' => auth('web')->id(),
                'transaction_number' => generateTransactionNumber(),
                'order_id' => $order->id,
                'payment_method' => $this->paymentMethod,
                'amount_paid' => $this->amount_paid,
                'balance' => $this->amount_paid - $this->total, // Balance after payment
                'transact_amount' => $this->total, // Total amount of the order
                'transact_date' => now(), // Using Laravel's now() helper for the current timestamp
            ]);

            // Step 3: Save product details in the order_details table
            $cartItems = CartItemsModel::where('user_id', auth('web')->id())->get();
            foreach ($cartItems as $cartItem) {
                OrderDetailsModel::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'unit_price' => $cartItem->product->price, // Assuming you have a price field in the product model
                    'quantity' => $cartItem->quantity,
                    'total_amount' => $cartItem->product->price * $cartItem->quantity,
                    'discount' => $cartItem->product->discount ?? 0, // Optional discount field
                ]);

                // Subtract the order quantity from the product's stock
                $product = $cartItem->product;
                if ($product->stock < $cartItem->quantity) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $product->decrement('stock', $cartItem->quantity);
            }

            $customer = CustomersModel::find($this->customer_id);
            $customer->counter += 1;
            $customer->save();
            // Step 4: Clear the cart after a successful order
            CartItemsModel::where('user_id', auth('web')->user()->id)->delete();

            // Optional: Reset order data if needed
            $this->resetTransactionData();
            $this->dispatch('printReceipt', order_id: $order->id);
            $this->dispatch('close-modal');

            $this->getLastOrder(); // Fetch the last order
        });

        toastr()->success('Order successfully completed!');

        $this->dispatch('orderCompleted');
    }

    public function resetTransactionData()
    {
        $this->customerName = '';
        $this->customerPhone = '';
        $this->paymentMethod = 'cash'; // Reset payment method to default
        $this->amount_paid = null; // Reset amount paid
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
        toastr()->success('Order has been cancelled and cart has been cleared.');

        // You may also want to refresh the cart items or other UI elements
        $this->dispatch('cartUpdated'); // Example: emit an event to refresh the UI if needed
    }


    public function showOrdersModal()
    {
        $this->orders = OrdersModel::with('transactions', 'user')->where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())->get() ?? collect();

        $this->dispatch('orderHistory');
    }


    public function getOrderData($id)
    {

        $this->order = OrdersModel::find($id);
        $this->dispatch('printReceipt');
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
                $this->customer = $customer;
            } else {
                $this->phone = $this->customerPhone;
                $this->name = "Walk-in Customer";
                $this->newCustomer = true;
            }
        }


        if ($propertyName === 'paymentMethod') {
            if ($this->paymentMethod === 'online') {
                $validated = $this->validate([
                    'customerPhone' => 'required|exists:customers,phone',
                ], [
                    'customerPhone' => 'The provided phone number does not match any existing customer. Please check the number or click "Add New" to create a new customer.',
                ]);

                $this->amount_paid = $this->total;
                $data = [
                    'email' => $this->email ?? 'wuninsu.a@yahoo.com',
                    'amount' => $this->total,
                ];
                $this->dispatch('payOnline', data: $data);
            }
        }
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
            $this->customer = $customer;
            toastr()->success('Customer created successfully!');
            $this->dispatch('showCustomModal');
        }
    }

    // #[On('transaction-confirmed')]
    // public function transactionConfirmed()
    // {
    //     Log('transaction: confirmed');
    // }



    #[Title('POS')]
    public function render()
    {
        // dd($this->order);
        $this->searchProducts();
        // Fetch cart items for the view
        $cartItems = CartItemsModel::with('product')->where('user_id', auth('web')->user()->id)->latest()->get();
        return view('livewire.admin.pos', [
            'cartItems' => $cartItems,
            'products' => $this->products,
            'categories' => $this->categories, // Pass categories to the view
        ])->layout('layouts.guest');
    }
}
