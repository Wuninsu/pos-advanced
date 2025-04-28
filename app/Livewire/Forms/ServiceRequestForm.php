<?php

namespace App\Livewire\Forms;

use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

class ServiceRequestForm extends Component
{
    public $service_request_id;
    public $user_id, $service_id, $date, $client, $loading_place, $destination;
    public $quantity, $unit_price, $unit_of_measurement, $amount, $revenue;
    public $fuel, $allowance, $feeding, $maintenance, $owner, $status;
    public $services, $units;

    public $other_expenses, $remarks;

    public $measurement = '';
    // Validation rules
    public function rules()
    {
        $service = Service::find($this->service_id);

        $isTractor = $service && strtolower($service->name) === 'tractor';

        return [
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'client' => 'required|string|max:255',
            'loading_place' => $isTractor ? 'nullable|string|max:255' : 'required|string|max:255',
            'destination' => $isTractor ? 'nullable|string|max:255' : 'required|string|max:255',
            'quantity' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:0',
            'unit_of_measurement' => 'required|exists:units,id',
            'amount' => 'required|numeric|min:0',
            'revenue' => 'required|numeric|min:0',
            'fuel' => 'nullable|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'feeding' => 'nullable|numeric|min:0',
            'maintenance' => 'nullable|numeric|min:0',
            'owner' => 'nullable|numeric|min:0',
            'other_expenses' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }


    // Mount method to initialize values
    public function mount(ServiceRequest $request)
    {
        if ($request) {
            $this->service_request_id = $request->id;
            $this->service_id = $request->service_id;
            $this->date = $request->date;
            $this->client = $request->client;
            $this->loading_place = $request->loading_place;
            $this->destination = $request->destination;
            $this->quantity = $request->quantity;
            $this->unit_price = $request->unit_price;
            $this->unit_of_measurement = $request->unit_id;
            $this->amount = $request->amount;
            $this->revenue = $request->revenue;
            $this->fuel = $request->fuel;
            $this->allowance = $request->allowance;
            $this->feeding = $request->feeding;
            $this->maintenance = $request->maintenance;
            $this->owner = $request->owner;
            $this->remarks = $request->remarks;
            $this->other_expenses = $request->other_expenses;
            $this->status = $request->status;
        }

        $this->services = Service::all();
        $this->units = Unit::all();
    }

    public function updated($propertyName)
    {
        // Check if quantity or unit_price has been updated
        if ($propertyName == 'quantity' || $propertyName == 'unit_price') {
            $this->quantity = (int) $this->quantity;
            $this->unit_price = (float) $this->unit_price;
            // Calculate amount based on quantity and unit price
            $this->amount = $this->quantity * $this->unit_price;
        }
        if ($propertyName == 'unit_of_measurement') {
            // Calculate amount based on quantity and unit price
            $unit = Unit::find($this->unit_of_measurement);
            $this->measurement = $unit->name . ' ' . '(' . $unit->abbreviation . ')';
        }

        // Recalculate revenue whenever any of the related fields are updated
        if (in_array($propertyName, ['amount', 'allowance', 'fuel', 'feeding', 'maintenance', 'owner', 'other_expenses'])) {
            $rules = [
                'fuel' => 'nullable|numeric|min:0',
                'allowance' => 'nullable|numeric|min:0',
                'feeding' => 'nullable|numeric|min:0',
                'maintenance' => 'nullable|numeric|min:0',
                'owner' => 'nullable|numeric|min:0',
                'other_expenses' => 'nullable|numeric|min:0',
            ];
            $this->validate($rules);
            $this->calculateRevenue();
        }
    }



    public function calculateRevenue()
    {
        $totalPercentage =
            (float) $this->fuel +
            (float) $this->allowance +
            (float) $this->feeding +
            (float) $this->maintenance +
            (float) $this->owner +
            (float) $this->other_expenses;

        if ($totalPercentage >= 100) {
            $this->addError('percentage_total', 'The total percentage value cannot be more than 100%.');
            $this->revenue = 0;
            return;
        }

        // Calculate net revenue
        $this->revenue = $this->amount - (
            ($this->amount * (float) $this->fuel / 100)
            + ($this->amount * (float) $this->allowance / 100)
            + ($this->amount * (float) $this->feeding / 100)
            + ($this->amount * (float) $this->maintenance / 100)
            + ($this->amount * (float) $this->owner / 100)
            + ($this->amount * (float) $this->other_expenses / 100)
        );
    }


    // Save or update method
    public function save()
    {
        $this->validate();
        // DB::beginTransaction();

        // try {
        ServiceRequest::updateOrCreate(
            ['id' => $this->service_request_id],
            [
                'user_id' => auth('web')->id(),
                'service_id' => $this->service_id,
                'date' => $this->date,
                'client' => $this->client,
                'loading_place' => $this->loading_place,
                'destination' => $this->destination,
                'quantity' => $this->quantity,
                'unit_price' => $this->unit_price,
                'unit_id' => $this->unit_of_measurement,
                'amount' => $this->amount,
                'revenue' => $this->revenue,
                'fuel' => $this->fuel,
                'allowance' => $this->allowance,
                'feeding' => $this->feeding,
                'maintenance' => $this->maintenance,
                'owner' => $this->owner,
                'other_expenses' => $this->other_expenses,
                'remarks' => $this->remarks,
                'status' => $this->status ? $this->status : 'pending',
            ]
        );
        // DB::commit();
        toastr()->success($this->service_request_id ? 'Service Request updated successfully!' : 'Service Request created successfully!');
        $this->reset();
        return redirect()->route('service.requests');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     toastr('error', 'Error placing order: ' . $e->getMessage());
        // }
    }

    #[Title('Manage Service Records')]
    public function render()
    {
        return view('livewire.forms.service-request-form');
    }
}
