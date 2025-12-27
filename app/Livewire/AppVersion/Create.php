<?php
namespace App\Livewire\AppVersion;

use Log;
use Livewire\Component;
use App\Models\AppVersion;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{

    public $version, $download_url, $release_note, $status;

    use WithFileUploads;
    public function save()
    {
        $this->validate([
            'version' => 'required|unique:app_version,version',
            'download_url' => 'required',
            'release_note' => 'required',
            'status' => 'required'
        ]);

        DB::transaction(function () {
            // create konten
            $konten = AppVersion::create([
                'version' => $this->version,
                'download_url' => $this->download_url,
                'status' => $this->status,
                'release_note' => $this->release_note,
            ]);

            session()->flash('alert', 'New version successfully added !');

            return $this->redirect('/appversion', navigate: true);
        });

    }

    public function render()
    {
        return view('livewire.app-version.create')->layout('layouts.dashboard.main');
    }
}
