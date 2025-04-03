<?php

namespace App\Livewire\Forms;

use App\Helpers\ActivityLogger;
use App\Models\ActivityLog;
use App\Models\OrdersModel;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ProfileForm extends Component
{
    use WithPagination;
    public $profile;
    public $search = '';
    public $status = '';
    public function mount(User $user)
    {
        $uid = $user->uuid;
        // if (!in_array($user->role, ['admin', 'manager']) && ($uid != auth('web')->user()->uuid)) {
        //     abort(404);
        // }
        if (!in_array(auth('web')->user()->role, ['admin', 'manager']) && ($uid != auth('web')->user()->uuid)) {
            abort(404);
        }
        $this->profile = $user;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function getOrdersProperty()
    {
        return OrdersModel::where('user_id', $this->profile->id)
            ->where(function ($query) {
                if (strlen($this->search) > 2) {
                    $query->where('order_amount', 'like', "%{$this->search}%")
                        ->orWhere('order_number', 'like', "%{$this->search}%")
                        ->orWhereDate('created_at', 'like', "%{$this->search}%");
                }
                if ($this->status) {
                    $query->where('status', $this->status);
                }
            })
            ->latest()
            ->paginate(10);
    }


    public $currentTab = 'overview';


    public function switchTab($tabId)
    {
        $this->currentTab = $tabId;
    }


    public function getOrdersInfo()
    {
        $totalOrders = OrdersModel::where('user_id', $this->profile->id)->count();

        $lowStock = lowStock();
        $pending = OrdersModel::where('status', 'pending')->where('user_id', $this->profile->id)->count();
        $canceled = OrdersModel::where('status', 'canceled')->where('user_id', $this->profile->id)->count();
        $completed = OrdersModel::where('status', 'completed')->where('user_id', $this->profile->id)->count();

        // Avoid division by zero
        $pendingAmount = OrdersModel::where('status', 'pending')->where('user_id', $this->profile->id)->sum('order_amount');
        $canceledAmount = OrdersModel::where('status', 'canceled')->where('user_id', $this->profile->id)->sum('order_amount');
        $completedAmount = OrdersModel::where('status', 'completed')->where('user_id', $this->profile->id)->sum('order_amount');

        $percentPending = $totalOrders ? ($pending / $totalOrders) * 100 : 0;
        $percentCanceled = $totalOrders ? ($canceled / $totalOrders) * 100 : 0;
        $percentCompleted = $totalOrders ? ($completed / $totalOrders) * 100 : 0;



        return [
            'pending' => [
                'count' => $pending,
                'amount' => intWithStyle($pendingAmount),
                'percentage' => $percentPending,
            ],
            'canceled' => [
                'count' => $canceled,
                'amount' => intWithStyle($canceledAmount),
                'percentage' => $percentCompleted,
            ],
            'completed' => [
                'count' => $completed,
                'amount' => intWithStyle($completedAmount),
                'percentage' => $percentPending,
            ],
        ];
    }

    #[Title('User Profile')]
    public function render()
    {
        $logs = ActivityLog::with('user')
            ->where('user_id', $this->profile->id)->latest()->paginate(10);

        return view('livewire.forms.profile-form', [
            'orders' => $this->orders,
            'logs' => $logs,
            'orderInfo' => $this->getOrdersInfo(),
        ]);
    }
}
