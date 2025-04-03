<?php

namespace App\Livewire;

use App\Models\OrdersModel;
use App\Models\TransactionsModel;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class OrderDetails extends Component
{
    public $order;


    public function mount($order)
    {
        $this->order = OrdersModel::with(['orderDetails.product', 'transactions'])
            ->where('order_number', $order)
            ->firstOrFail();
        $this->status = $this->order->status;
    }

    public $status;

    public function updatedStatus()
    {
        $order = OrdersModel::findOrFail($this->order->id);
        $order->status = $this->status;
        $order->save();
        $this->order = $order;
        toastr('Order status has been changed', 'info');
    }

    #[Title('Order Detail')]
    public function render()
    {
        return view('livewire.order-details');
    }
}
