<?php

namespace App\Exports;

use App\Models\CategoriesModel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportCategories implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;
    /**
     * Create a new class instance.
     */
    public function collection()
    {
        return CategoriesModel::latest()->get();
    }

    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->description,
            $category->status,
            $category->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            "#",
            "Name",
            "Description",
            "Status",
            "Created At"
        ];
    }
}
