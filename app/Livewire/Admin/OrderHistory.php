<?php

namespace App\Livewire\Admin;

use App\Models\OrdersModel;
use Livewire\Component;
use Illuminate\Support\Carbon;

class OrderHistory extends Component
{
    public $orders = [];

    protected $listeners = ['showOrdersModal'];

    public function showOrdersModal()
    {
        $this->orders = OrdersModel::whereDate('created_at', Carbon::today())->get();
        $this->dispatch('show-modal', id: 'ordersModal'); // Livewire 3 event to show modal
    }

    
    
    public function render()
    {
        return view('livewire.admin.order-history');
    }
}
