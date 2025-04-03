<?php

namespace App\Livewire\Forms;

use App\Livewire\Admin\Categories;
use App\Models\CategoriesModel;
use App\Models\ProductsModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryInfo extends Component
{
    use WithPagination;

    public $categories = [];
    public $categoryId;
    public $search = '';
    public $status;
    public $category;

    public function mount(CategoriesModel $category)
    {
        $this->categoryId = $category->id;
    }


    public function updatingSearch()
    {

        $this->categories = CategoriesModel::query()
            ->when(strlen($this->search > 1), function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function findCategory($id)
    {
        $this->categoryId = $id;
        $this->search = '';
        $this->categories = [];
    }

    public function getCategoryProductsProperty()
    {
        return  ProductsModel::with('category')
            ->where('category_id', $this->categoryId)
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(paginationLimit());
    }


    #[Title('Category Products')]
    public function render()
    {
        return view('livewire.forms.category-info', [
            'cproducts' => $this->categoryProducts,
        ]);
    }
}
