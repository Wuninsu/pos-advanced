<?php

namespace App\Livewire;

use App\Models\ServiceRequest;
use Livewire\Attributes\Title;
use Livewire\Component;

class ServiceRequestShow extends Component
{
    public $serviceRequest;

    public function mount(ServiceRequest $request)
    {
        $this->serviceRequest = $request;
    }

    #[Title('Service Request Details')]
    public function render()
    {
        return view('livewire.service-request-show');
    }
}
