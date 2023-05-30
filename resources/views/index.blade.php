<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    @if (isset($produkPage))
        <title>{{ $produk->nama_produk }} - {{ website(1)->nama_website }}</title>
        <meta property="og:url" content="{{ url('produk-detail/' . $produk->slug) }}" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="{!! strip_tags($produk->nama_produk) !!}" />
        <meta property="og:image" content="{{ asset('image/produk/large/' . $produk->gambar_utama) }}" />
        <meta property="og:description" content="{!! strip_tags($produk->deskripsi) !!}" name="description">
        <meta
            content="Branding, Souvenir Perusahaan, Seminar KIT, Merchandise Kantor, Produk ART, Barang Promosi, Produk Promosi, Design Baju, Design Sendiri, Bukittinggi, Padang, Solok, Sumatera Barat, Padang Panjang, Indonesia, Payakumbuh, Sawah Lunto, Sijunjung, Tanah Datar, Batusangkar, {{ $produk->kategori->nama_kategori }}"
            name="keywords">
    @else
        <title>{{  website(1)->nama_website }}</title>
        <meta content="{!! strip_tags(website(1)->description) !!}" name="description">
        <meta content="{{ website(1)->tagline }}" name="keywords">
        <meta
            content="Branding, Souvenir Perusahaan, Seminar KIT, Merchandise Kantor, Produk ART, Barang Promosi, Produk Promosi, Design Baju, Design Sendiri, Bukittinggi, Padang, Solok, Sumatera Barat, Padang Panjang, Indonesia, Payakumbuh, Sawah Lunto, Sijunjung, Tanah Datar, Batusangkar"
            name="keywords">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicons -->
    <link href="{{ asset('image/website/' . website(1)->icon) }}" rel="icon">
    <link href="{{ asset('image/website/' . website(1)->icon) }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <!--  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
 -->
    <!-- Vendor CSS Files -->
    <link href="{{ asset('fe_assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('fe_assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fe_assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('fe_assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fe_assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('fe_assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Template Main CSS File -->
    <link href="{{ asset('fe_assets/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

    <style>
        body {
            /* font-family: "Quicksand", "Open Sans"; */
            font-size: 14px;
        }

        .float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 60px;
            right: 10px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
        }

        .my-float {
            margin-top: 16px;
        }


        /* Absolute Center Spinner */
        #loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            display: none;
        }

        /* Transparent Overlay */
        #loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));

            background: -webkit-radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));
        }

        /* :not(:required) hides these rules from IE9 and below */
        #loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        #loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 150ms infinite linear;
            -moz-animation: spinner 150ms infinite linear;
            -ms-animation: spinner 150ms infinite linear;
            -o-animation: spinner 150ms infinite linear;
            animation: spinner 150ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

    </style>
</head>

