<?php

namespace App\Livewire\Admin;

use App\Exports\ExportUsers;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Users extends Component
{
    use WithPagination;
    public $search = '';
    public $search_role = '';
    public $user_uuid;
    public $showDelete = false;

    protected $queryString = ['search'];


    public function confirmDelete($uuid)
    {
        $this->user_uuid = $uuid;
        $this->showDelete = true;
    }

    public function handleDelete()
    {
        if ($this->user_uuid) {
            $user = ModelsUser::where('uuid', $this->user_uuid)->firstOrFail();

            if ($user) {
                $user->delete();
                toastr()->success('User deleted successfully.');
            } else {
                toastr()->error('User not found.');
            }

            $this->user_uuid = null;
        } else {
            toastr()->error('No user selected.');
        }
        $this->reset();
        $this->showDelete = false;
    }



    public function exportAs($type)
    {
        return Excel::download(new ExportUsers, now() . '_users.' . $type);
    }




    #[Title('Users')]
    public function render()
    {
        $users = ModelsUser::query()
            ->when($this->search, function ($query) {
                $query->where('username', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->when($this->search_role, function ($query) {
                $query->where('role', '=', $this->search_role);
            })
            ->latest()
            ->paginate(paginationLimit());

        return view('livewire.admin.users', [
            'users' => $users
        ]);
    }
}
