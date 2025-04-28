<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Services extends Component
{
    use WithPagination;
    public $search = ''; // Search term
    public $service_id;
    public $showDelete = false; // To show a confirmation dialog
    public $isEdit = false; // To show a confirmation dialog


    protected $queryString = ['search']; // Persist search term in the query string

    public $name, $description;


    protected function rules()
    {
        return [
            'name' => 'required|min:4|max:255|unique:services,name,' . $this->service_id,
            'description' => 'nullable',
        ];
    }
    public function saveService()
    {
        $validatedData = $this->validate(); // validate user inputs 
        $save = Service::create($validatedData); // save data
        if (!$save) {
            toastr()->error('Fail to create service category. Please try again.'); // send error msg
        }
        toastr()->success(message: 'Service category created successfully.'); // send success msg
        $this->reset(); // reset form fields

    }


    public function editService(Service $service)
    {
        $this->service_id = $service->id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->isEdit = true;
    }

    public function updateService()
    {
        $validatedData = $this->validate();
        $service = Service::find($this->service_id);
        if ($service) {
            $service->update($validatedData);
            $this->reset(); // Clear the form fields
            toastr()->success('Service updated successfully.');
        } else {
            toastr()->error('Service not found.');
        }
    }


    public function confirmDelete($id)
    {
        if (!preference('allow_rep_delete_services')) {
            return;
        }
        $service = Service::findOrFail($id);
        $this->service_id = $service->id;
        $this->showDelete = true;
        // $this->dispatch('confirmed', id: $id);
    }

    public function handleDelete()
    {
        if ($this->service_id) {
            $service = Service::find($this->service_id);

            if ($service) {
                $service->delete();
                toastr()->success('Service deleted successfully.');
            } else {
                toastr()->error('Service not found.');
            }

            $this->service_id = null; // Reset the service ID
        } else {
            toastr()->error('No service selected.');
        }
        $this->reset();
        $this->showDelete = false;
    }


    // public function exportAs($type)
    // {
    //     return Excel::download(new ExportCategories, now() . '_service_categories.' . $type);
    // }

    #[Title('Service Categories')]
    public function render()
    {

        // Fetch categories based on the search term or paginate all categories
        $services = Service::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(paginationLimit());
        return view('livewire.admin.services', ['services' => $services]);
    }
}
