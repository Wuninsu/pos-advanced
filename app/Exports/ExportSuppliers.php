<?php

namespace App\Exports;

use App\Models\SuppliersModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportSuppliers implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Create a new class instance.
     */
    public function collection()
    {
        return SuppliersModel::all();
    }

    public function map($supplier): array
    {
        return [
            $supplier->id,
            $supplier->company_name,
            $supplier->contact_person,
            $supplier->address,
            $supplier->email,
            $supplier->phone,
            $supplier->status,
            $supplier->website,
            $supplier->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            "#",
            "Company name",
            "Contact Person",
            "Address",
            "Email",
            "Phone",
            "Status",
            "Website",
            "Created At"
        ];
    }
}
