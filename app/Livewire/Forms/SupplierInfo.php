<?php

namespace App\Livewire\Forms;

use App\Models\ProductsModel;
use App\Models\SuppliersModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierInfo extends Component
{
    use WithPagination;

    public $suppliers = [];
    public $supplierId;
    public $search = '';
    public $status;
    public function mount(SuppliersModel $supplier)
    {
        $this->supplierId = $supplier->id;
    }


    public function updatingSearch()
    {

        $this->suppliers = SuppliersModel::query()
            ->when(strlen($this->search > 1), function ($query) {
                $query->where('company_name', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function findSupplier($id)
    {
        $this->supplierId = $id;
        $this->search = '';
        $this->suppliers = [];
        // $this->reset();
    }

    public function getSupplierProductsProperty()
    {
        return  ProductsModel::with('supplier')
            ->where('supplier_id', $this->supplierId)
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(paginationLimit());
    }
    #[Title('Supplier Products')]
    public function render()
    {
        return view('livewire.forms.supplier-info', [
            'sproducts' => $this->supplierProducts,
        ]);
    }
}
