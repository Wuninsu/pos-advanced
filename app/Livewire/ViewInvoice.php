<?php

namespace App\Livewire;

use App\Models\Invoices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewInvoice extends Component
{
    public $invoice;

    public function mount($invoice)
    {
        $this->invoice = Invoices::with(['order.orderDetails.product', 'order'])
            ->where('invoice_number', $invoice)
            ->firstOrFail();
    }


    #[Title('Invoice View')]
    public function render()
    {
        return view('livewire.view-invoice');
    }
}
