@extends('layouts.app')

@section('content')
<?php
$tpenjualan = $jenis['penjualan'] ?? 0;
$tpembelian = $jenis['pembelian'] ?? 0;
$tpendapatan = $tpenjualan - $tpembelian;
$tpersentase = 0;
if ($tpendapatan > 0 and $tpembelian > 0) {
    $tpersentase = ($tpendapatan / $tpembelian) * 100;
}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">

            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <form action="/dashboard" method="get" onsubmit="return checkEmpData();">
            @csrf
            <div class="row">
                <div class=" col-9 col-sm-9">
                    <div class="row">
                        <div class="col">
                            <label for="bulan">Bulan</label>
                            <select name="bulan" id="bulan" class="form-control">
                                <option value="">Semua</option>
                                <option @if($month==1) selected @endif value="01">Januari</option>
                                <option @if($month==2) selected @endif value="02">Februari</option>
                                <option @if($month==3) selected @endif value="03">Maret</option>
                                <option @if($month==4) selected @endif value="04">April</option>
                                <option @if($month==5) selected @endif value="05">Mei</option>
                                <option @if($month==6) selected @endif value="06">Juni</option>
                                <option @if($month==7) selected @endif value="07">Juli</option>
                                <option @if($month==8) selected @endif value="08">Agustus</option>
                                <option @if($month==9) selected @endif value="09">September</option>
                                <option @if($month==10) selected @endif value="10">Oktober</option>
                                <option @if($month==11) selected @endif value="11">November</option>
                                <option @if($month==12) selected @endif value="12">Desember</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control">
                                <option value="">Semua</option>
                                @for ($i = 2021; $i <= date('Y'); $i++) <option @if($year==$i) selected @endif value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        @can('show-all')
                        <div class="col">
                            <label for="website">Toko</label>
                            <select name="website" id="website" class="form-control">
                                <option value="">Semua</option>
                                @foreach (dataWebsite() as $item)
                                <option @if($website==$item->id) selected @endif value="{{ $item->id }}">{{ $item->nama_website }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endcan
                    </div>

                </div>


                <div class="col-3 col-sm-3">
                    <label for="">Aksi</label>
                    <div class="form-group">
                        <button id="filter" type="submit" class="btn btn-outline-primary mb-2">Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12">
        <div class="card widget-inline">
            <div class="card-header">
                <h4 class="header-title">Transaksi <small>(by filter)</small></h4>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-12 col-sm-12 col-lg-6">
                        <div class="row">
                            <div class="col-6 col-sm-6 col-lg-6">
                                <div class="card shadow-none m-0">
                                    <div class="card-body text-center">
                                        <i class="mdi mdi-cart-plus font-30 text-muted" style="font-size: 24px;"></i>
                                        <h3 class="font-16"><span>{{uang($tpembelian)}}</span></h3>
                                        <p class="text-muted font-14 mb-0">Pembelian / {{$jenisCount['pembelian']??0}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-sm-6 col-lg-6">
                                <div class="card shadow-none m-0 border-start">
                                    <div class="card-body text-center">
                                        <i class="mdi mdi-cart-check text-muted" style="font-size: 24px;"></i>
                                        <h3 class="font-16"><span>{{uang($tpenjualan)}}</span></h3>
                                        <p class="text-muted font-14 mb-0">Penjualan / {{$jenisCount['penjualan']??0}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-6 col-lg-6">
                                <div class="card shadow-none m-0 border-start">
                                    <div class="card-body text-center">
                                        <i class="mdi mdi-currency-usd text-muted" style="font-size: 24px;"></i>
                                        <h3 class="font-16"><span>{{uang($tpendapatan)}}</span></h3>
                                        <p class="text-muted font-14 mb-0">Pendapatan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-6 col-lg-6">
                                <div class="card shadow-none m-0 border-start">
                                    <div class="card-body text-center">
                                        <i class="mdi mdi-pulse text-muted" style="font-size: 24px;"></i>
                                        <h3 class="font-16"><span>{{$tpersentase}}%</span></h3>
                                        <p class="text-muted font-14 mb-0">Persentase Pendapatan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-lg-6">
                        <!-- <h4 class="header-title">Grafik <small>(by filter)</small></h4> -->
                        <canvas id="chart" class="float-middle"></canvas>
                    </div>

                    <!-- <div class="col-6 col-sm-6 col-xl-3">
                        <div class="card shadow-none m-0 border-start">
                            <div class="card-body text-center">
                                <i class="mdi mdi-cart-remove text-muted" style="font-size: 24px;"></i>
                                <h3><span>{{uang($jenis['pengurangan']??0)}}</span></h3>
                                <p class="text-muted font-15 mb-0">Pengurangan Stock / {{$jenisCount['pengurangan']??0}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-sm-6 col-xl-3">
                        <div class="card shadow-none m-0 border-start">
                            <div class="card-body text-center">
                                <i class="dripicons-scale text-muted" style="font-size: 24px;"></i>
                                <h3><span>{{uang($jenis['transfer']??0)}}</span></h3>
                                <p class="text-muted font-15 mb-0">Transfer Stock / {{$jenisCount['transfer']??0}}</p>
                            </div>
                        </div>
                    </div> -->

                </div> <!-- end row -->
            </div>
        </div> <!-- end card-box-->
    </div> <!-- end col-->




    <!-- <div class="col-lg-3">
        <div class="card tilebox-one">
            <div class="card-body">
                <i class="uil uil-users-alt float-end"></i>
                <h6 class="text-uppercase mt-0">Toko</h6>
                <h2 class="my-2" id="active-users-count">{{$totalToko}}</h2>
            </div>
        </div>
    </div> -->
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card tilebox-one">
            <div class="card-body">
                <i class="uil uil-users-alt float-end"></i>
                <h3 class="text-uppercase mt-0 font-14">Pengguna @can('show-all')<small>(by filter)</small>@endcan</h3>
                <h2 class="my-2" id="active-users-count">{{$totalPengguna}}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card tilebox-one">
            <div class="card-body">
                <i class="uil uil-users-alt float-end"></i>
                <h3 class="text-uppercase mt-0 font-14">Customer @can('show-all')<small>(by filter)</small>@endcan</h3>
                <h2 class="my-2" id="active-users-count">{{$totalCustomer}}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card tilebox-one">
            <div class="card-body">
                <i class="uil uil-users-alt float-end"></i>
                <h3 class="text-uppercase mt-0 font-14">Sales @can('show-all')<small>(by filter)</small>@endcan</h3>
                <h2 class="my-2" id="active-users-count">{{$totalSales}}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-lg-3">
        <div class="card tilebox-one">
            <div class="card-body">
                <i class="uil uil-users-alt float-end"></i>
                <h3 class="text-uppercase mt-0 font-14">Supplier @can('show-all')<small>(by filter)</small>@endcan</h3>
                <h2 class="my-2" id="active-users-count">{{$totalSupplier}}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">5 Barang Terlaris <small>(by filter)</small></h4>
            </div>
            <div class="card-body">
                <!-- <a href="" class="btn btn-sm btn-link float-end">Export
                                            <i class="mdi mdi-download ms-1"></i>
                                        </a> -->


                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Barang</th>
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
    </div> <!-- end col-->
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">10 Stock Hampir Habis (Jumlah <= 5) <small>(all)</small></h4>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Barang</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockProdukMenipis as $index=>$pm)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$pm->produk->nama_produk}}</td>
                                <td>{{$pm->jumlah}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/chartJsUtils.js"></script>
<script>
    const Utils = ChartUtils.init();
    const data = {
        labels: [<?php foreach ($pembelian as $index => $v) {
                        // if (isset($_GET['bulan']) and empty($_GET['bulan']) or !$_GET) {
                        //     if ($index == 1) {
                        //         echo '"Januari",';
                        //     } elseif ($index == 2) {
                        //         echo '"Februari",';
                        //     } elseif ($index == 3) {
                        //         echo '"Maret",';
                        //     } elseif ($index == 4) {
                        //         echo '"April",';
                        //     } elseif ($index == 5) {
                        //         echo '"Mei",';
                        //     } elseif ($index == 6) {
                        //         echo '"Juni",';
                        //     } elseif ($index == 7) {
                        //         echo '"Juli",';
                        //     } elseif ($index == 8) {
                        //         echo '"Agustus",';
                        //     } elseif ($index == 9) {
                        //         echo '"September",';
                        //     } elseif ($index == 10) {
                        //         echo '"Oktober",';
                        //     } elseif ($index == 11) {
                        //         echo '"November",';
                        //     } elseif ($index == 12) {
                        //         echo '"Desember",';
                        //     }
                        // } else {
                        echo $index . ',';
                        // }
                    } ?>],
        datasets: [{
                label: 'pembelian',
                data: [<?php foreach ($pembelian as $index => $v) {
                            echo $v . ',';
                        } ?>],
                borderColor: Utils.CHART_COLORS.red,
                backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
            },
            {
                label: 'Penjualan',
                data: [<?php foreach ($penjualan as $index => $v) {
                            echo $v . ',';
                        } ?>],
                borderColor: Utils.CHART_COLORS.blue,
                backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
            },

        ]
    };

    `{
        label: 'Transfer Stock',
        data: [<?php foreach ($transfer as $index => $v) {
                    echo $v . ',';
                } ?>],
        borderColor: Utils.CHART_COLORS.purple,
        backgroundColor: Utils.transparentize(Utils.CHART_COLORS.green, 1),
    }, {
        label: 'Pengurangan Stock',
        data: [<?php foreach ($pengurangan as $index => $v) {
                    echo $v . ',';
                } ?>],
        borderColor: Utils.CHART_COLORS.yellow,
        backgroundColor: Utils.transparentize(Utils.CHART_COLORS.yellow, 0.5),
    }`

    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                // title: {
                //     display: true,
                //     text: 'Grafik Transaksi Tahun'
                // }
            }
        },
    };




    const myChart = new Chart(
        document.getElementById('chart'),
        config
    );
</script>

@endsection