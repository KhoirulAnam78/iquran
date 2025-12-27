<?php
namespace App\Livewire\ModelVersion;

use Log;
use Livewire\Component;
use App\Models\ModelVersion;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{

    public $version, $model_name, $descriptions, $type, $file,$status;

    use WithFileUploads;
    public function save()
    {
        $this->validate([
            'version' => 'required',
            'model_name' => 'required',
            'descriptions' => 'required',
            'type' => 'required',
            'file' => 'required|file|max:20480',
            'status' => 'required'
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
                    if($this->type == 'model'){
                        $path = $this->file->storeAs('uploads', 'model'.date('ymdhis').'.tflite', 'public');
                    }else{
                        $path = $this->file->store('uploads', 'public');
                    }
                    
                    Log::info('Store result:', [
                        'path' => $path,
                        'full_disk_path' => Storage::disk('public')->path($path),
                        'file_exists' => Storage::disk('public')->exists($path) ? 'YES' : 'NO'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Store error: ' . $e->getMessage());
                }
            }

            $konten = ModelVersion::create([
                'version' => $this->version,
                'model_name' => $this->model_name,
                'path' => $path,
                'type' => $this->type,
                'status' => $this->status,
                'descriptions' => $this->descriptions
            ]);

            session()->flash('alert', 'New Model Content successfully added !');

            return $this->redirect('/modelversion', navigate: true);
        });

    }

    public function render()
    {
        return view('livewire.model-version.create')->layout('layouts.dashboard.main');
    }
}
