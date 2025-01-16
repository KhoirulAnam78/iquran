<?php

namespace App\Livewire\Role;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Title('Role Management')]

class Index extends Component
{
    public $search = '';
    public $perpage = 10;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $delete_id, $orderBy = 'name', $direction = 'ASC';

    public function render()
    {
        $roles = Role::query()
            ->when(!blank($this->search), function ($query) {
                return $query
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('guard_name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->orderBy, $this->direction)
            ->paginate($this->perpage);
        return view('livewire.role.index', compact('roles'))->layout('layouts.dashboard.main');
    }

    public function sortBy($orderBy)
    {
        $this->orderBy = $orderBy;
        if ($this->direction == 'ASC') {
            $this->direction = 'DESC';
        } else {
            $this->direction = 'ASC';
        }
    }

    public function showDelete($id)
    {
        $decrypt = Crypt::decryptString($id);
        $this->delete_id = $decrypt;

        $data = Role::find($decrypt);
        session()->flash('delete-confirm', 'Apakah anda yakin menghapus data "' . $data->name . '" ?');
    }

    public function delete()
    {
        DB::transaction(function () {
            $data = Role::find($this->delete_id);
            $data->delete();
            session()->flash('alert', 'Role successfully deleted !');
        });
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
