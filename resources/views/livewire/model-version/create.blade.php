<div>
    <div class="card card-height-100">
        <div class="card-header">
            <h4>Add Model</h4>
        </div>
        <div class="card-body">
            <form wire:submit='save'>
                <div class="mb-3">
                    <label for="model_name">Model Name</label>
                    <input type="text" class="form-control" wire:model='model_name'>
                    <x-form.validation.error name="model_name" />
                </div>

                <div class="mb-3">
                    <label for="version">Version</label>
                    <input type="text" class="form-control" wire:model='version'>
                    <x-form.validation.error name="version" />
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-control" id="type" name="type" wire:model='type'>
                        <option value="">Pilih</option>
                        <option value="label">label</option>
                        <option value="model">model</option>
                        <option value="other">other</option>
                    </select>
                    <x-form.validation.error name="type" />
                </div>

                <div class="mb-3">
                    <label for="descriptions">Descriptions</label>
                    <textarea class="form-control" wire:model='descriptions'></textarea>
                    <x-form.validation.error name="descriptions" />
                </div>


                <div class="mb-3">
                    <label for="file">File <span class="text-danger" style="font-size:10px"></span></label>
                    <input type="file" class="form-control" wire:model='file'>
                    <x-form.validation.error name="file" />
                    <span wire:loading wire:target='file' class="text-primary">Mengunggah....</span>
                </div>

                <label for="focus-ring" class="form-label">Status (Active/Non Active)</label>
                <div class="mb-3 mx-2">
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <input class="form-check-input code-switcher" type="checkbox" value="1" wire:model='status'
                            id="focus-ring">
                    </div>
                    <br>
                    <x-form.validation.error name="status" />
                </div>

                <div class="my-3">
                    <button class="btn btn-primary" wire:loading.attr='disabled' type="submit">Save</button>
                </div>
            </form>
        </div>
        <!-- cardheader -->
    </div>

</div>
