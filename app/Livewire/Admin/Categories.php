<?php

namespace App\Livewire\Admin;

use App\Exports\ExportCategories;
use App\Models\CategoriesModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class Categories extends Component
{
    use WithPagination;
    public $search = ''; // Search term
    public $category_id;
    public $showDelete = false; // To show a confirmation dialog
    public $isEdit = false; // To show a confirmation dialog


    protected $queryString = ['search']; // Persist search term in the query string

    public $name, $description, $status;


    protected function rules()
    {
        return [
            'name' => 'required|min:4|max:255|unique:categories,name,' . $this->category_id,
            'description' => 'nullable',
            'status' => 'nullable',
        ];
    }
    public function saveCategory()
    {
        $validatedData = $this->validate(); // validate user inputs 
        $save = CategoriesModel::create($validatedData); // save data
        if (!$save) {
            toastr()->error('Fail to create category. Please try again.'); // send error msg
        }
        toastr()->success('Category created successfully.'); // send success msg
        $this->reset(); // reset form fields

    }


    public function editCategory(CategoriesModel $category)
    {
        $this->category_id = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->status = $category->status;
        $this->isEdit = true;
    }

    public function updateCategory()
    {
        $validatedData = $this->validate();
        $category = CategoriesModel::find($this->category_id);
        if ($category) {
            $category->update($validatedData);
            $this->reset(); // Clear the form fields
            toastr()->success('Category updated successfully.');
        } else {
            toastr()->error('Category not found.');
        }
    }



    #[On('delete')]
    public function delete($id)
    {
        $category = CategoriesModel::find($id);

        if ($category) {
            $category->delete();
            toastr()->success('Category deleted successfully.');
        } else {
            toastr()->error('Category not found.');
        }
        $this->reset();
    }

    public function confirmDelete($id)
    {
        if (!can_cashier_delete_data()) {
            return;
        }
        $category = CategoriesModel::findOrFail($id);
        $this->category_id = $category->id;
        $this->showDelete = true;
        // $this->dispatch('confirmed', id: $id);
    }


    public function handleDelete()
    {
        if ($this->category_id) {
            $category = CategoriesModel::find($this->category_id);

            if ($category) {
                $category->delete();
                toastr()->success('Category deleted successfully.');
            } else {
                toastr()->error('Category not found.');
            }

            $this->category_id = null; // Reset the category ID
        } else {
            toastr()->error('No category selected.');
        }
        $this->reset();
        $this->showDelete = false;
    }


    public function exportAs($type)
    {
        return Excel::download(new ExportCategories, now() . '_categories.' . $type);
    }

    #[Title('Categories')]
    public function render()
    {

        // Fetch categories based on the search term or paginate all categories
        $categories = CategoriesModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(paginationLimit());
        return view('livewire.admin.categories', ['categories' => $categories]);
    }
}
