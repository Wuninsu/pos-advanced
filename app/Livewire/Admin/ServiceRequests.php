<?php

namespace App\Livewire\Admin;

use App\Exports\ExportServiceRecords;
use App\Models\Service;
use App\Models\ServiceRequest;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;


use App\Exports\ExportProducts;
use Maatwebsite\Excel\Facades\Excel;

class ServiceRequests extends Component
{
    use WithPagination;

    public $search = ''; // Search input for filtering
    public $service_id = ''; // Service ID for filtering by service type
    public $status = ''; // Filter for status
    public $showDelete = false; // To control the modal visibility for adding new request

    protected $queryString = ['search', 'service_id', 'status'];

    public $services, $serviceId;
    // Toggle the modal for adding a new request
    public function mount()
    {
        $this->services = Service::all();
    }

    public function handleDelete()
    {
        if (!preference('allow_rep_delete_services')) {
            return;
        }
        $record = ServiceRequest::where('id', $this->serviceId)
            ->firstOrFail();
        $record->delete();
        $this->showDelete = false;
        $this->serviceId = null;
        toastr('Record Deleted Successfully', 'success');
    }

    public function confirmDelete($id)
    {
        if (!preference('allow_rep_delete_services')) {
            return;
        }
        $service = ServiceRequest::where('id', $id)->firstOrFail();
        $this->serviceId = $service->id;
        $this->showDelete = true;
    }

    public function exportAs($type)
    {
        return Excel::download(new ExportServiceRecords, now() . '_records.' . $type);
    }


    #[Title('Service Requests')]
    public function render()
    {
        // Get the filtered service requests
        $serviceRequests = ServiceRequest::query()
            ->when($this->search, function ($query) {
                return $query->where('client_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->service_id, function ($query) {
                return $query->where('service_id', $this->service_id);
            })
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            })
            ->paginate(paginationLimit()); // Paginate results

        return view('livewire.admin.service-requests', compact('serviceRequests'));
    }
}
