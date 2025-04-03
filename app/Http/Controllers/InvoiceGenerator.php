<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\OrdersModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoiceGenerator extends Controller
{
    public function index()
    {
        $pdf = Pdf::loadView('exports.suppliers');
        return $pdf->stream();
    }

    public function downloadPdf($invoice)
    {
        $invoice = Invoices::with(['order.orderDetails.product', 'order'])
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        $pdf = Pdf::loadView('exports.invoice', ['invoice' => $invoice]);
        return $pdf->download(now() . '_' . $invoice->invoice_number . '.pdf');
    }


    public function downloadOrderPdf($order)
    {
        $order = OrdersModel::with(['orderDetails.product', 'orderDetails'])
            ->where('order_number', $order)
            ->firstOrFail();

        $pdf = Pdf::loadView('exports.order', ['order' => $order]);
        return $pdf->download(now() . '_' . $order->order_number . '.pdf');
    }
}
