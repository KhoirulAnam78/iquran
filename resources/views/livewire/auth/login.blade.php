<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Login</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                        <li class="breadcrumb-item active">Login</li>
                    </ol>
                </div>

            </div>
            <div class="page-body">
                <div class="card">

                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5 class="text-primary">Login</h5>
                            {{-- <p class="text-muted">Sorry guys login cuma aku yang bisa hoho.</p> --}}
                        </div>
                        <div class="p-2 mt-4">
                            <form wire:submit='authenticate()'>
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username"
                                        placeholder="Enter username" required wire:model='username'>
                                    @error('username')
                                        <div class="div">
                                            <span class="text-danger">Hayoooo mau ngapain sih</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password-input">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="position-relative auth-pass-inputgroup">
                                        <input type="password" class="form-control pe-5 password-input"
                                            placeholder="Enter password" id="password-input"
                                            aria-describedby="passwordInput" wire:model='password'>
                                        <button
                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                            type="button" id="password-addon"><i
                                                class="ri-eye-fill align-middle"></i></button>
                                    </div>
                                </div>


                                <div class="mt-4">
                                    <button class="btn btn-primary w-100" type="submit"
                                        wire:click='authenticate()'>Masuk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
</div>
