<?php
namespace App\Livewire\ModelVersion;

use App\Models\ModelVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use WithFileUploads;

    public $version, $model_name, $descriptions, $type, $file, $old_path, $edit_id,$status;

    public function mount($id)
    {
        $decrypt = Crypt::decryptString($id);
        $this->edit_id = $decrypt;
        $data = ModelVersion::find($decrypt);
        $this->version = $data->version;
        $this->model_name = $data->model_name;
        $this->descriptions = $data->descriptions;
        $this->old_path = $data->path;
        $this->type = $data->type;
        $this->status = $data->status == 1 ? true : false;
    }

    public function update()
    {
        $this->validate([
            'version' => 'required',
            'model_name' => 'required',
            'file' => 'nullable|file|max:20480',
            'type' => 'required',
            'status' => 'required'
        ]);

        DB::transaction(function () {

            $path = $this->old_path;
            if ($this->file) {
                if ($this->old_path) {
                    Storage::disk('public')->delete($this->old_path);
                }
                if($this->type == 'model'){
                    $path = $this->file->storeAs('uploads', date('ymdhis').'.tflite', 'public');
                }else{
                    $path = $this->file->store('uploads', 'public');
                }
            }
            // create permission
            $konten = ModelVersion::where('id_model_version', $this->edit_id)->update([
                'version' => $this->version,
                'model_name' => $this->model_name,
                'path' => $path,
                'descriptions' => $this->descriptions,
                'type' => $this->type,
                'status' => $this->status
            ]);

            session()->flash('alert', 'Model Content successfully edited !');

            return $this->redirect('/modelversion', navigate: true);
        });

    }

    public function render()
    {
        return view('livewire.model-version.update')->layout('layouts.dashboard.main');
    }
}
