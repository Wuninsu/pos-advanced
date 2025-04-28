<?php

namespace App\Livewire;

use App\Models\OrdersModel;
use App\Models\TransactionsModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class OrderDetails extends Component
{
    public $order;
    public $showPaymentModal = false;

    public $paymentMethod;
    public $amount_paid;
    public $due_date;
    public $owing;

    public string $changeMessage = '';
    public string $changeAmount = '';
    public  $showBalance = false;

    public function mount($order)
    {
        $this->order = OrdersModel::with(['orderDetails.product', 'transactions'])
            ->where('order_number', $order)
            ->firstOrFail();
        $this->status = $this->order->status;
        $this->owing = $this->order->balance;
    }

    public $status;

    public function updatedStatus()
    {
        $order = OrdersModel::findOrFail($this->order->id);
        $order->status = $this->status;
        $order->save();
        $this->order = $order;
        toastr('Order status has been changed', 'info');
    }

    public function makePayment()
    {
        $rules = [
            'paymentMethod' => ['required', 'string', 'not_in:credit'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
        ];

        if ($this->paymentMethod === 'credit') {
            $rules['amount_paid'] = ['nullable', 'numeric', 'min:0'];
            $rules['due_date'] = ['required', 'date', 'after_or_equal:today'];
        }

        $messages = [
            'paymentMethod.not_in' => 'Credit payment is not allowed at this point.',
        ];

        $validated = $this->validate($rules, $messages);


        DB::beginTransaction();
        try {
            $order = OrdersModel::findOrFail($this->order->id);

            $amountPaidNow = $this->amount_paid ?? 0;
            $previouslyPaid = $order->amount_paid;
            $totalPaid = $amountPaidNow + $previouslyPaid;

            $remainingBalance = max(0, $order->amount_payable - $totalPaid);
            $change = max(0, $totalPaid - $order->amount_payable);

            // Optionally adjust only the actual amount that counts toward the order
            $actualPayment = $amountPaidNow - $change;

            $order->amount_paid += $actualPayment;
            $order->balance = $remainingBalance;
            $order->status = $remainingBalance <= 0 ? 'completed' : 'pending';
            $order->save();


            // Log transaction
            TransactionsModel::create([
                'user_id' => auth('web')->id(),
                'transaction_number' => generateTransactionNumber(), // Generate the transaction number
                'order_id' => $order->id, // Link the transaction to the order
                'payment_method' => $this->paymentMethod, // Payment method selected
                'balance' =>  $remainingBalance ?? 0.00, // The remaining balance
                'transaction_amount' =>  $actualPayment, // The total paid amount in the transaction
                'transaction_date' => now(), // Transaction timestamp
            ]);

            $this->order = $order; // Refresh order
            $this->showPaymentModal = false;
            toastr()->success('Payment recorded successfully.');
            $this->dispatch('close-modal');
            $this->showBalance = true;
            if ($remainingBalance > 0) {
                $this->changeMessage = "Customer owes:";
                $this->changeAmount = ($this->settings['currency'] ?? 'GHS') . number_format(abs($remainingBalance), 2);
            } elseif ($remainingBalance < 0) {
                $this->changeMessage = "Change due to customer:";
                $this->changeAmount = ($this->settings['currency'] ?? 'GHS') . number_format($remainingBalance, 2);
            } else {
                $this->changeMessage = "Fully paid:";
                $this->changeAmount = ($this->settings['currency'] ?? 'GHS') . number_format($remainingBalance, 2);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error('Payment failed: ' . $e->getMessage());
        }
    }

    public function validateAndShowModal()
    {
        // properly check here if the mounted order is not empty
        if (!$this->order) {
            toastr()->error('No order was found.');
            return;
        }
        // Dispatch modal
        $this->dispatch('showPaymentModal');
        $this->showPaymentModal = true;
    }

    #[Title('Order Detail')]
    public function render()
    {
        return view('livewire.order-details');
    }
}
