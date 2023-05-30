@extends("layouts.app")

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Profil Toko</h4>
        </div>
    </div>
</div>
<!-- end page title -->


<div class="row">
    <div class="col-sm-12">
        <!-- Profile -->
        <div class="card bg-primary">
            <div class="card-body profile-user-box">

                <div class="row">
                    <div class="col-sm-8">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-lg">
                                    <img src="{{asset('image/website/'.$website->icon)}}" alt="" class="rounded-circle img-thumbnail">
                                </div>
                            </div>
                            <div class="col">
                                <div>
                                    <h4 class="mt-1 mb-1 text-white">{{$website->nama_website}}</h4>
                                    <p class="font-13 text-white-50"> {{$website->tagline}}</p>

                                    <!-- <ul class="mb-0 list-inline text-light">
                                        <li class="list-inline-item me-3">
                                            <h5 class="mb-1">$ 25,184</h5>
                                            <p class="mb-0 font-13 text-white-50">Total Revenue</p>
                                        </li>
                                        <li class="list-inline-item">
                                            <h5 class="mb-1">5482</h5>
                                            <p class="mb-0 font-13 text-white-50">Number of Orders</p>
                                        </li>
                                    </ul> -->
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->

                    <div class="col-sm-4">
                        @if(auth()->user())
                        @if(auth()->user()->can('toko') or auth()->user()->can('toko-edit'))
                        <div class="text-center mt-sm-0 mt-3 text-sm-end">
                            <a href="{{url('toko/ubah/'.$website->username)}}" class="btn btn-light">
                                <i class="mdi mdi-account-edit me-1"></i> Edit Profil Toko
                            </a>
                        </div>
                        @endif
                        @endif
                    </div> <!-- end col-->
                </div> <!-- end row -->

            </div> <!-- end card-body/ profile-user-box-->
        </div><!--end profile/ card -->
    </div> <!-- end col-->
</div>
<!-- end row -->


<div class="row">
    <div class="col-xl-4">
        <!-- Personal-Information -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Informasi Toko</h4>
                <div class="text-muted font-13">
                    {!!$website->description!!}
                </div>

                <hr />

                <div class="text-start">
                    <p class="text-muted"><strong>Nama Toko :</strong> <span class="ms-2">{{$website->nama_website}}</span></p>
                    <p class="text-muted"><strong>Pemilik :</strong> <span class="ms-2">{{$website->nama_atasan}}</span></p>
                    <p class="text-muted"><strong>Mobile :</strong><span class="ms-2">{{$website->contact}}</span></p>
                    <p class="text-muted"><strong>Email :</strong> <span class="ms-2">{{$website->email??''}}</span></p>
                    <p class="text-muted"><strong>Location :</strong> <span class="ms-2">{!!$website->address!!} {{$website->webkecamatan->name.', '.$website->webkota->name.', '.$website->webprovinsi->name.', '.$website->kode_pos}}</span></p>

                    <p class="text-muted mb-0" id="tooltip-container"><strong>Social Media :</strong>
                        <a class="d-inline-block ms-2 text-muted" data-bs-container="#tooltip-container" data-bs-placement="top" data-bs-toggle="tooltip" href="{{$website->facebook}}" title="Facebook"><i class="mdi mdi-facebook"></i></a>
                        <a class="d-inline-block ms-2 text-muted" data-bs-container="#tooltip-container" data-bs-placement="top" data-bs-toggle="tooltip" href="{{$website->instagram}}" title="Instagram"><i class="mdi mdi-instagram"></i></a>
                        <a class="d-inline-block ms-2 text-muted" data-bs-container="#tooltip-container" data-bs-placement="top" data-bs-toggle="tooltip" href="{{$website->whatsapp}}" title="WhatsApp"><i class="mdi mdi-whatsapp"></i></a>
                    </p>

                </div>
            </div>
        </div>
        <!-- Personal-Information -->




    </div> <!-- end col-->

    <div class="col-xl-8">
    <div class="row">
            <div class="col-sm-6">
                <div class="card tilebox-one">
                    <div class="card-body">
                        <i class="dripicons-basket float-end text-muted"></i>
                        <h6 class="text-muted text-uppercase mt-0">Total Penjualan</h6>
                        <h2 class="m-b-20">{{$jenisCount['penjualan']??0}}</h2>
                        <span class="text-muted">Selesai</span>
                    </div> <!-- end card-body-->
                </div> <!--end card-->
            </div><!-- end col -->

            <div class="col-sm-6">
                <div class="card tilebox-one">
                    <div class="card-body">
                        <i class="dripicons-box float-end text-muted"></i>
                        <h6 class="text-muted text-uppercase mt-0">Total Pendapatan</h6>
                        <h2 class="m-b-20">{{uang($jenis['penjualan']??0)}}</h2>
                        <span class="text-muted">Selesai</span>
                    </div> <!-- end card-body-->
                </div> <!--end card-->
            </div><!-- end col -->

            <!-- <div class="col-sm-4">
                <div class="card tilebox-one">
                    <div class="card-body">
                        <i class="dripicons-jewel float-end text-muted"></i>
                        <h6 class="text-muted text-uppercase mt-0">Product Sold</h6>
                        <h2 class="m-b-20">1,890</h2>
                        <span class="badge bg-primary"> +89% </span> <span class="text-muted">Last year</span>
                    </div> 
                </div> 
            </div> -->

        </div>
        <!-- end row -->

        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Produk Terlaris</small></h4>
            </div>
            <div class="card-body">
                <!-- <a href="" class="btn btn-sm btn-link float-end">Export
                                            <i class="mdi mdi-download ms-1"></i>
                                        </a> -->


                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topSellProduk as $index=>$ts)
                            <tr>
                                <td>
                                    <img width="35px" src="{{url('image/produk/small/'.$ts->produk->gambar_utama)}}" alt="">
                                    <span class="font-14 my-1 fw-normal">{{$ts->produk->kode_produk}}</span><br>
                                    <span class="text-muted font-13">{{$ts->produk->nama_produk}}</span>
                                </td>
                                <td>
                                    <h5 class="font-14 my-1 fw-normal">{{uang($ts->harga)}}</h5>
                                </td>
                                <td>
                                    <h5 class="font-14 my-1 fw-normal">{{$ts->jml}}</h5>
                                </td>
                                <td>
                                    <h5 class="font-14 my-1 fw-normal">{{uang($ts->jml*$ts->harga)}}</h5>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div> <!-- end table-responsive-->
            </div> <!-- end card-body-->
        </div> <!-- end card-->
        <!-- Chart-->
        <!-- <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Penjualan & Pendapatan</h4>
                <div dir="ltr">
                    <div style="height: 260px;" class="chartjs-chart">
                        <canvas id="high-performing-product"></canvas>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- End Chart-->

        




    </div>
    <!-- end col -->

</div>
<!-- end row -->
@endsection