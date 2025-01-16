<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Home</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>

            </div>
            <div class="page-body">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('login') }}" class="btn btn-primary my-3">Login</a>
                        <br> <br>
                        <p>Silahkan login terlebih dahulu, <br>
                            untuk mengelola konten dapat mengakses Menu Master Data -> Konten Iquran </p>
                        <br>
                        <h2>Dokumentasi API</h2>
                        1. Get Konten
                        <ul>
                            <li>Method : GET</li>
                            <li>URL : {{ url('/api/get-content/{nama_key}') }}</li>
                        </ul>
                        <span class="text-danger">NOTE : nama_key diperoleh dari Data Master -> Konten Iquran</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
</div>
