<?php

namespace App\Livewire;

use App\Models\Invoices;
use App\Models\OrderDetailsModel;
use App\Models\OrdersModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ViewInvoice extends Component
{
    public $invoice;
    public $status;
    public $showConfirmationModel = false;

    public function mount($invoice)
    {
        $this->invoice = Invoices::with(['invoiceDetail.product'])
            ->where('invoice_number', $invoice)
            ->firstOrFail();
        $this->status = $this->invoice->status;
    }
    public function updatedStatus()
    {
        $invoice = Invoices::findOrFail($this->invoice->id);
        if ($this->status == 'paid') {
            $this->showConfirmationModel = true;
            return;
        }
        $invoice->status = $this->status;
        $invoice->save();
        $this->invoice = $invoice;
        toastr('Invoice status has been changed', 'info');
    }

    public function confirmInvoicePaid()
    {
        DB::beginTransaction();
        try {
            $invoice = Invoices::with('invoiceDetail')->findOrFail($this->invoice->id);

            // Step 1: Update Invoice Status
            $invoice->status = 'paid';
            $invoice->save();

            // Step 2: Create new Order
            $order = OrdersModel::create([
                'order_number' => generateOrderNumber(),
                'user_id' => auth('web')->id(),
                'customer_id' => $invoice->customer_id,
                'order_amount' => $invoice->invoice_amount,
                'discount' => $invoice->discount,
                'amount_payable' => $invoice->amount_payable,
                'amount_paid' => $invoice->amount_paid,
                'payment_method' => 'cash',
                'status' => 'pending',
            ]);

            // Step 3: Clone Invoice Details to Order Details
            foreach ($invoice->invoiceDetail as $item) {
                OrderDetailsModel::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_amount' => $item->total_amount,
                    'description' => $item->description,
                ]);
            }

            DB::commit();

            $this->invoice = $invoice; // refresh invoice
            $this->showConfirmationModel = false;

            toastr('Invoice confirmed and converted to an order.', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            toastr('Something went wrong: ' . $e->getMessage(), 'error');
        }
    }


    #[Title('Invoice View')]
    public function render()
    {
        return view('livewire.view-invoice');
    }
}
