<?php
namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Edit extends Component
{

    public $username, $name, $role = [];
    public $selectAll, $roles, $edit_id;

    public function mount($id)
    {
        $decrypt        = Crypt::decryptString($id);
        $user           = User::with('roles')->find($decrypt);
        $this->username = $user->username;
        $this->name     = $user->name;
        foreach ($user->roles as $role) {
            $this->role[] = (int) $role->id;
        }

        $this->edit_id = $decrypt;

        $this->roles = Role::orderBy('name')->get();
    }
    public function update()
    {
        $this->validate([
            'username' => 'required|unique:users,username,' . $this->edit_id,
            'role'     => 'required',
            'name'     => 'required',
        ], [
            'name.required' => 'User is required !',
            'role.required' => 'Role is required !',
        ]);

        DB::transaction(function () {
            // create role

            $user = User::where('id', $this->edit_id)->update([
                'username' => $this->username,
                'name'     => $this->name,
            ]);

            // give roles
            $user = User::find($this->edit_id);

            $role = array_map('intval', $this->role);

            $user->syncRoles($role);

            session()->flash('alert', 'user successfully edited !');

            return $this->redirect('/user', navigate: true);
        });

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
        return view('livewire.users.edit', compact('roles'))->layout('layouts.dashboard.main');
    }
}
