<?php

namespace App\Livewire\Forms;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;

class UserForm extends Component
{
    use WithFileUploads;
    public $user_id;

    public $username, $email, $name, $phone, $avatar, $status, $role, $password;
    public $password_confirmation;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|min:4|max:255|alpha_dash|unique:users,username,' . $this->user_id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user_id,
            'phone' => 'required|regex:/^\d{10,13}$/|unique:users,phone,' . $this->user_id,
            'role' => 'required|string|in:admin,manager,cashier',
            'status' => 'nullable|boolean',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => $this->user_id ? 'nullable|confirmed|min:6' : 'required|confirmed|min:6',
        ];
    }

    public $showImg;

    public function mount(User $user)
    {

        if ($user->uuid) {
            $uid = $user->uuid;
            if (!in_array(auth('web')->user()->role, ['admin', 'manager']) && ($uid != auth('web')->user()->uuid)) {
                abort(404);
            }
            $this->name = $user->name;
            $this->email = $user->email;
            $this->username = $user->username;
            $this->status = $user->status == 1 ? true : false;
            $this->showImg = $user->avatar;
            $this->phone = $user->phone;
            $this->role = $user->role;

            $this->user_id = $user->id;
        }
    }


    public function save()
    {
        $this->validate();

        $user = User::find($this->user_id);
        $filePath = $user ? $user->avatar : null;

        // Handle file upload if a new image is selected
        if ($this->avatar) {
            if ($filePath) {
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            $filePath = uploadFile($this->avatar, 'avatars');
        }


        User::updateOrCreate(
            ['id' => $this->user_id_id],
            [
                'name' => $this->name,
                'status' => $this->status,
                'email' => $this->email,
                'role' => $this->role,
                'phone' => $this->phone,
                'username' => $this->username,
                'avatar' => $filePath,
            ]
        );


        toastr()->success($this->user_id ? 'User updated successfully!' : 'User created successfully!');
        $this->reset();
        return redirect()->route('users');
    }



    public function saveUser()
    {

        $this->validate();

        $user = User::find($this->user_id);
        $filePath = $user ? $user->avatar : null;

        // Handle file upload if a new image is selected
        if ($this->avatar) {
            if ($filePath) {
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            $filePath = uploadFile($this->avatar, 'avatars');
        }

        // Check if updating or creating
        if ($this->user_id) {
            // Update User
            $user = User::findOrFail($this->user_id);
            $user->update([
                'username' => $this->username,
                'email' => $this->email,
                'phone' => $this->phone,
                'name' => $this->name,
                'role' => $this->role,
                'avatar' => $filePath,
                'status' => $this->status,
            ]);

            // Update Password if Provided
            if ($this->password) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }
        } else {
            // Create New User
            $user = User::create([
                'username' => $this->username,
                'email' => $this->email,
                'phone' => $this->phone,
                'name' => $this->name,
                'role' => $this->role,
                'avatar' => $filePath,
                'status' => $this->status,
                'password' => Hash::make($this->password), // Required on creation
            ]);
        }


        toastr()->success($this->user_id ? 'User updated successfully' : 'User created successfully');

        $this->reset(); // Reset form
        return redirect()->route('users');
    }

    #[Title('Manage User')]
    public function render()
    {
        return view('livewire.forms.user-form');
    }
}
