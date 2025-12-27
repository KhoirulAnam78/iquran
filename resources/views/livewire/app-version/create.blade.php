<div>
    <div class="card card-height-100">
        <div class="card-header">
            <h4>Add Version</h4>
        </div>
        <div class="card-body">
            <form wire:submit='save'>
                <div class="mb-3">
                    <label for="version">Version</label>
                    <input type="text" class="form-control" wire:model='version'>
                    <x-form.validation.error name="version" />
                </div>

                <div class="mb-3">
                    <label for="release_note">Release Note</label>
                    <textarea class="form-control" wire:model='release_note' rows="10"></textarea>
                    <x-form.validation.error name="release_note" />
                </div>

                <div class="mb-3">
                    <label for="download_url">Download Url</label>
                    <input type="text" class="form-control" wire:model='download_url'>
                    <x-form.validation.error name="download_url" />
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
