<?php
namespace App\Livewire\KontenIquran;

use App\Models\KontenIquran;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Konten Iquran Management')]

class Index extends Component
{
    public $search  = '';
    public $perpage = 10;
    use WithPagination;
    protected $paginationTheme  = 'bootstrap';
    public $delete_id, $orderBy = 'nama_konten', $direction = 'ASC';

    public function render()
    {
        $konten = KontenIquran::query()
            ->when(! blank($this->search), function ($query) {
                return $query
                    ->where('nama_konten', 'like', '%' . $this->search . '%')
                    ->orWhere('nama_key', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->orderBy, $this->direction)
            ->paginate($this->perpage);
        return view('livewire.konten-iquran.index', compact('konten'))->layout('layouts.dashboard.main');
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

        $data = KontenIquran::find($decrypt);
        session()->flash('delete-confirm', 'Apakah anda yakin menghapus data "' . $data->nama_konten . '" ?');
    }

    public function delete()
    {
        DB::transaction(function () {
            $data = KontenIquran::find($this->delete_id);
            Storage::delete($data->path_konten);
            $data->delete();
            session()->flash('alert', 'KontenIquran successfully deleted !');
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
