<?php
namespace App\Livewire\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Edit extends Component
{

    public $name, $permission_name, $status = false, $icon, $position, $parent_menu = 'false', $route, $descriptions, $parent_id;
    public $edit_id;
    public function mount($id)
    {
        $decrypt               = Crypt::decryptString($id);
        $this->edit_id         = $decrypt;
        $menu                  = Menu::find($decrypt);
        $this->name            = $menu->name;
        $this->permission_name = $menu->permission;
        $this->status          = $menu->status == 1 ? true : false;
        $this->icon            = $menu->icon;
        $this->position        = $menu->position;
        $this->parent_menu     = $menu->parent_menu == 1 ? true : false;
        $this->route           = $menu->route;
        $this->descriptions    = $menu->descriptions;
        $this->parent_id       = $menu->parent_menu_id;
    }

    public function update()
    {
        $validation = [
            'name'            => 'required|unique:menus,name,' . $this->edit_id,
            'permission_name' => 'required',
            'position'        => 'required|numeric',
            'status'          => 'required',
            'parent_menu'     => 'required',
        ];

        if ($this->parent_menu === true) {
            $validation['route']     = 'required';
            $validation['parent_id'] = 'required';
            $this->icon              = null;
        } else {
            $validation['icon'] = 'required';
            $this->route        = null;
            $this->parent_id    = null;
        }

        $messages = [
            'name.required'            => 'Menu name is required !',
            'name.unique'              => 'Menu name has exist !',
            'permission_name.required' => 'Permission name is required !',
            'position.required'        => 'Position is required !',
            'status.required'          => 'Status is required',
            'parent_menu.required'     => 'Menu haa childs ?',
            'route.required'           => 'Route is required !',
            'parent_id.required'       => 'Parent Menu is required !',
        ];

        $this->validate($validation, $messages);

        DB::transaction(function () {

            $parent = ($this->parent_menu === 'true' || $this->parent_menu === true) ? 1 : 0;

            $menu = Menu::where('position', '>=', $this->position)->where('parent_menu', $parent)->orderBy('position', 'DESC')->get();

            if ($menu) {
                foreach ($menu as $m) {
                    $m->update([
                        'position' => $m->position + 1,
                    ]);
                }
            }

            // create menu
            $menu = Menu::where('id', $this->edit_id)->update([
                'name'           => $this->name,
                'permission'     => $this->permission_name,
                'status'         => ($this->status === 'true' || $this->status === true) ? 1 : 0,
                'position'       => $this->position,
                'parent_menu'    => $parent,
                'icon'           => $this->icon,
                'route'          => $this->route,
                'parent_menu_id' => $this->parent_id,
                'descriptions'   => $this->descriptions,
            ]);

            session()->flash('alert', 'Menu successfully edited !');

            return $this->redirect('/menu', navigate: true);
        });

    }

    #[On('select-value')]
    public function postAdded($selected)
    {
        $model_name        = $selected['model'];
        $value             = $selected['value'];
        $this->$model_name = $value;
    }

    public function render()
    {
        $routes      = Route::getRoutes();
        $permissions = Permission::orderBy('name')->get();
        return view('livewire.menu.edit', compact('permissions', 'routes'))->layout('layouts.dashboard.main');
    }
}
