<?php

namespace App\Exports;

use App\Models\ProductsModel;
use App\Models\SettingsModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportProducts implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Create a new class instance.
     */
    public function collection()
    {
        return ProductsModel::all();
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category->name ?? 'N/A',
            $product->description,
            $product->stock,
            $product->price,
            $product->sku,
            $product->barcode,
            $product->status,
            $product->supplier->name,
            $product->created_at,
        ];
    }

    public function headings(): array
    {

        $settings = SettingsModel::getSettingsData();

        return [
            "#",
            "Name",
            "Category",
            "Description",
            "Quantity",
            "Price " . $settings['currency'],
            "SKU",
            "Barcode",
            "Status",
            "Supplier",
            "Created At"
        ];
    }
}
