<?php

namespace App\Exports;

use App\Models\CustomersModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportCustomers implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Create a new class instance.
     */
    public function collection()
    {
        return CustomersModel::all();
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->address,
            $customer->counter,
            $customer->created_at
        ];
    }

    public function headings(): array
    {
        return [
            "#",
            "Name",
            "Email",
            "Phone",
            "Address",
            'Counter',
            "Created At"
        ];
    }
}
