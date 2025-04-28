<?php

namespace App\Livewire\Admin;

use App\Models\Expenditure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Expenditures extends Component
{

    use WithPagination;

    public $description, $user_id, $category, $amount, $date;
    public $expenditure_id;
    public $showDelete = false;
    public function mount() {}

    public function rules()
    {
        return [
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ];
    }



    public function confirmDelete($id)
    {
        $this->showDelete = true;
        $this->expenditure_id = $id;
    }

    #[On('delete')]
    public function handleDelete()
    {
        if (auth('web')->user()->role == 'admin') {
            $expenditure = Expenditure::findOrFail($this->expenditure_id);
            if ($expenditure) {
                // Delete expenditure from database
                $expenditure->delete();
                $this->showDelete = false;
                $this->expenditure_id = null;
                toastr()->success('Expenditure deleted successfully.');
            } else {
                toastr()->error('No expenditure was found');
            }
        } else {
            toastr()->warning('You do not have enough permissions to delete expenditure');
        }
    }


    public function loadExpenditureData($id)
    {
        $expenditure = Expenditure::findOrFail($id);
        if ($expenditure) {
            $this->expenditure_id = $expenditure->id;
            $this->category = $expenditure->category;
            $this->description = $expenditure->description;
            $this->amount = $expenditure->amount;
            $this->user_id = $expenditure->user_id;
            $this->date = $expenditure->date;
            $this->dispatch('show-modal');
        }
    }



    public function createOrUpdateExpenditure()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $expenditure = Expenditure::find($this->expenditure_id);


            if ($this->expenditure_id) {
                // Checks if user is admin
                // if (!auth('web')->user()->role == 'admin') {
                // Update existing expenditure
                $data =  $expenditure->update([
                    'amount' => strip_tags($this->amount),
                    'category' => strip_tags($this->category),
                    'description' => strip_tags($this->description),
                    'date' => $this->date
                ]);
                // } else {
                //     toastr()->warning('You do not have enough permissions to delete expenditure');
                //     $this->resetPage();
                //     $this->dispatch('close-modal');
                //     return;
                // }
            } else {
                // Create new slide
                $data =   Expenditure::create([
                    'amount' => strip_tags($this->amount),
                    'category' => strip_tags($this->category),
                    'description' => strip_tags($this->description),
                    'user_id' => Auth::user()->id,
                    'date' => $this->date
                ]);
            }

            DB::commit();

            toastr()->success($this->expenditure_id ? 'Expenditure updated successfully' : 'Expenditure created successfully');
            $this->resetPage();
            $this->dispatch('close-modal');
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error('Something went wrong! ' . $e->getMessage());
        }
    }


    public function resetPage()
    {
        $this->amount = null;
        $this->category = null;
        $this->description = null;
        $this->date = null;
        $this->user_id = null;
        $this->expenditure_id = null;
    }
    public $search = '';

    #[Title('Expenditures')]
    public function render()
    {
        $expenditures = Expenditure::query()
            ->when($this->search, function ($query) {
                $query->where('amount', 'like', '%' . $this->search . '%')
                    ->orWhere('category', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(paginationLimit());

        return view('livewire.admin.expenditures', [
            'expenditures' => $expenditures
        ]);
    }
}
