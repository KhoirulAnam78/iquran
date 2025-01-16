<?php

namespace App\Livewire\Permission;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Edit extends Component
{
    public $name, $guard_name, $description;
    public $edit_id;

    public function mount($id)
    {
        $decrypt = Crypt::decryptString($id);
        $this->edit_id = $decrypt;
        $permission = Permission::find($decrypt);
        $this->name = $permission->name;
        $this->guard_name = $permission->guard_name;
        $this->description = $permission->description;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name,' . $this->edit_id,
            'guard_name' => 'required',
        ], [
            'name.required' => 'Permission name is required !',
            'name.unique' => 'Permission name has exist !',
            'guard_name.required' => 'Guard name is required !',
        ]);

        DB::transaction(function () {

            $permission = Permission::find($this->edit_id);

            // update permission
            $permission->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'description' => $this->description,
            ]);

            session()->flash('alert', 'Permission successfully edited !');

            return $this->redirect('/permission', navigate: true);
        });

    }

    public function render()
    {
        return view('livewire.permission.edit')->layout('layouts.dashboard.main');
    }
}
