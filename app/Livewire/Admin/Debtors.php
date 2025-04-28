<?php

namespace App\Livewire\Admin;

use App\Models\OrderDetailsModel;
use App\Models\OrdersModel;
use App\Models\SettingsModel;
use App\Models\SmsLog;
use App\Models\TransactionsModel;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Debtors extends Component
{
    use WithPagination;
    public $showDebtors = false;

    public $order;
    public $showPaymentModal = false;

    public $paymentMethod;
    public $amount_paid;
    public $due_date;
    public $owing;

    public $currency;

    public function mount()
    {
        $settings = SettingsModel::getSettingsData();
        $this->currency = $settings['currency'] ?? 'GHS';
    }


    public function sendReminderDebtor($orderNumber)
    {
        $debtor = OrdersModel::with('customer')->where('order_number', $orderNumber)->first();

        if (!$debtor) {
            $this->addError('sms', "Debtor with order number {$orderNumber} not found.");
            return;
        }

        if (!$debtor->customer || !$debtor->customer->phone) {
            $this->addError('sms', "Customer information or phone number missing for order {$orderNumber}.");
            return;
        }

        try {

            $placeholders = [
                'customer_name' => $debtor->customer?->name,
                'due_date' => $debtor->due_date,
                'balance' => $this->currency . '' . number_format($debtor->balance, 2),
                'order_number' => $debtor->order_number,
            ];

            $smsMessage = generateSmsMessage('payment_reminder', $placeholders);
            $status = SmsLog::sendSMS($debtor->customer->phone, $smsMessage) ? 'sent' : 'failed';
            // Save SMS log
            SmsLog::updateOrCreate([
                'recipient_phone' => $debtor->customer->phone,
                'message' => $smsMessage,
            ]);

            if ($status === 'sent') {
                toastr()->success("Reminder sent successfully to {$debtor->customer->name}.");
            } else {
                toastr()->error("Failed to send reminder to {$debtor->customer->name}.");
            }
        } catch (\Exception $e) {
            $this->addError('sms', "Failed to send reminder to {$debtor->customer->name}: {$e->getMessage()}");
        }
    }

    public function sendReminderToAllDebtors()
    {
        $debtors = OrdersModel::with('customer')
            ->where('payment_method', 'credit')
            ->whereDate('due_date', '>=', now())
            ->get();

        $this->sendSmsAndLog($debtors, 'payment_reminder', function ($record) {
            return [
                'customer_name' => $record->customer?->name,
                'due_date' => $record->due_date,
                'balance' => $this->currency . '' . number_format($record->balance, 2),
                'order_number' => $record->order_number,
            ];
        });
    }

    private function sendSmsAndLog($results, $messageType, $placeholdersCallback)
    {
        $successCount = 0;
        $failureCount = 0;

        foreach ($results as $record) {
            $placeholders = $placeholdersCallback($record);
            $smsMessage = generateSmsMessage($messageType, $placeholders);

            try {
                // Safe access for customer phone
                $phone = $record->customer?->phone;

                if (!$phone) {
                    // If no phone number, skip
                    $this->addError('sms', "No phone number found for customer: {$record->customer?->name}");
                    $failureCount++;
                    continue;
                }

                $status = SmsLog::sendSMS($phone, $smsMessage) ? 'sent' : 'failed';

                if ($status === 'sent') {
                    $successCount++;
                } else {
                    $failureCount++;
                }

                // Log the result to database
                SmsLog::updateOrCreate(
                    [
                        'recipient_phone' => $phone,
                        'message' => $smsMessage,
                    ]
                );
            } catch (\Exception $e) {
                $failureCount++;
                $this->addError('sms', "Failed to send SMS to {$record->customer?->name} ({$phone}): {$e->getMessage()}");
            }
        }

        // Provide feedback to user
        if ($successCount > 0 && $failureCount === 0) {
            toastr()->success("$successCount SMS sent successfully.");
        } elseif ($successCount > 0 && $failureCount > 0) {
            toastr()->warning("$successCount SMS sent successfully, but $failureCount failed.");
        } elseif ($successCount === 0 && $failureCount > 0) {
            toastr()->error("All SMS failed to send. Please check your SMS gateway or internet connection.");
        } else {
            toastr()->info("No remainders was sent.");
        }
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
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error('Payment failed: ' . $e->getMessage());
        }
    }

    public function validateAndShowModal($orderNumber)
    {
        $order = OrdersModel::where('order_number', $orderNumber)->first();
        // properly check here if the mounted order is not empty
        if (!$order) {
            toastr()->error('No order was found.');
            return;
        }
        // Dispatch modal
        $this->order = $order;
        $this->owing = $order->balance;
        $this->dispatch('showPaymentModal');
    }

    public $start_date;
    public $end_date;

    public $search = '';


    #[Title('Debtors')]
    public function render()
    {
        $debtors = OrdersModel::where('payment_method', 'credit')
            ->when(!in_array(auth('web')->user()->role, ['admin', 'manager']), function ($query) {
                $query->where('user_id', auth('web')->id());
            })
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('due_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('due_date', '<=', $this->end_date);
            })
            ->latest()
            ->paginate(paginationLimit());
        return view('livewire.admin.debtors', ['debtors' => $debtors]);
    }
}
