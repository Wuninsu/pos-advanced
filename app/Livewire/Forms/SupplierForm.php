<?php

namespace App\Livewire\Forms;

use App\Models\SuppliersModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;

use Livewire\Component;

class SupplierForm extends Component
{
    public $status;
    public $supplier_id;

    public $company_name, $contact_person, $user_id, $address, $email, $phone, $website;

    public function rules()
    {
        return [
            'email' => 'required|email|max:255|unique:suppliers,email,' . $this->supplier_id,
            'company_name' => 'required|min:4|max:255|unique:suppliers,company_name,' . $this->supplier_id,
            'phone' => 'required|regex:/^\d{10,13}$/|unique:suppliers,phone,' . $this->supplier_id,
            'contact_person' => 'required',
            'address' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
            'website' => 'nullable|url',
        ];
    }

    public function mount(SuppliersModel $supplier)
    {
        if ($supplier) {
            $this->company_name = $supplier->company_name;
            $this->contact_person = $supplier->contact_person;
            $this->status = $supplier->status;
            $this->address = $supplier->address;
            $this->website = $supplier->website;
            $this->email = $supplier->email;
            $this->phone = $supplier->phone;
            $this->supplier_id = $supplier->id;
        }
    }


    public function save()
    {
        $this->validate();
        SuppliersModel::updateOrCreate(
            ['id' => $this->supplier_id],
            [
                'company_name' => $this->company_name,
                'status' => $this->status,
                'address' => $this->address,
                'website' => $this->website,
                'contact_person' => $this->contact_person,
                'email' => $this->email,
                'phone' => $this->phone,
                'user_id' => Auth::user()->id,
            ]
        );

        if (!$this->supplier_id) {
            $this->reset();
        }
        toastr()->success($this->supplier_id ? 'Supplier updated successfully!' : 'Supplier created successfully!');

        return redirect()->route('suppliers');
    }

    public function getTitleProperty()
    {
        return $this->supplier_id ? 'Edit Supplier' : 'Create Supplier';
    }

    #[Title('Manage Suppliers')]
    public function render()
    {
        return view('livewire.forms.supplier-form');
    }
}
