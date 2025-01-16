<?php

namespace App\Livewire\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{

    public $name, $permission_name, $status = false, $icon, $position, $parent_menu = 'false', $route, $descriptions, $parent_id;
    public $search_permission = "";

    public function save()
    {
        $validation = [
            'name' => 'required|unique:menus,name',
            'permission_name' => 'required',
            'position' => 'required|numeric',
            'status' => 'required',
            'parent_menu' => 'required',
        ];

        if ($this->parent_menu === true) {
            $validation['route'] = 'required';
            $validation['parent_id'] = 'required';
        } else {
            $validation['icon'] = 'required';
        }

        $messages = [
            'name.required' => 'Menu name is required !',
            'name.unique' => 'Menu name has exist !',
            'permission_name.required' => 'Permission name is required !',
            'position.required' => 'Position is required !',
            'status.required' => 'Status is required',
            'parent_menu.required' => 'Menu haa childs ?',
            'route.required' => 'Route is required !',
            'parent_id.required' => 'Parent Menu is required !',
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
            $menu = Menu::create([
                'name' => $this->name,
                'permission' => $this->permission_name,
                'status' => ($this->status === 'true' || $this->status === true) ? 1 : 0,
                'position' => $this->position,
                'parent_menu' => $parent,
                'icon' => $this->icon,
                'route' => $this->route,
                'parent_menu_id' => $this->parent_id,
                'descriptions' => $this->descriptions,
            ]);

            session()->flash('alert', 'New menu successfully added !');

            return $this->redirect('/menu', navigate: true);
        });

    }

    #[On('select-value')]
    public function postAdded($selected)
    {
        $model_name = $selected['model'];
        $value = $selected['value'];
        $this->$model_name = $value;
    }

    public function render()
    {
        $routes = Route::getRoutes();
        return view('livewire.menu.create', compact('routes'))->layout('layouts.dashboard.main');
    }
}