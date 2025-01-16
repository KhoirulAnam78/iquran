<div>
    <div class="card card-height-100">
        <div class="card-header">
            <h4>Edit Menu</h4>
        </div>
        <div class="card-body">
            <form wire:submit='update'>
                @csrf
                <div class="mb-3">
                    <label for="name">Menu Name</label>
                    <input type="text" class="form-control" wire:model='name'>
                    <x-form.validation.error name="name" />
                </div>

                <div class="mb-3">
                    <livewire:components.select-search :query="'Spatie\Permission\Models\Permission'" :wire_model="'permission_name'" :label="'Permission Name'"
                        :colSearch="'name'" :colValue="'name'" :selected="$permission_name" />
                    <x-form.validation.error name="permission_name" />
                </div>

                <div class="mb-3">
                    <label for="position">Position</label>
                    <input type="number" class="form-control" wire:model='position'>
                    <x-form.validation.error name="position" />
                </div>

                <label for="focus-ring" class="form-label">Is child menu (Yes/No)</label>
                <div class="mb-3 mx-2">
                    <div class="form-check form-switch form-switch-right form-switch-md">
                        <input class="form-check-input code-switcher" type="checkbox" value="1"
                            wire:model.live='parent_menu' id="focus-ring">
                    </div>
                    <br>
                    <x-form.validation.error name="parent_menu" />
                </div>

                @if ($parent_menu === true)
                    <div class="mb-3">
                        <livewire:components.select-search :query="'App\Models\Menu'" :wire_model="'parent_id'" :label="'Parent Menu'"
                            :colSearch="'name'" :colValue="'id'" :selected="$parent_id" />
                        <x-form.validation.error name="parent_id" />
                    </div>
                    <div class="mb-3">
                        <label for="route" class="form-label">Route</label>
                        <select class="form-control" id="route" name="route" wire:model='route'>
                            @foreach ($routes as $route)
                                @if (!blank($route->getName()))
                                    <option value="{{ $route->getName() }}">{{ $route->getName() }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-form.validation.error name="route" />
                    </div>
                @else
                    <div class="mb-3">
                        <label for="icon">Menu Icon (use remixicon)</label>
                        <input type="text" class="form-control" wire:model='icon' placeholder="ri-arrow-left-s-line">
                        <x-form.validation.error name="icon" />
                    </div>
                @endif

                <div class="mb-3">
                    <label for="descriptions" class="form-label">Description</label>
                    <textarea type="text" wire:model='descriptions' class="form-control" id="descriptions"
                        placeholder="Permission descriptions" name="descriptions"></textarea>
                    <x-form.validation.error name="descriptions" />
                </div>

                <label for="focus-ring" class="form-label">Status (Show/Hide)</label>
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
