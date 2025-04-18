<?php

namespace App\Livewire\Admin;

use App\Exports\ExportProducts;
use App\Models\CategoriesModel;
use App\Models\ProductsModel;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

class Products extends Component
{
    use WithPagination;
    public $search = ''; // Search term
    public $category = ''; // Search term
    public $productUid;

    protected $queryString = ['search'];

    public $category_id;

    public $confirmingDelete = false;

    // Livewire search listener
    protected $listeners = ['refreshPage' => '$refresh'];
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }


    public $showDelete = false;

    #[On('delete-order')]
    public function handleDelete()
    {
        if (!can_cashier_delete_data()) {
            return;
        }
        $user = auth('web')->user();
        $uid = $user->uuid;
        $product = ProductsModel::where('uuid', $this->productUid)
            ->with('supplier')
            ->firstOrFail();

        // Unlink image before deleting
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        // if (!in_array($user->role, ['admin', 'manager'])) {
        //     $product->status = false;
        //     $product->save();

        //     // forward request to admins and managers
        //     $this->dispatch('forward-request', product: $product, user: $user);
        //     $this->dispatch('request-sent', product: $product);
        //     $this->showDelete = false;
        //     return;
        // }
        $product->delete();
        $this->showDelete = false;
        toastr('ProductDeleted Successfully', 'success');
    }

    public function confirmDelete($uuid)
    {
        if (!can_cashier_delete_data()) {
            return;
        }
        $product = ProductsModel::where('uuid', $uuid)->firstOrFail();
        $this->productUid = $product->uuid;
        $this->showDelete = true;
    }

    public function deleteProduct()
    {
        if ($this->product_id) {
            $product = ProductsModel::find($this->product_id);

            if ($product) {
                $product->delete();
                toastr()->success('Product deleted successfully.');
            } else {
                toastr()->error('Product not found.');
            }

            $this->productUid = null; // Reset the product ID
        } else {
            toastr()->error('No product selected.');
        }
        $this->reset();
        $this->confirmingDelete = false; // Hide confirmation dialog
    }


    public function exportAs($type)
    {
        return Excel::download(new ExportProducts, now() . '_products.' . $type);
    }

    #[Title('Products')]
    public function render()
    {
        // Fetch products based on the search term or paginate all products
        $products = ProductsModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', '=', $this->category);
            })
            ->latest()
            ->paginate(4)->withQueryString();
        return view('livewire.admin.products', [
            'products' => $products,
            'categories' => CategoriesModel::latest()->get(),
        ]);
    }
}
