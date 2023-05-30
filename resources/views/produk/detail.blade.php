@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <!-- <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">eCommerce</a></li>
                                            <li class="breadcrumb-item active">Product Details</li>
                                        </ol>
                                    </div> -->
            <h4 class="page-title">Detail Barang</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <!-- Product image -->
                        <a href="javascript: void(0);" class="text-center d-block mb-4">
                            <img src="/image/produk/{{ $produk->gambar_utama }}" class="img-fluid" style="max-width: 280px;" alt="{{ $produk->nama_produk }}" />
                        </a>

                        <div class="d-lg-flex d-none justify-content-center">
                            @php $i=1; @endphp
                            @foreach ($galeri as $galeri)
                            <a href="{{ url('/galeriImage/' . $galeri->gambar) }}" @if ($i> 1) class="ms-2" @endif>
                                <img src="/galeriImage/{{ $galeri->gambar }}" class="img-fluid img-thumbnail p-2" style="max-width: 75px;" alt="{{ $produk->nama_produk }}" />
                            </a>
                            @php $i++ @endphp
                            @endforeach
                        </div>
                    </div> <!-- end col -->
                    <div class="col-lg-5 col-sm-6">
                        <div class="ps-lg-4">
                            <!-- Product title -->
                            <h3 class="mt-0">{{ $produk->nama_produk }}</h3>
                            <p class="mb-1">Added Date: {{ tglIndo(date('Y-m-d',strtotime($produk->created_at))) }}</p>
                            <!-- <p class="font-16">
                                                        <span class="text-warning mdi mdi-star"></span>
                                                        <span class="text-warning mdi mdi-star"></span>
                                                        <span class="text-warning mdi mdi-star"></span>
                                                        <span class="text-warning mdi mdi-star"></span>
                                                        <span class="text-warning mdi mdi-star"></span>
                                                    </p> -->

                            <!-- Product stock -->
                            <?php if ($produk->stock->jumlah > 0) { ?>
                                <div class="mt-3">
                                    <h4><span class="badge badge-success-lighten">Instock {{$produk->stock->jumlah.' '.$produk->satuan->name}} </span></h4>
                                </div>
                            <?php } else {
                            ?>
                                <h4><span class="badge badge-danger-lighten">Stock Kosong</span></h4>
                            <?php
                            } ?>
                            <!-- Product description -->
                            <div class="mt-2">
                                <h6 class="font-14">Harga Modal:</h6>
                                <h3 class="font-16 text-primary"> {{uang($produk->stock->harga)}}</h3>
                            </div>
                            <div class="mt-2">
                                <h6 class="font-14">Harga Eceran:</h6>
                                <h3 class="font-16 text-primary"> {{uang($produk->stock->harga_jual)}}</h3>
                            </div>
                            <div class="mt-2">
                                <h6 class="font-14">Harga Grosir:</h6>
                                <h3 class="font-16 text-primary"> {{uang($produk->stock->harga_grosir)}} <br><small class="text-info">Min Order : {{$produk->min_order}} / Max Order : {{$produk->max_order}}</small></h3>
                            </div>

                            <!-- Quantity -->
                            <!-- <div class="mt-4">
                                                        <h6 class="font-14">Quantity</h6>
                                                        <div class="d-flex">
                                                            <input type="number" min="1" value="1" class="form-control" placeholder="Qty" style="width: 90px;">
                                                            <button type="button" class="btn btn-danger ms-2"><i class="mdi mdi-cart me-1"></i> Add to cart</button>
                                                        </div>
                                                    </div> -->

                            <!-- Product description -->
                            <div class="mt-4">
                                <h6 class="font-14">Keterangan:</h6>
                                {!! $produk->keterangan !!}
                            </div>

                            <!-- Product information -->
                            <!-- <div class="mt-4">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <h6 class="font-14">Available Stock:</h6>
                                                                <p class="text-sm lh-150">1784</p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <h6 class="font-14">Number of Orders:</h6>
                                                                <p class="text-sm lh-150">5,458</p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <h6 class="font-14">Revenue:</h6>
                                                                <p class="text-sm lh-150">$8,57,014</p>
                                                            </div>
                                                        </div>
                                                    </div> -->

                        </div>
                    </div> <!-- end col -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="mt-4">
                            <h6 class="font-14">Kode Barang:</h6>
                            <h3 class="font-16 text-info"> {{$produk->kode_produk}}</h3>
                        </div>
                        <div class="mt-2">
                            <h6 class="font-14">Merek:</h6>
                            <h3 class="font-16 text-info"> {{$produk->merek ?? 'Tanpa Merek'}}</h3>
                        </div>
                        <div class="mt-2">
                            <h6 class="font-14">Berat:</h6>
                            <h3 class="font-16 text-info"> {{$produk->berat}} Gram</h3>
                        </div>
                        <div class="mt-2">
                            <h6 class="font-14">Satuan:</h6>
                            <h3 class="font-16 text-info"> {{$produk->satuan->name}}</h3>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <h6 class="font-14">Detail :</h6>
                        {!!$produk->deskripsi!!}
                    </div>
                </div> <!-- end row-->

                <!-- <div class="table-responsive mt-4">
                    <table class="table table-bordered table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Outlets</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ASOS Ridley Outlet - NYC</td>
                                <td>$139.58</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">478 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 56%;" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$1,89,547</td>
                            </tr>
                            <tr>
                                <td>Marco Outlet - SRT</td>
                                <td>$149.99</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">73 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 16%;" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$87,245</td>
                            </tr>
                            <tr>
                                <td>Chairtest Outlet - HY</td>
                                <td>$135.87</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">781 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$5,87,478</td>
                            </tr>
                            <tr>
                                <td>Nworld Group - India</td>
                                <td>$159.89</td>
                                <td>
                                    <div class="progress-w-percent mb-0">
                                        <span class="progress-value">815 </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 89%;" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$55,781</td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->
                <!--  end table-responsive -->

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row-->

@endsection