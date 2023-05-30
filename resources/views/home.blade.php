@extends('layouts.app')

@section('content')
    <style>
        .page-title-box .page-title {
            line-height: 30px;
        }
    </style>
    <div class="content-page">
        <div class="content">
            <div class="row mt-4">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box">
                                        <h4 class="page-title">Filter</h4>
                                    </div>
                                </div>
                            </div>

                            <form action="/dashboard" method="get" onsubmit="return checkEmpData();">
                                @csrf
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col">
                                                <label for="bulan">Bulan</label>
                                                <select name="bulan" id="bulan" class="form-control">
                                                    <option value="">Semua</option>
                                                    <option value="01">Januari</option>
                                                    <option value="02">Februari</option>
                                                    <option value="03">Maret</option>
                                                    <option value="04">April</option>
                                                    <option value="05">Mei</option>
                                                    <option value="06">Juni</option>
                                                    <option value="07">Juli</option>
                                                    <option value="08">Agustus</option>
                                                    <option value="09">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="tahun">Tahun</label>
                                                <select name="tahun" id="tahun" class="form-control">
                                                    <option value="">Semua</option>
                                                    @for ($i = 2021; $i <= date('Y'); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        @can('show-all')
                                            <div class="row">
                                                <div class="col">
                                                    <label for="website">Perusahaan</label>
                                                    <select name="website" id="website" class="form-control">
                                                        <option value="">Semua</option>
                                                        @foreach (dataWebsite() as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama_website }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>


                                    <div class="col-md-3">
                                        <label for="">Aksi</label>
                                        <div class="form-group">
                                            <button id="filter" type="submit"
                                                class="btn btn-outline-primary mb-2">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if (!empty($month) and !empty($year))
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <canvas id="chartKasKecil"></canvas>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <canvas id="chartKasBesar"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="@hasrole('SUPERADMIN') col-lg-8 col-md-8 @else col-lg-12 col-md-12 @endhasrole">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Saldo</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="row">
                                {{-- <div class="col-lg-4">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-account-multiple widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Saldo Xendit</h5>
                                    <h3 class="mt-3 mb-3">Rp. {{ number_format(saldo(), 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-muted">
                                    </p>
                                </div>
                            </div>
                        </div> --}}

{{--
                                <div class="col-lg-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i class="mdi mdi-account-multiple widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Kas Besar</h5>
                                            <h3 class="mt-3 mb-3 text-primary">Rp. {{ number_format($kasBesar, 0, ',', '.') }}</h3>
                                            <a target="_blank" href="/print-laporan-kas?jenis=2&print=true<?php if(isset($_GET['bulan'])){ echo "&bulan=".$_GET['bulan']; }  if(isset($_GET['tahun'])){ echo "&tahun=".$_GET['tahun']; } if(isset($_GET['website'])){ echo "&website=".$_GET['website']; } ?>">Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i class="mdi mdi-account-multiple widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Kas Kecil</h5>
                                            <h3 class="mt-3 mb-3 text-success">Rp. {{ number_format($kasKecil, 0, ',', '.') }}</h3>
                                            <a target="_blank" href="/print-laporan-kas?jenis=1&print=true<?php if(isset($_GET['bulan'])){ echo "&bulan=".$_GET['bulan']; }  if(isset($_GET['tahun'])){ echo "&tahun=".$_GET['tahun']; } if(isset($_GET['website'])){ echo "&website=".$_GET['website']; } ?>">Selengkapnya</a>
                                        </div>
                                    </div>
                                </div> --}}


                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Pajak</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i class="mdi mdi-account-multiple widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">PPN</h5>
                                            <h3 class="mt-3 mb-3 text-success">Rp. {{ number_format($ppn, 0, ',', '.') }}</h3>
                                            <a target="_blank" href="/print-laporan-pajak?jenis_pajak=ppn&print=true<?php if(isset($_GET['bulan'])){ echo "&bulan=".$_GET['bulan']; }  if(isset($_GET['tahun'])){ echo "&tahun=".$_GET['tahun']; } if(isset($_GET['website'])){ echo "&website=".$_GET['website']; } ?>">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                                <div class="col-lg-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i class="mdi mdi-account-multiple widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">PPH</h5>
                                            <h3 class="mt-3 mb-3 text-primary">Rp. {{ number_format($pph, 0, ',', '.') }}</h3>
                                            <a target="_blank" href="/print-laporan-pajak?jenis_pajak=pph&print=true<?php if(isset($_GET['bulan'])){ echo "&bulan=".$_GET['bulan']; }  if(isset($_GET['tahun'])){ echo "&tahun=".$_GET['tahun']; } if(isset($_GET['website'])){ echo "&website=".$_GET['website']; } ?>">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div>


                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Transaksi</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">

                            <div class="row">
                                {{-- <div class="col-lg-6">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-account-multiple widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Customers</h5>
                                    <h3 class="mt-3 mb-3">{{ $pelanggan }}</h3>
                                    <p class="mb-0 text-muted">
                                        <!-- <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 5.27%</span> -->

                                    </p>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col--> --}}

                                <div class="col-md-6 col-md-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i
                                                    class="mdi mdi-cart-plus widget-icon bg-success-lighten text-success"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Semua Transaksi
                                            </h5>
                                            <h3 class="mt-3 mb-3 text-primary">{{ $trxAll }}</h3>
                                            <a href="/transaksi">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                                <div class="col-lg-6 col-md-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i
                                                    class="mdi mdi-cart-plus widget-icon bg-success-lighten text-success"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Berhasil</h5>
                                            <h3 class="mt-3 mb-3 text-success">{{ $trxSuccess }}</h3>
                                            <a href="/transaksi">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                                <div class="col-lg-6 col-md-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i
                                                    class="mdi mdi-cart-plus widget-icon bg-success-lighten text-success"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Menunggu, Proses, Dibayar, Dikirim</h5>
                                            <h3 class="mt-3 mb-3 text-info">{{ $trxOnProses }}</h3>
                                            <a href="/transaksi">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                                <div class="col-lg-6 col-md-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i
                                                    class="mdi mdi-cart-plus widget-icon bg-success-lighten text-success"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Dibatalkan</h5>
                                            <h3 class="mt-3 mb-3 text-danger">{{ $trxCancel }}</h3>
                                            <a href="/transaksi">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->

                            </div> <!-- end row -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box">
                                        <h4 class="page-title">Pengeluaran</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="col-lg-3">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-currency-usd widget-icon bg-success-lighten text-success"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Average Revenue">Pendapatan</h5>
                                    <h3 class="mt-3 mb-3">Rp. {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-muted">

                                    </p>
                                </div>
                            </div>
                        </div> --}}

                                <div class="col-lg-6 col-md-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i class="mdi mdi-pulse widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Growth">Semua Pengeluaran</h5>
                                            <h3 class="mt-3 mb-3 text-primary">Rp. {{ number_format($pengeluaranAll, 0, ',', '.') }}
                                            </h3>
                                            <a target="_blank" href="/print-laporan-pengeluaran?persetujuan=&print=true<?php if(isset($_GET['bulan'])){ echo "&bulan=".$_GET['bulan']; }  if(isset($_GET['tahun'])){ echo "&tahun=".$_GET['tahun']; } if(isset($_GET['website'])){ echo "&website=".$_GET['website']; } ?>">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                                <div class="col-lg-6 col-md-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i class="mdi mdi-pulse widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Growth">Disetujui
                                            </h5>
                                            <h3 class="mt-3 mb-3 text-success">Rp. {{ number_format($pengeluaranAccept, 0, ',', '.') }}
                                            </h3>
                                            <a target="_blank" href="/print-laporan-pengeluaran?persetujuan=2&print=true<?php if(isset($_GET['bulan'])){ echo "&bulan=".$_GET['bulan']; }  if(isset($_GET['tahun'])){ echo "&tahun=".$_GET['tahun']; } if(isset($_GET['website'])){ echo "&website=".$_GET['website']; } ?>">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                                <div class="col-lg-6 col-md-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i class="mdi mdi-pulse widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Growth">Belum Verifikasi</h5>
                                            <h3 class="mt-3 mb-3 text-info">Rp.
                                                {{ number_format($pengeluaranWaiting, 0, ',', '.') }}</h3>
                                                <a target="_blank" href="/print-laporan-pengeluaran?persetujuan=1&print=true<?php if(isset($_GET['bulan'])){ echo "&bulan=".$_GET['bulan']; }  if(isset($_GET['tahun'])){ echo "&tahun=".$_GET['tahun']; } if(isset($_GET['website'])){ echo "&website=".$_GET['website']; } ?>">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->

                                <div class="col-lg-6 col-md-6">
                                    <div class="card widget-flat">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <i class="mdi mdi-pulse widget-icon"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal mt-0" title="Growth">Ditolak</h5>
                                            <h3 class="mt-3 mb-3 text-danger">Rp.
                                                {{ number_format($pengeluaranReject, 0, ',', '.') }}</h3>
                                                <a target="_blank" href="/print-laporan-pengeluaran?persetujuan=3&print=true<?php if(isset($_GET['bulan'])){ echo "&bulan=".$_GET['bulan']; }  if(isset($_GET['tahun'])){ echo "&tahun=".$_GET['tahun']; } if(isset($_GET['website'])){ echo "&website=".$_GET['website']; } ?>">Selengkapnya</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                            </div> <!-- end row -->

                        </div> <!-- end col -->



                    </div>

                </div>
                @can('show-all')
                    <div class="col-lg-4 col-md-4">
                        <div class="card" style="margin-top: 30px;">
                            <div class="card-header">
                                <h4 class="page-title">Last Activity</h4>
                            </div>
                            <div class="card-body" style="max-height: 100vh; overflow-y: scroll;">
                                @foreach ($activity as $activity)
                                    <b class="text-info">{{ $activity->user->name }}</b><br>
                                    <small><time><small class="text-muted ml-auto"><i class="bi bi-clock"></i>
                                                {{ Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}</small></time></small>
                                    <br>
                                    <p style="text-indent: 10px; margin-bottom: 0;" class="text-success">{{ $activity->activity_name }}</p>
                                    <p style="text-indent: 10px; margin-bottom: 0;">
                                        <small>{{ $activity->description }}</small>
                                    </p>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endcan

            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/chartJsUtils.js"></script>
    <script>
        $(document).ready(function() {
            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $("select[name=bulan]").val("{{ $month }}");

            $("select[name=tahun]").val("{{ $year }}");

            $("select[name=website]").val("{{ $website }}");
        })
    </script>

    <script>
        const Utils = ChartUtils.init();
        const data = {
            labels: [<?php foreach ($kasKecilDebit as $index => $v) {
                echo $index . ',';
            } ?>],
            datasets: [{
                    label: 'Kas Kecil Keluar',
                    data: [<?php foreach ($kasKecilDebit as $index => $v) {
                        echo $v . ',';
                    } ?>],
                    borderColor: Utils.CHART_COLORS.red,
                    backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
                },
                {
                    label: 'Kas Kecil Masuk',
                    data: [<?php foreach ($kasKecilKredit as $index => $v) {
                        echo $v . ',';
                    } ?>],
                    borderColor: Utils.CHART_COLORS.blue,
                    backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
                }
            ]
        };

        const data2 = {
            labels: [<?php foreach ($kasBesarDebit as $index => $v) {
                echo $index . ',';
            } ?>],
            datasets: [{
                    label: 'Kas Besar Keluar',
                    data: [<?php foreach ($kasBesarDebit as $index => $v) {
                        echo $v . ',';
                    } ?>],
                    borderColor: Utils.CHART_COLORS.red,
                    backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
                },
                {
                    label: 'Kas Besar Masuk',
                    data: [<?php foreach ($kasBesarKredit as $index => $v) {
                        echo $v . ',';
                    } ?>],
                    borderColor: Utils.CHART_COLORS.blue,
                    backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
                }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Kas Kecil'
                    }
                }
            },
        };

        const config2 = {
            type: 'line',
            data: data2,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Kas Besar'
                    }
                }
            },
        };


        const myChart = new Chart(
            document.getElementById('chartKasKecil'),
            config
        );

        const myChart2 = new Chart(
            document.getElementById('chartKasBesar'),
            config2
        );

        function checkEmpData(){
            var bulan=$("#bulan").val();
            var tahun=$("#tahun").val();
            if(bulan!=''){
                if(tahun==''){
                    alert('Silahkan Pilih Tahun');
                    return false;
                }
            }
        }
    </script>


@endsection
