<?php
namespace App\Livewire\Appversion;

use App\Models\Appversion;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('App Version Management')]

class Index extends Component
{
    public $search  = '';
    public $perpage = 10;
    use WithPagination;
    protected $paginationTheme  = 'bootstrap';
    public $delete_id, $orderBy = 'version', $direction = 'ASC';

    public function render()
    {
        $konten = Appversion::query()
            ->when(! blank($this->search), function ($query) {
                return $query
                    ->where('version', 'like', '%' . $this->search . '%')
                    ->orWhere('release_note', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->orderBy, $this->direction)
            ->paginate($this->perpage);
        return view('livewire.app-version.index', compact('konten'))->layout('layouts.dashboard.main');
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
        $decrypt         = Crypt::decryptString($id);
        $this->delete_id = $decrypt;

        $data = Appversion::find($decrypt);
        session()->flash('delete-confirm', 'Apakah anda yakin menghapus data "' . $data->version . '" ?');
    }

    public function delete()
    {
        DB::transaction(function () {
            $data = Appversion::find($this->delete_id);
            $data->delete();
            session()->flash('alert', 'Appversion successfully deleted !');
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
