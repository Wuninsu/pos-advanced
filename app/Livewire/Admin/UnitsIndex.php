<?php

namespace App\Livewire\Admin;

use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\ExportUnits;
use Maatwebsite\Excel\Facades\Excel;

class UnitsIndex extends Component
{
    use WithPagination;
    public $search = ''; // Search term
    public $unit_id;
    public $showDelete = false; // To show a confirmation dialog
    public $isEdit = false; // To show a confirmation dialog


    protected $queryString = ['search']; // Persist search term in the query string

    public $name, $abbreviation;


    protected function rules()
    {
        return [
            'name' => 'required|min:4|max:255|unique:units,name,' . $this->unit_id,
            'abbreviation' => 'required|string',
        ];
    }
    public function saveUnit()
    {
        $validatedData = $this->validate(); // validate user inputs 
        $save = Unit::create($validatedData); // save data
        if (!$save) {
            toastr()->error('Fail to create unit. Please try again.'); // send error msg
        }
        toastr()->success('Unit created successfully.'); // send success msg
        $this->reset(); // reset form fields

    }


    public function editUnit(Unit $unit)
    {
        $this->unit_id = $unit->id;
        $this->name = $unit->name;
        $this->abbreviation = $unit->abbreviation;
        $this->isEdit = true;
    }

    public function updateUnit()
    {
        $validatedData = $this->validate();
        $unit = Unit::find($this->unit_id);
        if ($unit) {
            $unit->update($validatedData);
            $this->reset(); // Clear the form fields
            toastr()->success('Unit updated successfully.');
        } else {
            toastr()->error('Unit not found.');
        }
    }



    #[On('delete')]
    public function delete($id)
    {
        $uint = Unit::find($id);

        if ($uint) {
            $uint->delete();
            toastr()->success('Uint deleted successfully.');
        } else {
            toastr()->error('Uint not found.');
        }
        $this->reset();
    }

    public function confirmDelete($id)
    {
        if (!preference('allow_rep_delete_units')) {
            return;
        }
        $unit = Unit::findOrFail($id);
        $this->unit_id = $unit->id;
        $this->showDelete = true;
    }

    public function handleDelete()
    {
        if ($this->unit_id) {
            $unit = Unit::find($this->unit_id);

            if ($unit) {
                $unit->delete();
                toastr()->success('Unit deleted successfully.');
            } else {
                toastr()->error('Unit not found.');
            }

            $this->unit_id = null; // Reset the unit ID
        } else {
            toastr()->error('No unit selected.');
        }
        $this->reset();
        $this->showDelete = false;
    }


    public function exportAs($type)
    {
        return Excel::download(new ExportUnits, now() . '_unit_of_measurements.' . $type);
    }

    #[Title('Units')]
    public function render()
    {
        // Fetch categories based on the search term or paginate all categories
        $units = Unit::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(paginationLimit());
        return view('livewire.admin.units-index', ['units' => $units]);
    }
}
