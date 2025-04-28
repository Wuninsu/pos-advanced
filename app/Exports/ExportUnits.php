<?php

namespace App\Exports;

use App\Models\CategoriesModel;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportUnits implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;
    /**
     * Create a new class instance.
     */
    public function collection()
    {
        return Unit::latest()->get();
    }

    public function map($unit): array
    {
        return [
            $unit->id,
            $unit->name,
            $unit->description,
            $unit->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            "#",
            "Name",
            "Description",
            "Created On"
        ];
    }
}
