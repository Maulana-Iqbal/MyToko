<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>{{  website(1)->nama_website }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="{{  website(1)->description }}" name="description" />
        <meta content="Nengki Rahmat" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('image/website/'.website(1)->icon) }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App css -->
        <link href="{{ asset('hy_assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('hy_assets/css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

    </head>

    <body class="loading authentication-bg" data-layout-config='{"darkMode":false}'>
        <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-lg-5">
                        <div class="card">

                            <!-- Logo -->
                            <div class="card-header pt-4 pb-4 text-center bg-primary">
                                <a href="/">
                                    <span><img src="{{ asset('image/website/'.website(1)->icon) }}" alt="" height="80px"></span>
                                </a>
                            </div>

                            <div class="card-body p-4">
