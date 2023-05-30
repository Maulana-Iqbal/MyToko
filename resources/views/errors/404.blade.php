<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>{{website()->nama_website}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="{{website()->deskripsi}}" name="description" />
        <meta content="Nengki Rahmad" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('image/website/' . website()->icon) }}">

        <!-- App css -->
        <link href="{{ asset('hy_assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('hy_assets/css/app-modern.min.css')}}" rel="stylesheet" type="text/css" id="light-style" />
        <link href="{{ asset('hy_assets/css/app-modern-dark.min.css')}}" rel="stylesheet" type="text/css" id="dark-style" />

    </head>

    <body class="loading authentication-bg" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>

<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-lg-5">
                        <div class="card">
                            <!-- Logo -->
                            <div class="card-header pt-4 pb-4 text-center bg-primary">
                                <a href="index.html">
                                    <span><img src="{{ asset('image/website/' . website()->icon) }}" alt="" height="18"></span>
                                </a>
                            </div>
                            
                            <div class="card-body p-4">

                                <div class="text-center">
                                    <img src="{{asset('hy_assets/images/startman.svg')}}" height="120" alt="File not found Image">

                                    <h1 class="text-error mt-4">404</h1>
                                    <h4 class="text-uppercase text-danger mt-3">Page Not Found!</h4>
                                    <p class="text-muted mt-3">Why not try refreshing your page? or you can contact <a href="" class="text-muted"><b>Support</b></a></p>

                                    <a class="btn btn-info mt-3" href="{{url('/dashboard')}}"><i class="mdi mdi-reply"></i> Return Dashboard</a>
                                </div>

                            </div> <!-- end card-body-->
                        </div>
                        <!-- end card-->
                        
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <footer class="footer footer-alt">
            {{date('Y')}} © {{website()->nama_website}}
        </footer>

        <!-- bundle -->
        <script src="{{ asset('hy_assets/js/vendor.min.js')}}"></script>
        <script src="{{ asset('hy_assets/js/app.min.js')}}"></script>
        
    </body>
</html>
