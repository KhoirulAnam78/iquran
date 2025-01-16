<div>
    <div class="card card-height-100">
        <div class="card-header">
            <h4>Add User</h4>
        </div>
        <div class="card-body">
            <form wire:submit='save'>
                <div class="mb-3">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" wire:model='username'>
                    <x-form.validation.error name="username" />
                </div>

                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" wire:model='name'>
                    <x-form.validation.error name="name" />
                </div>
                <!-- Custom Checkboxes Color -->
                <div class="mb-3">
                    <label>Select Roles</label>
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
                        @foreach ($roles as $item)
                            <div class="col-sm-6 col-md-3" wire:key='{{ $item->id }}'>
                                <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                                    id="role{{ $item->id }}" wire:model='role'>
                                <label class="form-check-label" for="role{{ $item->id }}">
                                    {{ $item->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <x-form.validation.error name="role" />
                </div>
                <div class="my-3">
                    <button class="btn btn-primary" wire:loading.attr='disabled' type="submit">Save</button>
                </div>
            </form>
        </div>
        <!-- cardheader -->
    </div>

</div>
