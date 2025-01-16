<div>
    <div class="card card-height-100">
        <!-- cardheader -->
        <div class="card-header border-bottom-dashed align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Konten Iquran</h4>
            <div class="flex-shrink-0">
                <a href="{{ route('konten.create') }}" wire:navigate class="btn btn-soft-primary btn-sm">
                    <i class="ri-add-line"></i>
                    Add
                </a>
            </div>
        </div>
        <div class="card-body mx-1">
            @if (session('alert'))
                <div class="row">
                    <!-- Primary Alert -->
                    <div class="alert alert-primary alert-dismissible bg-primary text-white alert-label-icon fade show material-shadow"
                        role="alert">
                        <i class="ri-user-smile-line label-icon"></i><strong>Success</strong> - {{ session('alert') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>

                </div>
            @endif
            @if (session('delete-confirm'))
                <div class="row">
                    <!-- Primary Alert -->
                    <div class="alert alert-danger alert-dismissible bg-danger text-white alert-label-icon fade show material-shadow"
                        role="alert">
                        <i class="ri-user-smile-line label-icon"></i><strong>Konfirmasi Delete</strong> -
                        {{ session('delete-confirm') }}
                        <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="alert"
                            wire:click='delete()' aria-label="Close">Hapus</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="alert"
                            aria-label="Close">Batal</button>
                    </div>

                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center" style="margin:0pc;padding:0px">
                <div class="datatables-length mt-2">
                    <label>
                        <select name="example_length" aria-controls="example" class="form-select form-select-sm"
                            wire:model.live='perpage'>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </label>
                </div>
                <div class="col-sm-12 col-md-6">
                    <input type="text" class="form-control" placeholder="Search.."
                        wire:model.live.debounce.500ms="search">
                </div>
            </div>

            <div class="table-responsive table-card mt-2">
                <table class="table table-nowrap table-striped-columns mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Konten <i class="ri-arrow-down-s-fill"></i>
                            </th>
                            <th scope="col">Nama Key <i class="ri-arrow-down-s-fill"></i></th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">File</th>
                            <th scope="col">Info</th>
                            <th scope="col" class="col-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($konten as $k)
                            <tr>
                                <th scope="row">
                                    {{ ($konten->currentpage() - 1) * $konten->perpage() + $loop->index + 1 }}</th>
                                <td>{{ $k->nama_konten }}</td>
                                <td>{{ $k->nama_key }}</td>
                                <td>{{ $k->jenis }}</td>
                                <td>{{ $k->deskripsi ?? '-' }}</td>
                                <td>
                                    <a href="{{ $k->path_konten ? asset('storage/' . $k->path_konten) : '#' }}"
                                        class="link" target="_blank">Lihat</a>
                                </td>
                                <td>
                                    <span class="text-success" style="font-size: 10px">
                                        Dibuat pada : <br> {{ $k->created_at }} oleh {{ $k->created_by }} <br>

                                        Diperbarui pada : <br> {{ $k->updated_at }} oleh {{ $k->updated_by ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('konten.edit', ['id' => Crypt::encryptString($k->id_konten)]) }}"
                                        class="btn btn-primary" wire:navigate><i class="ri-quill-pen-line"></i></a>
                                    <button class="btn btn-danger"
                                        wire:click="showDelete('{{ Crypt::encryptString($k->id_konten) }}')"><i
                                            class="ri-delete-bin-5-line"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="5" class="text-center">No data to display</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer py-4 d-flex">
            <p class="flex-grow-1">Total data : {{ $konten->total() }}</p>
            <nav aria-label="..." class="pagination justify-content-end flex-shrink-0">
                {{ $konten->links() }}
            </nav>
        </div>
    </div>
</div>
