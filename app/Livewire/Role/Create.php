<?php

namespace App\Livewire\Role;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Create extends Component
{

    public $name, $guard_name, $description, $permission = [];
    public $selectAll, $permissions;

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name',
            'guard_name' => 'required',
            'permission' => 'required',
        ], [
            'name.required' => 'Role name is required !',
            'name.unique' => 'Role name has exist !',
            'guard_name.required' => 'Guard name is required !',
            'permission.required' => 'Permission is required !',
        ]);

        DB::transaction(function () {
            // create role
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'descriptions' => $this->description,
            ]);

            // give permissions
            $permission = array_map('intval', $this->permission);
            $role->syncPermissions($permission);

            session()->flash('alert', 'New role successfully added !');

            return $this->redirect('/role', navigate: true);
        });

    }

    public function mount()
    {
        $this->permissions = Permission::orderBy('name')->get();
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

    public function render()
    {
        $permissions = $this->permissions;
        return view('livewire.role.create', compact('permissions'))->layout('layouts.dashboard.main');
    }
}
