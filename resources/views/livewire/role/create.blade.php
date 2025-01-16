<div>
    <div class="card card-height-100">
        <div class="card-header">
            <h4>Add Role</h4>
        </div>
        <div class="card-body">
            <form wire:submit='save'>
                <div class="mb-3">
                    <label for="name">Role Name</label>
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

                <!-- Custom Checkboxes Color -->
                <div class="mb-3">
                    <label>Select Permissions</label>
                </div>
                <div class="form-check">
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <input class="form-check-input" type="checkbox" id="selectAll" value="all"
                                wire:model.live='selectAll'>
                            <label class="form-check-label" for="selectAll">
                                Select All
                            </label>
                        </div>
                        @foreach ($permissions as $item)
                            <div class="col-sm-6 col-md-3" wire:key='{{ $item->id }}'>
                                <input class="form-check-input" type="checkbox" value="{{ $item->name }}"
                                    id="permission{{ $item->id }}" wire:model='permission'>
                                <label class="form-check-label" for="permission{{ $item->id }}">
                                    {{ $item->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <x-form.validation.error name="permission" />
                </div>
                <div class="my-3">
                    <button class="btn btn-primary" wire:loading.attr='disabled' type="submit">Save</button>
                </div>
            </form>
        </div>
        <!-- cardheader -->
    </div>

</div>
