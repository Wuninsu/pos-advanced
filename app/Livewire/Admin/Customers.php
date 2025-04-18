<?php

namespace App\Livewire\Admin;

use App\Exports\ExportCustomers;
use App\Models\CustomersModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Customers extends Component
{
    use WithPagination;
    public $search;

    public $customerId;
    public $showDelete = false;


    public function confirmDelete($id)
    {
        if (!can_cashier_delete_data()) {
            return;
        }
        $this->customerId = $id;
        $this->showDelete = true;
    }

    public function handleDelete()
    {
        if ($this->customerId) {
            $customer = CustomersModel::findOrFail($this->customerId);
            if ($customer) {
                $customer->delete();
                toastr()->success('Customer deleted successfully.');
            } else {
                toastr()->error('Customer not found.');
            }

            $this->customerId = null;
        } else {
            toastr()->error('No customer selected.');
        }
        $this->reset();
        $this->showDelete = false;
    }


    public function exportAs($type)
    {
        return Excel::download(new ExportCustomers, now() . '_customers.' . $type);
    }

    #[Title('Customers')]
    public function render()
    {
        $customers = CustomersModel::query()
            ->when($this->search, function ($query) {
                $query->where('company_name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(paginationLimit());
        return view('livewire.admin.customers', ['customers' => $customers]);
    }
}
