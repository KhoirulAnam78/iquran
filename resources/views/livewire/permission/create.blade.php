<div>
    <div class="card card-height-100">
        <div class="card-header">
            <h4>Add Permission</h4>
        </div>
        <div class="card-body">
            <form wire:submit='save'>
                <div class="mb-3">
                    <label for="name">Permission Name</label>
                    <input type="text" class="form-control" wire:model='name'>
                    <x-form.validation.error name="name" />
                </div>
                <div class="mb-3">
                    <label for="guard_name">Guard Name</label>
                    <input type="text" class="form-control" wire:model='guard_name'>
                    <x-form.validation.error name="guard_name" />
                </div>

                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" wire:model='description'></textarea>
                    <x-form.validation.error name="description" />
                </div>
                <div class="my-3">
                    <button class="btn btn-primary" wire:loading.attr='disabled' type="submit">Save</button>
                </div>
            </form>
        </div>
        <!-- cardheader -->
    </div>

</div>
