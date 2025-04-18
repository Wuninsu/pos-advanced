<?php

namespace App\Livewire\Admin;

use App\Exports\ExportInvoice;
use App\Models\Invoices as ModelsInvoices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Invoices extends Component
{
    use WithPagination;
    public $search;
    public $status;
    public $showDelete = false;
    public $invoiceNum;

    #[On('delete-order')]
    public function handleDelete()
    {
        $user = auth('web')->user();
        $uid = $user->uuid;
        $invoice = ModelsInvoices::where('invoice_number', $this->invoiceNum)
            ->with('order.customer')
            ->firstOrFail();
        // if (!in_array($user->role, ['admin', 'manager'])) {
        //     $invoice->status = 'canceled';
        //     $invoice->save();

        //     // forward request to admins and managers
        //     $this->dispatch('forward-request', invoice: $invoice, user: $user);
        //     $this->dispatch('request-sent', invoice: $invoice);
        //     $this->showDelete = false;
        //     return;
        // }
        $invoice->delete();
        $this->showDelete = false;
        toastr('Invoice Deleted Successfully', 'success');
    }

    #[On('delete-invoice')]
    public function deleteNow()
    {
        toastr('Invoice deleted successfully', 'success');
    }
    public function confirmDelete($invoiceNum)
    {
        if (!can_cashier_delete_data()) {
            return;
        }
        $invoice = ModelsInvoices::where('invoice_number', $invoiceNum)->firstOrFail();
        $this->invoiceNum = $invoice->invoice_number;
        $this->showDelete = true;
    }


    public function exportAs($type)
    {
        return Excel::download(new ExportInvoice, now().'_invoices.' . $type);
    }


    #[Title('Invoices')]
    public function render()
    {
        $invoices = ModelsInvoices::query()
            ->when(!in_array(auth('web')->user()->role, ['admin', 'manager']), function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('user_id', auth('web')->id());
                });
            })
            ->when($this->search, function ($query) {
                $query->where('invoice_number', 'like', $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(paginationLimit());

        return view('livewire.admin.invoices', ['invoices' => $invoices]);
    }
}
