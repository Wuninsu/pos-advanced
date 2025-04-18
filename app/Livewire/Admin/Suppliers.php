<?php

namespace App\Livewire\Admin;

use App\Exports\ExportProducts;
use App\Exports\ExportSuppliers;
use App\Models\SuppliersModel;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportModels\SupportModels;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Pdf as WriterPdf;

class Suppliers extends Component
{
    use WithPagination;
    public $search;




    public $supplierId;
    public $showDelete = false;


    public function confirmDelete($id)
    {
        if (!can_cashier_delete_data()) {
            return;
        }
        $this->supplierId = $id;
        $this->showDelete = true;
    }

    public function handleDelete()
    {
        if (!can_cashier_delete_data()) {
            return;
        }
        if ($this->supplierId) {
            $supplier = SuppliersModel::findOrFail($this->supplierId);
            if ($supplier) {
                $supplier->delete();
                toastr()->success('Supplier deleted successfully.');
            } else {
                toastr()->error('Supplier not found.');
            }

            $this->supplierId = null;
        } else {
            toastr()->error('No supplier selected.');
        }
        $this->reset();
        $this->showDelete = false;
    }


    public function exportAs($type)
    {
        return Excel::download(new ExportSuppliers, now() . '_suppliers.' . $type);
    }

    #[Title('Suppliers')]
    public function render()
    {
        // Fetch suppliers based on the search term or paginate all suppliers
        $suppliers = SuppliersModel::query()
            ->when($this->search, function ($query) {
                $query->where('company_name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(paginationLimit());

        return view('livewire.admin.suppliers', ['suppliers' => $suppliers]);
    }
}
