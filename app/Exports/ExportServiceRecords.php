<?php

namespace App\Exports;

use App\Models\ProductsModel;
use App\Models\ServiceRequest;
use App\Models\SettingsModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportServiceRecords implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Create a new class instance.
     */
    public function collection()
    {
        return ServiceRequest::latest()->get();
    }

    public function map($record): array
    {
        return [
            $record->id,
            $record->user->name ?? 'Unknown',
            $record->service->name ?? 'N/A',
            $record->date,
            $record->client,
            $record->loading_place,
            $record->destination,
            $record->quantity,
            $record->unit_price,
            $record->unit->name ?? 'N/A',
            $record->amount,
            $record->revenue,
            $record->remarks,
            $record->other_expenses,
            $record->fuel,
            $record->allowance,
            $record->feeding,
            $record->maintenance,
            $record->owner,
            $record->status,
        ];
    }

    public function headings(): array
    {

        $settings = SettingsModel::getSettingsData();

        return [
            "#",
            'sales rep',
            'service',
            'date',
            'client',
            'loading_place',
            'destination',
            'quantity',
            'price' . $settings['currency'],
            'unit',
            'amount' . $settings['currency'],
            'revenue',
            'remarks',
            'other_expenses',
            'fuel',
            'allowance',
            'feeding',
            'maintenance',
            'owner',
            'status'
        ];
    }
}
