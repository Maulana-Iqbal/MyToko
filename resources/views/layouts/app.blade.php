<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>{{ website()->nama_website }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('image/website/' . website()->icon) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App css -->
    <!-- third party css -->
    <link href="{{ asset('hy_assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/vendor/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/vendor/select.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <link href="{{ asset('hy_assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('hy_assets/css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>




    <style>
        table.dataTable td {
            padding: 10px;
        }

        #loading {
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: spin 2s linear infinite;
            margin: auto;
        }

        #preloading {
            position: fixed;
            left: 50%;
            top: 40%;
            transform: translate(-50%, -50%);
            width: 140px;
            height: 140px;
            text-align: center;
        }

        #canvasloading {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            height: 100%;
            z-index: 999999;
            position: absolute;
            display: none;
        }

        #txt {
            font-weight: 700;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        body {
            /* font-family: "Quicksand", "Open Sans"; */
            font-size: 14px;
        }

        .select2-selection__clear {
            margin-right: 18px;
            font-size: 20px;
        }

        .table td,
        .table th {
            padding: 4px;
            font-size: 13px;
        }
    </style>
    <script>
        $(document).ready(function() {
            //ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
</head>

<body class="loading" data-layout="detached" data-layout-config='{"leftSidebarCondensed":false,"darkMode":false, "showRightSidebarOnStart": false}'>
    <div id="preloader">
        <div id="status">
            <div class="bouncing-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <div id="canvasloading">

        <div id="preloading">
            <div id="loading"></div>
            <p id="txt">Mohon Tunggu Sebentar...</p>
        </div>
    </div>
    <!-- Topbar Start -->
    <div class="navbar-custom topnav-navbar topnav-navbar-dark">
        <div class="container-fluid">

            <!-- LOGO -->
            <a href="/" class="topnav-logo">
                <span class="topnav-logo-lg">
                    <img src="{{ asset('image/website/' . website()->icon) }}" alt="{{ website()->nama_website }}" height="30">
                </span>
                <span class="topnav-logo-lg" style="vertical-align: middle; color: #ced4da; font-size:20px; margin-left: 10px;">{{ website()->nama_website }}</span>
                <span class="topnav-logo-sm">
                    <img src="{{ asset('image/website/' . website()->icon) }}" alt="" height="16">
                </span>
            </a>
            <ul class="list-unstyled topbar-menu float-end mb-0">

                <li class="dropdown notification-list d-xl-none">
                    <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="dripicons-search noti-icon"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                        <form class="p-3">
                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                        </form>
                    </div>
                </li>



                <!--
                <li class="notification-list">
                    <a class="nav-link end-bar-toggle" href="javascript: void(0);">
                        <i class="dripicons-gear noti-icon"></i>
                    </a>
                </li> -->

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" id="topbar-notifydrop" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="dripicons-bell noti-icon"></i>
                        <span class="noti-icon-badge"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg" aria-labelledby="topbar-notifydrop">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-end">
                                    {{-- <a href="javascript: void(0);" class="text-dark">
                                        <small>Clear All</small>
                                    </a> --}}
                                </span>Notification
                            </h5>
                        </div>

                        <div id="body-notifikasi" style="max-height: 230px;" data-simplebar>
                            <div id="data-notifikasi"></div>
                            <div class="auto-load-notifikasi text-center">
                                <div class="spinner-border text-success" role="status"></div>
                            </div>

                        </div>

                        <!-- All-->
                        {{-- <a href="javascript:void(0);"
                            class="dropdown-item text-center text-primary notify-item notify-all">
                            View All
                        </a> --}}

                    </div>
                </li>

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="account-user-avatar">
                            <img src="{{ asset('userFoto/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}" class="rounded-circle">
                        </span>
                        <span>
                            <span class="account-user-name">{{ auth()->user()->name }}</span>
                            <span class="account-position">{{ auth()->user()->level }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop">
                        <!-- item-->
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        @if(auth()->user()->can('user') or auth()->user()->can('user-profil'))
                        <a href="/profil" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-circle me-1"></i>
                            <span>Profil</span>
                        </a>
                        @endif

                        <!-- item-->
                        <a href="{{ route('logout') }}" class="dropdown-item notify-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout me-1"></i>
                            <span>Logout</span>
                        </a>


                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </div>
                </li>

            </ul>

            <a class="button-menu-mobile disable-btn">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>

        </div>
    </div>
    <!-- end Topbar -->

    <!-- Start Content-->
    <div class="container-fluid">

        <!-- Begin page -->
        <div class="wrapper">

            <!-- ========== Left Sidebar Start ========== -->
            <div class="leftside-menu leftside-menu-detached">

                <div class="leftbar-user">
                    <a href="javascript: void(0);">
                        <img src="{{ asset('userFoto/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}" height="42" class="rounded-circle shadow-sm">
                        <span class="leftbar-user-name">{{ auth()->user()->name }}</span>
                    </a>
                </div>

                <!--- Sidemenu -->
                <ul class="side-nav">

                    <li class="side-nav-title side-nav-item">Navigation</li>

                    <li class="side-nav-item">
                        <a href="/dashboard" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>


                    <li class="side-nav-title side-nav-item">Transaksi</li>
                    @if(auth()->user()->can('pembelian') or auth()->user()->can('pembelian-list'))
                    <li class="side-nav-item">
                        <a href="/pembelian" class="side-nav-link">
                            <i class="mdi mdi-cart-plus"></i>
                            <span> Pembelian </span>
                        </a>
                    </li>
                    @endif
                    <!-- @if(auth()->user()->can('penjualan') or auth()->user()->can('penjualan-list'))
                    <li class="side-nav-item">
                        <a href="/penjualan" class="side-nav-link">
                            <i class="mdi mdi-table"></i>
                            <span> Penjualan</span>
                        </a>
                    </li>
                    @endif -->
                    @if(auth()->user()->can('transaksi') or auth()->user()->can('transaksi-list'))
                    <li class="side-nav-item">
                        <a href="/transaksi" class="side-nav-link">
                            <i class="mdi mdi-cart-check"></i>
                            <span> Penjualan</span>
                        </a>
                    </li>
                    @endif
                    <li class="side-nav-title side-nav-item">Transaksi Lain</li>
                    <li class="side-nav-item">
                        <a href="/preorder" class="side-nav-link">
                            <i class="mdi mdi-cart-arrow-up"></i>
                            <span> Purchase Order</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/quotation" class="side-nav-link">
                            <i class="mdi mdi-cart-arrow-up"></i>
                            <span> Penawaran</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebar-pengurangan" aria-expanded="false" aria-controls="sidebar-pengurangan" class="side-nav-link">
                            <i class="mdi mdi-cart-remove"></i>
                            <span> Pengurangan</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebar-pengurangan">
                            <ul class="side-nav-second-level">
                                @if(auth()->user()->can('pengurangan') or auth()->user()->can('pengurangan-list'))
                                <li>
                                    <a href="/pengurangan">
                                        <i class="uil-circle"></i>
                                        <span> Data Pengurangan</span>
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->can('stock') or auth()->user()->can('stock-pengurangan'))
                                <li>
                                    <a href="/stock/pengurangan-stock">
                                        <i class="uil-circle"></i>
                                        <span> Item Pengurangan </span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>


                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebar-stock-transfer" aria-expanded="false" aria-controls="sidebar-stock-transfer" class="side-nav-link">
                            <i class="mdi mdi-cart-arrow-right"></i>
                            <span> Transfer Barang</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebar-stock-transfer">
                            <ul class="side-nav-second-level">

                                @if(auth()->user()->can('stocktransfer') or auth()->user()->can('stocktransfer-list'))
                                <li>
                                    <a href="/stock-transfer">
                                        <i class="uil-circle"></i>
                                        <span> Data Transfer</span>
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->can('stock') or auth()->user()->can('stock-transfer'))
                                <li>
                                    <a href="/stock/transfer">
                                        <i class="uil-circle"></i>
                                        <span> Item Transfer</span>
                                    </a>
                                </li>
                                @endif


                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-title side-nav-item">Stock Barang</li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarStock" aria-expanded="false" aria-controls="sidebarStock" class="side-nav-link">
                            <i class="mdi mdi-store"></i>
                            <span> Stock Barang</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarStock">
                            <ul class="side-nav-second-level">

                                @if(auth()->user()->can('stock') or auth()->user()->can('stock-list'))
                                <li>
                                    <a href="/stock">
                                        <i class="uil-circle"></i>
                                        <span> Semua Stock </span>
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->can('stock') or auth()->user()->can('stock-gudang'))
                                <li>
                                    <a href="/stock-gudang">
                                        <i class="uil-circle"></i>
                                        <span> Stock Gudang </span>
                                    </a>
                                </li>
                                @endif

                                @if(auth()->user()->can('stock') or auth()->user()->can('stock-masuk'))
                                <li>
                                    <a href="/stock/masuk">
                                        <i class="uil-circle"></i>
                                        <span> Masuk</span>
                                    </a>
                                </li>
                                @endif

                                @if(auth()->user()->can('stock') or auth()->user()->can('stock-keluar'))
                                <li>
                                    <a href="/stock/keluar">
                                        <i class="uil-circle"></i>
                                        <span> Keluar </span>
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-title side-nav-item">Akuntansi</li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebar-akun" aria-expanded="false" aria-controls="sidebar-akun" class="side-nav-link">
                            <i class="mdi mdi-table"></i>
                            <span> Akun</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebar-akun">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="/kategoriAkun">
                                        <i class="uil-circle"></i>
                                        <span> Kategori Akun</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/akun">
                                        <i class="uil-circle"></i>
                                        <span> Daftar Akun</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebar-kas" aria-expanded="false" aria-controls="sidebar-kas" class="side-nav-link">
                            <i class="mdi mdi-cash-usd"></i>
                            <span> Kas</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebar-kas">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="/kasBank">
                                        <i class="uil-circle"></i>
                                        <span> Kas & Bank</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/rekening">
                                        <i class="uil-circle"></i>
                                        <span> Rekening</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-item">
                        <a href="/pengeluaran" class="side-nav-link">
                            <i class="mdi mdi-cash-minus"></i>
                            <span> Pengeluaran</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/penerimaan" class="side-nav-link">
                            <i class="mdi mdi-cash-check"></i>
                            <span> Penerimaan</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/transfer" class="side-nav-link">
                            <i class="mdi mdi-bank-transfer"></i>
                            <span> Transfer</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/jurnal" class="side-nav-link">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span> Jurnal Umum</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebar-laporan-akuntansi" aria-expanded="false" aria-controls="sidebar-laporan-akuntansi" class="side-nav-link">
                            <i class="mdi mdi-table"></i>
                            <span> Laporan</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebar-laporan-akuntansi">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="/laporan/neraca">
                                        <i class="uil-circle"></i>
                                        <span> Neraca</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/laporan/buku-besar">
                                        <i class="uil-circle"></i>
                                        <span> Buku Besar</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/laporan/laba-rugi">
                                        <i class="uil-circle"></i>
                                        <span> Laba - Rugi</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-title side-nav-item">Master</li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarMaster" aria-expanded="false" aria-controls="sidebarEmail" class="side-nav-link">
                            <i class="mdi mdi-table"></i>
                            <span> Master</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarMaster">
                            <ul class="side-nav-second-level">

                                @if(auth()->user()->can('kategori') or auth()->user()->can('kategori-list'))
                                <li>
                                    <a href="/kategori">
                                        <i class="uil-circle"></i>
                                        <span> Kategori </span>
                                    </a>
                                </li>
                                @endif

                                @if(auth()->user()->can('satuan') or auth()->user()->can('satuan-list'))
                                <li>
                                    <a href="/satuan">
                                        <i class="uil-circle"></i>
                                        <span> Satuan </span>
                                    </a>
                                </li>
                                @endif

                                @if(auth()->user()->can('produk') or auth()->user()->can('produk-list'))
                                <li>
                                    <a href="/produk">
                                        <i class="uil-circle"></i>
                                        <span> Barang </span>
                                    </a>
                                </li>
                                @endif

                                @if(auth()->user()->can('gudang') or auth()->user()->can('gudang-list'))
                                <li>
                                    <a href="/gudang">
                                        <i class="uil-circle"></i>
                                        <span> Gudang </span>
                                    </a>
                                </li>
                                @endif

                                @if(auth()->user()->can('customer') or auth()->user()->can('customer-list'))
                                <li>
                                    <a href="/customer">
                                        <i class="uil-circle"></i>
                                        <span> Customer </span>
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->can('supplier') or auth()->user()->can('supplier-list'))
                                <li>
                                    <a href="/supplier">
                                        <i class="uil-circle"></i>
                                        <span> Supplier </span>
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->can('sales') or auth()->user()->can('sales-list'))
                                <li>
                                    <a href="/sales">
                                        <i class="uil-circle"></i>
                                        <span> Sales </span>
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </li>


                    <li class="side-nav-title side-nav-item">Pengaturan</li>


                    @if(auth()->user()->can('toko') or auth()->user()->can('toko-list'))
                    <li class="side-nav-item">
                        <a href="/toko" class="side-nav-link">
                            <i class="uil-store-alt"></i>
                            <span> Daftar Toko</span>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->can('toko') or auth()->user()->can('toko-edit'))
                    <li class="side-nav-item">
                        <a href="/toko/ubah/{{website()->username??''}}/" class="side-nav-link">
                            <i class="mdi mdi-cog"></i>
                            <span> Pengaturan Toko</span>
                        </a>
                    </li>
                    @endif
                    <li class="side-nav-item">
                        <a href="/toko/{{website()->username??''}}" class="side-nav-link">
                            <i class="uil-store"></i>
                            <span> Profil Toko</span>
                        </a>
                    </li>

                    @if(auth()->user()->can('user') or auth()->user()->can('user-list'))
                    <li class="side-nav-title side-nav-item">Pengguna</li>
                    <li class="side-nav-item">
                        <a href="/user" class="side-nav-link">
                            <i class="uil uil-users-alt"></i>
                            <span> Data Pengguna </span>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->can('role') or auth()->user()->can('role-list'))
                    <li class="side-nav-item">
                        <a href="/roles" class="side-nav-link">
                            <i class="uil-lock-access"></i>
                            <span> Roles </span>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->can('permission') or auth()->user()->can('permission-list'))
                    <li class="side-nav-item">
                        <a href="/permission" class="side-nav-link">
                            <i class="uil-shield-check"></i>
                            <span> Permission </span>
                        </a>
                    </li>
                    @endif
                    <li class="side-nav-item">
                        <a href="/user/{{auth()->user()->username??''}}" class="side-nav-link">
                            <i class="uil-user"></i>
                            <span> Profil Saya </span>
                        </a>
                    </li>



                    <div class="clearfix"></div>
                    <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

            <div class="content-page">
                <div class="content">

                    @yield('content')

                </div> <!-- End Content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                © {{ website()->nama_website }} - {{date('Y')}} All Right Reserved.
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-end footer-links d-none d-md-block">
                                    <a href="javascript: void(0);">About</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div> <!-- content-page -->

        </div> <!-- end wrapper-->
    </div>
    <!-- END Container -->




    <div class="rightbar-overlay"></div>
    <!-- /End-bar -->



    <script>
        // $(document).ready(function() {
        //     var ENDPOINT = "{{ url('/') }}";
        // var page = 1;
        // infinteLoadMore(page);
        // $('#body-notifikasi').scroll(function() {
        // if ($('#body-notifikasi').scrollTop() + $('#body-notifikasi').height() >= $('#body-notifikasi').height()) {
        // page++;
        // infinteLoadMore(page);
        // }
        // });


        // function infinteLoadMore(page) {
        // $.ajax({
        //         url: ENDPOINT + "/notifikasi",
        //         datatype: "html",
        //         type: "get",
        //         beforeSend: function() {
        //             $('.auto-load-notifikasi').show();
        //         }
        //     })
        //     .done(function(response) {
        //         if (response.length == 0) {
        //             $('.auto-load-notifikasi').html("We don't have more data to display :(");
        //             return;
        //         }
        //         $('.auto-load-notifikasi').hide();
        //         $("#data-notifikasi").append(response);
        //     })
        //     .fail(function(jqXHR, ajaxOptions, thrownError) {
        //         console.log('Server error occured');
        //     });



        // $(document).on("mouseover", ".notify-item", function() {
        //     var id = $(this).data('id');
        //     myurl = "{{ url('/notif-read') }}" + '/' + id
        //     $.ajax({
        //         type: "get",
        //         url: myurl,
        //         success: function(data) {

        //         },
        //         error: function(data) {
        //             console.log('Error:', data);
        //         }
        //     });
        // })

        // });
    </script>

    <!-- bundle -->
    <script src="{{ asset('hy_assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/app.min.js') }}"></script>

    <!-- third party js -->
    <script src="{{ asset('hy_assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.print.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.select.min.js') }}"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    <script src="{{ asset('hy_assets/js/pages/demo.datatable-init.js') }}"></script>
    <!-- end demo js-->

    {{-- <script src="{{ asset('hy_assets/js/vendor/apexcharts.min.js') }}"></script> --}}

    <!-- Todo js -->
    <script src="{{ asset('hy_assets/js/ui/component.todo.js') }}"></script>

    <!-- demo app -->
    {{-- <script src="{{ asset('hy_assets/js/pages/demo.dashboard-crm.js') }}"></script> --}}
    <!-- end demo js-->

    <!-- demo -->
    {{-- <script src="{{ asset('hy_assets/js/pages/demo.materialdesignicons.js') }}"></script> --}}
    <!-- end demo js-->

    <script src="{{ asset('js/sweetalert/sweetalert.min.js') }}"></script>
    <script src="/js/jquery.mask.min.js"></script>
    <!-- demo app -->
    <script src="{{ asset('hy_assets/js/pages/demo.form-wizard.js') }}"></script>
    <!-- end demo js-->

    <script>
        $(document).ready(function() {
            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('body').on("keyup", ".rupiah", function() {
                // Format mata uang.
                $(this).mask('0.000.000.000', {
                    reverse: true
                });

            })

            $('body').on("focus", ".rupiah", function() {
                if ($(this).val() == 0) {
                    $(this).val('');
                }
            })

            $('body').on("focusout", ".rupiah", function() {
                if ($(this).val() == '') {
                    $(this).val(0);
                }
            })

            $('body').on("focus", ".nilai", function() {
                if ($(this).val() == 0) {
                    $(this).val('');
                }
            })

            $('body').on("focusout", ".nilai", function() {
                if ($(this).val() == '') {
                    $(this).val(0);
                }
            })
        })
    </script>
</body>

</html>