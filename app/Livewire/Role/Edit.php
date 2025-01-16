<?php

namespace App\Livewire\Role;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    public $name, $guard_name, $description, $permission = [];
    public $selectAll, $permissions, $edit_id, $role;

    public function mount($id)
    {
        $this->permissions = Permission::orderBy('name')->get();
        $decrypt = Crypt::decryptString($id);
        $this->edit_id = $decrypt;
        $role = Role::find($decrypt);
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->description = $role->description;
        foreach ($role->permissions as $p) {
            array_push($this->permission, $p->id);
        }
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            foreach ($this->permissions as $item) {
                array_push($this->permission, $item->id);
            }
        } else {
            $this->permission = [];
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->edit_id,
            'guard_name' => 'required',
            'permission' => 'required',
        ], [
            'name.required' => 'Role name is required !',
            'name.unique' => 'Role name has exist !',
            'guard_name.required' => 'Guard name is required !',
            'permission.required' => 'Permission is required !',
        ]);

        DB::transaction(function () {

            $role = Role::find($this->edit_id);

            // update role
            $role->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'descriptions' => $this->description,
            ]);

            // sync permissions
            $permission = array_map('intval', $this->permission);
            $role->syncPermissions($permission);

            session()->flash('alert', 'Role successfully edited !');

            return $this->redirect('/role', navigate: true);
        });

    }

    public function render()
    {
        return view('livewire.role.edit')->layout('layouts.dashboard.main');
    }
}
