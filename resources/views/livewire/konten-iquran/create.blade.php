<div>
    <div class="card card-height-100">
        <div class="card-header">
            <h4>Add Content</h4>
        </div>
        <div class="card-body">
            <form wire:submit='save'>
                <div class="mb-3">
                    <label for="nama_konten">Nama Konten</label>
                    <input type="text" class="form-control" wire:model='nama_konten'>
                    <x-form.validation.error name="nama_konten" />
                </div>

                <div class="mb-3">
                    <label for="nama_key">Nama Key (sebagai parameter yang digunakan oleh api untuk get konten)</label>
                    <input type="text" class="form-control" wire:model='nama_key'>
                    <x-form.validation.error name="nama_key" />
                </div>
                <div class="mb-3">
                    <label for="jenis" class="form-label">Jenis</label>
                    <select class="form-control" id="jenis" name="jenis" wire:model='jenis'>
                        <option value="">Pilih</option>
                        <option value="video">Video</option>
                        <option value="file">File</option>
                    </select>
                    <x-form.validation.error name="jenis" />
                </div>

                <div class="mb-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" wire:model='deskripsi'></textarea>
                    <x-form.validation.error name="deskripsi" />
                </div>


                <div class="mb-3">
                    <label for="file">File <span class="text-danger" style="font-size:10px"></span></label>
                    <input type="file" class="form-control" wire:model='file'>
                    <x-form.validation.error name="file" />
                    <span wire:loading wire:target='file' class="text-primary">Mengunggah....</span>
                </div>


                <div class="my-3">
                    <button class="btn btn-primary" wire:loading.attr='disabled' type="submit">Save</button>
                </div>
            </form>
        </div>
        <!-- cardheader -->
    </div>

</div>
