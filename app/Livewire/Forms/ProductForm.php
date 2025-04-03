<?php

namespace App\Livewire\Forms;

use App\Models\CategoriesModel;
use App\Models\ProductsModel;
use App\Models\SuppliersModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;

class ProductForm extends Component
{
    use WithFileUploads;

    public $status, $sku, $barcode, $product_id;
    public $category;
    public $supplier;
    public $name;
    public $price;
    public $stock;
    public $description;
    public $image, $showImg;

    public $categories, $suppliers;

    public function rules()
    {
        return [
            'category' => 'required|exists:categories,id',
            'supplier' => 'required|exists:suppliers,id',
            'name' => 'required|min:4|max:255|unique:products,name,' . $this->product_id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|boolean',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $this->product_id,
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $this->product_id,
        ];
    }

    public function mount(ProductsModel $product)
    {
        if ($product) {
            $this->name = $product->name;
            $this->sku = $product->sku;
            $this->barcode = $product->barcode;
            $this->status = $product->status == 1 ? true : false;
            $this->showImg = $product->img;
            $this->description = $product->description;
            $this->stock = $product->stock;
            $this->price = $product->price;

            $this->supplier = $product->supplier_id;
            $this->category = $product->category_id;

            $this->product_id = $product->id;
        }

        $this->categories = CategoriesModel::all();
        $this->suppliers = SuppliersModel::all();
    }


    public function save()
    {
        $this->validate();

        $product = ProductsModel::find($this->product_id);
        $filePath = $product ? $product->img : null;

        // Handle file upload if a new image is selected
        if ($this->image) {
            if ($filePath) {
                if (Storage::disk('public')->exists($product->img)) {
                    Storage::disk('public')->delete($product->img);
                }
            }
            $filePath = uploadFile($this->image, 'products');
        }


        ProductsModel::updateOrCreate(
            ['id' => $this->product_id],
            [
                'name' => $this->name,
                'status' => $this->status,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'sku' => $this->sku,
                'barcode' => $this->barcode,
                'category_id' => $this->category,
                'supplier_id' => $this->supplier,
                'img' => $filePath,
                'user_id' => Auth::user()->id,
            ]
        );

        if (!$this->product_id) {
            $this->reset();
        }
        toastr()->success($this->product_id ? 'Product updated successfully!' : 'Product created successfully!');
        $this->reset();
        $this->clearTemporaryFiles();
        return redirect()->route('products');
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'name' && $this->name && $this->category && $this->supplier) {
            $this->sku = $this->generateSKU();
        }
    }

    private function generateSKU()
    {
        // Fetch category and supplier names from their respective models
        $category = CategoriesModel::find($this->category);
        $supplier = SuppliersModel::find($this->supplier);

        // Get first 3 letters of each attribute
        $namePart = strtoupper(substr($this->name, 0, 3));
        $categoryPart = $category ? strtoupper(substr($category->name, 0, 3)) : 'XXX';
        $supplierPart = $supplier ? strtoupper(substr($supplier->company_name, 0, 3)) : 'YYY';

        // Generate a random 4-digit number to ensure uniqueness
        $randomNumber = rand(1000, 9999);

        // Combine parts to form SKU
        return "{$namePart}-{$categoryPart}-{$supplierPart}-{$randomNumber}";
    }

    private function clearTemporaryFiles()
    {
        // Delete all temporary files from Livewire folder
        Storage::deleteDirectory('livewire-tmp');
    }

    #[Title('Manage Products')]
    public function render()
    {
        return view('livewire.forms.product-form');
    }
}
