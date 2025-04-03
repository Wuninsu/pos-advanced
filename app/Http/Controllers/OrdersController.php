<?php

namespace App\Http\Controllers;

use App\Models\OrderDetailsModel;
use App\Models\OrdersModel;
use App\Models\TransactionsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('orders.index', [
            'page_title' => "Orders"
        ]);
    }



    public function generateReceipt($orderId)
    {
        // Fetch the order and related details
        $order = OrdersModel::with('orderDetails.product')->find($orderId);

        // Check if the order exists
        if (!$order) {
            abort(404, 'Order not found');
        }

        // Pass the data to a Blade view for the receipt
        return view('receipt', ['order' => $order]);
    }

    public function store(Request $request)
    {

        // Validate the request
        $validated = $request->validate([
            'product_id' => 'required|array',
            'price' => 'required|array',
            'quantity' => 'required|array',
            'discount' => 'required|array',
            'total_amount' => 'required|array',
            'customer_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'payment_method' => 'required|string',
            'amount_paid' => 'required|numeric',
            'balance' => 'required|numeric',
            'transact_amount' => 'required|numeric',
        ]);

        DB::transaction(function () use ($validated) {
            // Step 1: Save customer information in the orders table
            $order = OrdersModel::create([
                'user_id' => 1,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['phone'],
            ]);

            // Step 2: Save payment information in the transactions table
            TransactionsModel::create([
                'user_id' => 1,
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'amount_paid' => $validated['amount_paid'],
                'balance' => $validated['balance'],
                'transact_amount' => $validated['transact_amount'],
                'transact_date' => date('Y-m-d H:i'),
            ]);

            // Step 3: Save product details in the order_details table
            foreach ($validated['product_id'] as $index => $productId) {
                OrderDetailsModel::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'unit_price' => $validated['price'][$index],
                    'quantity' => $validated['quantity'][$index],
                    'discount' => $validated['discount'][$index] ?? 0,
                    'total_amount' => $validated['total_amount'][$index],
                ]);
            }
        });

        return response()->json(['message' => 'Order successfully created.']);
    }
}
