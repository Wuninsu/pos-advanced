<?php

namespace App\Exports;

use App\Models\OrdersModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportOrders implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Create a new class instance.
     */
    public function collection()
    {
        return OrdersModel::latest()->get();
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->order_number,
            $order->order_amount,
            $order->items => count($order->orderDetails) ?? '0',
            $order->status,
            $order->customer->name ?? 'N/A',
            $order->user->username,
            $order->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            "#",
            "Number",
            "Amount",
            "Items",
            "Status",
            "Customer",
            "Cashier",
            "Created At"
        ];
    }
}
