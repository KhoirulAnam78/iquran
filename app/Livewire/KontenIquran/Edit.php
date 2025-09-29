<?php
namespace App\Livewire\KontenIquran;

use App\Models\KontenIquran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $nama_key, $nama_konten, $deskripsi, $jenis, $file, $old_path, $edit_id;

    public function mount($id)
    {
        $decrypt = Crypt::decryptString($id);
        $this->edit_id = $decrypt;
        $data = KontenIquran::find($decrypt);
        $this->nama_key = $data->nama_key;
        $this->nama_konten = $data->nama_konten;
        $this->deskripsi = $data->deskripsi;
        $this->old_path = $data->path_konten;
        $this->jenis = $data->jenis;
    }

    public function update()
    {
        $this->validate([
            'nama_key' => 'required|unique:konten_iquran,nama_key,' . $this->edit_id . ',id_konten',
            'nama_konten' => 'required',
            'file' => 'nullable|file|max:20480',
            'jenis' => 'required',
        ]);

        DB::transaction(function () {

            $path = $this->old_path;
            if ($this->file) {
                if ($this->old_path) {
                    Storage::disk('public')->delete($this->old_path);
                }
                $path = $this->file->store('uploads','public');
            }
            // create permission
            $konten = KontenIquran::where('id_konten', $this->edit_id)->update([
                'nama_key' => $this->nama_key,
                'nama_konten' => $this->nama_konten,
                'updated_by' => auth()->user()->name,
                'path_konten' => $path,
                'deskripsi' => $this->deskripsi,
            ]);

            session()->flash('alert', 'Content successfully edited !');

            return $this->redirect('/konten', navigate: true);
        });

    }

    public function render()
    {
        return view('livewire.konten-iquran.edit')->layout('layouts.dashboard.main');
    }
}
