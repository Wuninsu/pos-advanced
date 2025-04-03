<?php

namespace App\Livewire\Forms;

use App\Models\CustomersModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;

class CustomerForm extends Component
{
    public $status;
    public $customer_id;

    public $name, $address, $email, $phone;

    public function rules()
    {
        return [
            'email' => 'required|email|max:255|unique:customers,email,' . $this->customer_id,
            'name' => 'required|min:4|max:255',
            'phone' => 'required|regex:/^\d{10,13}$/|unique:customers,phone,' . $this->customer_id,
            'address' => 'nullable|string|max:1000',
        ];
    }

    public function mount(CustomersModel $customer)
    {
        if ($customer) {
            $this->name = $customer->name;
            $this->address = $customer->address;
            $this->email = $customer->email;
            $this->phone = $customer->phone;
            $this->customer_id = $customer->id;
        }
    }


    public function save()
    {
        $this->validate();
        CustomersModel::updateOrCreate(
            ['id' => $this->customer_id],
            [
                'name' => $this->name,
                'address' => $this->address,
                'email' => $this->email,
                'phone' => $this->phone,
                'created_by' => Auth::user()->id,
            ]
        );

        if (!$this->customer_id) {
            $this->reset();
        }
        toastr()->success($this->customer_id ? 'Customer updated successfully!' : 'Customer created successfully!');

        return redirect()->route('customers');
    }

    public function getTitleProperty()
    {
        return $this->supplier_id ? 'Edit Customer' : 'Create Customer';
    }


    #[Title('Manage Customers')]
    public function render()
    {
        return view('livewire.forms.customer-form');
    }
}
