<?php
namespace App\Livewire\AppVersion;

use App\Models\AppVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use WithFileUploads;

    public $version, $download_url, $release_note, $status, $edit_id;

    public function mount($id)
    {
        $decrypt = Crypt::decryptString($id);
        $this->edit_id = $decrypt;
        $data = AppVersion::find($decrypt);
        $this->version = $data->version;
        $this->download_url = $data->download_url;
        $this->release_note = $data->release_note;
        $this->status = $data->status == 1 ? true : false;
    }

    public function update()
    {
        $this->validate([
            'version' => 'required|unique:app_version,version,' . $this->edit_id . ',id_app_version',
            'download_url' => 'required',
            'release_note' => 'required',
            'status' => 'required',
        ]);

        DB::transaction(function () {
            // create permission
            $konten = AppVersion::where('id_app_version', $this->edit_id)->update([
                'version' => $this->version,
                'download_url' => $this->download_url,
                'status' => $this->status,
                'release_note' => $this->release_note,
            ]);

            session()->flash('alert', 'Version successfully edited !');

            return $this->redirect('/appversion', navigate: true);
        });

    }

    public function render()
    {
        return view('livewire.app-version.update')->layout('layouts.dashboard.main');
    }
}
