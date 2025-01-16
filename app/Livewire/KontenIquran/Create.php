<?php
namespace App\Livewire\KontenIquran;

use App\Models\KontenIquran;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    public $nama_key, $nama_konten, $deskripsi, $jenis, $file;

    use WithFileUploads;
    public function save()
    {
        $this->validate([
            'nama_key'    => 'required|unique:konten_iquran,nama_key',
            'nama_konten' => 'required',
            'deskripsi'   => 'required',
            'jenis'       => 'required',
            'file'        => 'required|file',
        ]);

        DB::transaction(function () {
            // create konten

            $path = null;
            if ($this->file) {
                $path = $this->file->store('uploads');
            }

            $konten = KontenIquran::create([
                'nama_key'    => $this->nama_key,
                'nama_konten' => $this->nama_konten,
                'path_konten' => $path,
                'jenis'       => $this->jenis,
                'deskripsi'   => $this->deskripsi,
                'created_by'  => auth()->user()->username,
            ]);

            session()->flash('alert', 'New content successfully added !');

            return $this->redirect('/konten', navigate: true);
        });

    }

    public function render()
    {
        return view('livewire.konten-iquran.create')->layout('layouts.dashboard.main');
    }
}
