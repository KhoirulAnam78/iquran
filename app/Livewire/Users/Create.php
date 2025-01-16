<?php
namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Create extends Component
{

    public $username, $name, $role = [];
    public $selectAll, $roles;

    public function save()
    {
        $this->validate([
            'username' => 'required|unique:users,username',
            'name'     => 'required',
            'role'     => 'required',
        ], [
            'name.required'   => 'Name is required !',
            'username.unique' => 'User has exist !',
            'role.required'   => 'Role is required !',
        ]);

        DB::transaction(function () {
            // create role

            $user = User::create([
                'name'     => $this->name,
                'username' => $this->username,
                'password' => bcrypt($this->username),
            ]);

            // give roles
            $role = array_map('intval', $this->role);
            $user->assignRole($role);

            session()->flash('alert', 'New user successfully added !');

            return $this->redirect('/user', navigate: true);
        });

    }

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            foreach ($this->roles as $item) {
                array_push($this->role, $item->id);
            }
        } else {
            $this->role = [];
        }
    }

    public function render()
    {
        $roles = $this->roles;
        return view('livewire.users.create', compact('roles'))->layout('layouts.dashboard.main');
    }
}
