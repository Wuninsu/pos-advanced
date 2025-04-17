<?php

namespace App\Livewire;

use App\Models\ProductsModel;
use App\Models\SettingsModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class StockAlerts extends Component
{
    use WithPagination;
    public $type;
    public function mount($type)
    {
        $this->type = $type;
    }

    #[Title("Stock Levels")]
    public function render()
    {
        $products = collect(); // Default empty collection in case of no match

        if ($this->type === 'low_stock') {
            $settings = SettingsModel::getSettingsData();
            $lowStock = $settings['low_stock'] ?? 100;

            $products = ProductsModel::where('stock', '>', 0)
                ->where('stock', '<=', $lowStock)
                ->paginate(paginationLimit());
        } elseif ($this->type === 'out_of_stock') {
            $products = ProductsModel::where('stock', 0)
                ->paginate(paginationLimit());
        }

        return view('livewire.stock-alerts', [
            'products' => $products,
        ]);
    }
}
