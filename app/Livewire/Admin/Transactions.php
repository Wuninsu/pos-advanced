<?php

namespace App\Livewire\Admin;

use App\Models\TransactionsModel;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{

    use WithPagination;
    public $search;

    #[Title('Orders')]
    public function render()
    {

        $transactions = TransactionsModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(paginationLimit());
        return view('livewire.admin.transactions', ['transactions' => $transactions]);
    }
}
