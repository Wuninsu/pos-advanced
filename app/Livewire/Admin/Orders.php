<?php

namespace App\Livewire\Admin;

use App\Events\DeleteOrderRequestReceived;
use App\Events\OrderDeleteEvent;
use App\Exports\ExportOrders;
use App\Models\OrdersModel;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

use function Illuminate\Log\log;

class Orders extends Component
{
    use WithPagination;
    public $search;
    public $status;
    public $order;
    public $showDelete = false;

    #[On('delete-order')]
    public function handleDelete()
    {
        $user = auth('web')->user();
        $uid = $user->uuid;
        $order = OrdersModel::where('order_number', $this->order->order_number)
            ->with('customer')
            ->firstOrFail();

        // if (!in_array($user->role, ['admin', 'manager'])) {
        //     $order->status = 'canceled';
        //     $order->save();

        //     // forward request to admins and managers
        //     $this->dispatch('forward-request', order: $order, user: $user);
        //     $this->dispatch('request-sent', order: $order);
        //     $this->showDelete = false;
        //     return;
        // }
        $order->delete();
        $this->showDelete = false;
        toastr('Order Deleted Successfully', 'success');
    }

    public function confirmDelete($orderNum)
    {
        if (!can_cashier_delete_data()) {
            return;
        }
        $order = OrdersModel::where('order_number', $orderNum)
            ->with('customer') // Eager load the related customer
            ->firstOrFail();
        if ($order) {
            $this->order = $order;
            $this->showDelete = true;
        }
    }

    public function exportAs($type)
    {
        return Excel::download(new ExportOrders, now() . '_orders.' . $type);
    }
    #[Title('Orders')]
    public function render()
    {

        // dd(auth('web')->user()->role);
        $orders = OrdersModel::query()
            ->when(!in_array(auth('web')->user()->role, ['admin', 'manager']), function ($query) {
                $query->where('user_id', auth('web')->id());
            })
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(paginationLimit());

        return view('livewire.admin.orders', ['orders' => $orders]);
    }
}
