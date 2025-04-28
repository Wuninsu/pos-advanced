<?php

namespace App\Exports;

use App\Models\Invoices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportInvoice implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Create a new class instance.
     */
    public function collection()
    {
        return Invoices::latest()->get();
    }

    public function map($invoice): array
    {
        return [
            $invoice->id,
            $invoice->invoice_number,
            $invoice->total_amount,
            $invoice->tax_amount,
            $invoice->items => count($invoice->order->OrderDetails),
            $invoice->status,
            $invoice->order->customer->name ?? 'N/A',
            $invoice->order->user->username,
            $invoice->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            "#",
            "Number",
            "Amount",
            "Tax",
            "Items",
            "Status",
            "Customer",
            "Made By",
            "Created At"
        ];
    }
}