<body>
    <div id="loading">Loading&#8230;</div>


    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

            <a href="/" class="logo d-flex align-items-center">
                <img src="{{ asset('image/website/' . website(1)->icon) }}" alt="{{  website(1)->nama_website }}">
                <span>{{ str_replace(' ', '',  website(1)->nama_website) }}</span>
            </a>

            <nav id="navbar" class="navbar">
                <ul>
                    <?php
                  if(!empty(dataMenu())){
                    foreach(dataMenu() as $menu){
                      ?>
                    <li><a class="nav-link scrollto active" href="{{ $menu->url }}">{{ $menu->title }}</a></li>
                    <?php
                    }
                  }
                  ?>
                    {{-- <li><a class="nav-link scrollto active" href="{{url('/')}}">Home</a></li> --}}
                    <!-- <li><a class="nav-link scrollto" href="/#pricing">Pricing</a></li> -->
                    <!-- <li><a class="nav-link scrollto" href="#services">Services</a></li> -->
                    {{-- <li><a class="nav-link scrollto @if (isset($produks)) active @endif" href="{{url('produk-page')}}">Product</a></li> --}}
                    {{-- <li><a class="nav-link scrollto" href="{{url('/#portfolio')}}">Portfolio</a></li> --}}
                    {{-- <li><a class="nav-link scrollto" href="{{url('testimoni-page')}}">Testimoni</a></li> --}}
                    {{-- <li><a class="nav-link scrollto" href="{{url('how-to-order')}}">How to Order</a></li> --}}
                    {{-- <li><a class="nav-link scrollto" href="{{url('/#services')}}">Services</a></li> --}}
                    {{-- <li><a class="nav-link scrollto" href="{{url('blog')}}">Blog</a></li> --}}
                    {{-- <li><a class="nav-link scrollto" href="{{url('/#about')}}">About</a></li> --}}
                    <!-- <li><a class="nav-link scrollto" href="/#team">Team</a></li> -->
                    <!-- <li><a href="blog">Blog</a></li> -->
                    <!--  <li class="dropdown"><a href="#"><span>Drop Down</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 2</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
            </ul>
          </li> -->
                    {{-- <li><a class="nav-link scrollto" href="/#contact">Contact</a></li> --}}
                    <!-- <li><a class="getstarted scrollto" href="#contact">Pesan Sekarang</a></li> -->
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->


    <main id="main">

        @yield('content')
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">

        <!--   <div class="footer-newsletter">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12 text-center">
          <h4>Our Newsletter</h4>
          <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
        </div>
        <div class="col-lg-6">
          <form action="" method="post">
            <input type="email" name="email"><input type="submit" value="Subscribe">
          </form>
        </div>
      </div>
    </div>
  </div> -->

        <div class="footer-top">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-5 col-md-12 footer-info">
                        <a href="index.html" class="logo d-flex align-items-center">
                            <img src="{{ asset('image/website/' . website(1)->icon) }}" alt="{{  website(1)->nama_website }}">
                            <span>{{  website(1)->nama_website }}</span>
                        </a>
                        <p>Jika Anda berencana untuk membuat merchandise promosi dengan kualitas terbaik, segera hubungi
                            kami dan kami akan memberikan informasi dan solusi terbaik bagi Anda. So let us help you
                            today!</p>
                        <div class="social-links mt-3">
                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6 footer-links">
                        <!-- <h4> Link utama</h4>
            <ul>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul> -->
                    </div>
                    <!--
          <div class="col-lg-2 col-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><i class="bi bi-chevron-right"></i> <a href="#"> Souvenir Kit</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Diskon Produk</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Pengiriman Cepat</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Respon Cepat</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Graphic Design</a></li>
            </ul>
          </div> -->
                    <div class="col-lg-4 col-md-12 footer-contact text-md-start">
                        <h4 style="padding: 0;">Hubungi Kami</h4>
                        <p>{{strip_tags(website(1)->address)}}, {{website(1)->webkecamatan->name}}, {{website(1)->webkota->name}}, {{website(1)->webprovinsi->name}}
                            {{website(1)->kode_pos}}<br>

                            <strong>Phone: </strong>
                            {{website(1)->contact}}
                            <br>
                            <strong>Email:</strong> {{strtolower(str_replace(' ','',website(1)->nama_website))}}@gmail.com<br>
                        </p>

                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>{{  website(1)->nama_website }} - 2022</span></strong>. All Rights Reserved
            </div>
            <div class="credits">

                Designed by <a href="#">Tim SJB</a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <a href="https://api.whatsapp.com/send?phone={{str_replace('+','',gantiformat(website(1)->contact))}}" class="float" target="_blank">
        <i class="fa fa-whatsapp my-float"></i>
    </a>

    <!-- Vendor JS Files -->
    {{-- <script src="{{ asset('fe_assets/vendor/purecounter/purecounter.js') }}"></script> --}}
    <script src="{{ asset('fe_assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('fe_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fe_assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('fe_assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    {{-- <script src="{{ asset('fe_assets/vendor/swiper/swiper-bundle.min.js') }}"></script> --}}
    <script src="{{ asset('fe_assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('fe_assets/js/main.js') }}"></script>

</body>

</html>
