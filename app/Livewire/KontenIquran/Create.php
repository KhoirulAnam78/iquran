<?php
namespace App\Livewire\KontenIquran;

use Log;
use Livewire\Component;
use App\Models\KontenIquran;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{

    public $nama_key, $nama_konten, $deskripsi, $jenis, $file;

    use WithFileUploads;
    public function save()
    {
        $this->validate([
            'nama_key' => 'required|unique:konten_iquran,nama_key',
            'nama_konten' => 'required',
            'deskripsi' => 'required',
            'jenis' => 'required',
            'file' => 'required|file|max:20480',
        ]);

        DB::transaction(function () {
            // create konten

            // $path = null;
            // if ($this->file) {
            //     $path = $this->file->store('uploads', 'public');
            // }

            // Debug proses penyimpanan
            if ($this->file) {
                Log::info('File detected:', [
                    'original_name' => $this->file->getClientOriginalName(),
                    'size' => $this->file->getSize(),
                    'mime_type' => $this->file->getMimeType()
                ]);

                try {
                    $path = $this->file->store('uploads', 'public');
                    
                    Log::info('Store result:', [
                        'path' => $path,
                        'full_disk_path' => Storage::disk('public')->path($path),
                        'file_exists' => Storage::disk('public')->exists($path) ? 'YES' : 'NO'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Store error: ' . $e->getMessage());
                }
            }

            $konten = KontenIquran::create([
                'nama_key' => $this->nama_key,
                'nama_konten' => $this->nama_konten,
                'path_konten' => $path,
                'jenis' => $this->jenis,
                'deskripsi' => $this->deskripsi,
                'created_by' => auth()->user()->username,
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
