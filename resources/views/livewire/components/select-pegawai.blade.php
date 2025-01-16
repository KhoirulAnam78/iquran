<div>
    <label for="selectpicker">{{ $label }}</label>
    <div class="dropdown py-2" style="width:100%">
        <span class="form-control" id="button_{{ $wire_model }}" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            {{ $selected ?? 'Choose One' }}
            </button>
            <ul class="dropdown-menu" id="dropdown_{{ $wire_model }}" style="width: 100%"
                aria-labelledby="button_{{ $wire_model }}" wire:ignore.self>
                <li><input type="text" role="button" data-bs-toggle="dropdown" autofocus
                        wire:model.live.debounce.500ms='search' class="form-control" placeholder="search..."></li>
                @forelse ($data as $item)
                    <li class="dropdown-item">
                        <div style="width: 100%;" wire:click='selectValue("{{ $item->$colValue }}")'>
                            <span class="{{ $selected == $item->$colValue ? 'fw-bold' : '' }}">
                                {{ $item->namaGelar() }}
                            </span>
                        </div>
                    </li>
                @empty
                    <span class="dropdown-item">No data...</span>
                @endforelse
            </ul>
    </div>
</div>
