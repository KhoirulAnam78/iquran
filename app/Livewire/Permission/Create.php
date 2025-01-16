<?php

namespace App\Livewire\Permission;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Create extends Component
{

    public $name, $guard_name, $description;

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name',
            'guard_name' => 'required',
        ], [
            'name.required' => 'Permission name is required !',
            'name.unique' => 'Permission name has exist !',
            'guard_name.required' => 'Guard name is required !',
        ]);

        DB::transaction(function () {
            // create permission
            $permission = Permission::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'description' => $this->description,
            ]);

            session()->flash('alert', 'New permission successfully added !');

            return $this->redirect('/permission', navigate: true);
        });

    }

    public function render()
    {
        return view('livewire.permission.create')->layout('layouts.dashboard.main');
    }
}