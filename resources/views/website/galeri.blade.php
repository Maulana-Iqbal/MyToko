@extends("layouts.app")

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Galeri</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                <div class="container">
                    <h2>Upload Gambar</h2>
                    @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                    @endif
                    @if(session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                    @endif
                    @if(count($errors) > 0 )
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="p-0 m-0" style="list-style: none;">
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="post" action="/upload-gambar" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" name="file[]" multiple class="form-control" required accept="image/*">
                            @if ($errors->has('files'))
                            @foreach ($errors->get('files') as $error)
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $error }}</strong>
                            </span>
                            @endforeach
                            @endif
                        </div>

                        <div class="form-group float-end">
                            <button type="submit" class="btn btn-success mt-2">Upload</button>
                            <button type="reset" class="btn btn-danger mt-2">Batal</button>
                        </div>
                    </form>
                </div>

                <!-- Page Content -->
                <div class="container" style="margin-top: 80px;">

                    <h3>Galeri Gambar</h3>

                    <hr>
                    <form action="/aksi-galeri" method="post">
                        @csrf
                        <div class="dropdown  mb-2">
                            <a href="#" class="dropdown-toggle arrow-none btn btn-info" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="uil uil-ellipsis-h"></i> Aksi Pilihan
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <input type="submit" name="homeSlider" value="Home Slider" class="dropdown-item">

                                <input type="submit" name="produkSlider" value="Produk Slider" class="dropdown-item">

                                <div class="dropdown-divider"></div>
                                <input type="submit" name="hapus" value="Hapus" class="dropdown-item text-danger">


                            </div> <!-- end dropdown menu-->
                        </div>
                        <div class="row text-center text-lg-start">
                            @foreach($galeri as $galeri)

                            <div class="col-lg-3 col-md-4 col-6 mb-2">
                                <input type="checkbox" name="select[]" id="select" value="{{$galeri->id}}">
                                <?php
                                    if($galeri->jenis=='homeSlider'){
                                        echo "Home Slider";
                                    }elseif($galeri->jenis=='produkSlider'){
                                        echo "Produk Slider";
                                    }elseif($galeri->jenis=='galeri'){
                                        echo "Galeri";
                                    }elseif($galeri->jenis=='produk'){
                                        echo "Produk";
                                    }
                                ?>
                                <a href="#" class="">
                                    <img class="img-fluid img-thumbnail" src="/galeriImage/{{$galeri->gambar}}" alt="">
                                </a>

                            </div>
                            @endforeach


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection