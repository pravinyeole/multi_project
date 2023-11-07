<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>IN₹ Bharat</title>
  <?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: text/css');
  ?>
  <!-- Favicons -->
  <link rel="shortcut icon" href="{{asset('images/logo/hpa_logo.png')}}">
  <link href="{{asset('images/logo/hpa_logo.png')}}" rel="apple-touch-icon">
  <link rel="stylesheet" href="{{asset('vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{asset('vendors/typicons/typicons.css')}}" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{asset('vendors/simple-line-icons/css/simple-line-icons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
  <!-- endinject -->
  <!-- inject:css -->
  @if(Auth::user()->user_role != 'S' )
  <link rel="stylesheet" href="{{asset('css/vertical-layout-light/style.css')}}">
  @else
  <link rel="stylesheet" href="{{asset('css/vertical-layout-light/style1.css')}}">
  @endif
  <!-- Template Main CSS File -->
  {{-- Vendor Scripts --}}
  <script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
  <style>
    .brand-logo-mini {
      height: 50px;
      width: 40px;
    }
  </style>
</head>

<body>
  <div class="container-scroller mobile-wrapper">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="{{url('/home')}}">
            <img src="{{asset('images/logo/inrb_logo.svg')}}" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{url('/home')}}">
            <img src="{{asset('images/logo/inrb_logo.svg')}}" alt="logo">
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <?php
            date_default_timezone_set('Asia/Kolkata');
            $slo = '';
            /* This sets the $time variable to the current hour in the 24 hour clock format */
            $time = date("H");
            /* Set the $timezone variable to become the current timezone */
            $timezone = date("e");
            /* If the time is less than 1200 hours, show good morning */
            if ($time < "12") {
              $slo = "Good morning";
            } else
              /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
              if ($time >= "12" && $time < "17") {
                $slo = "Good afternoon";
              } else
                /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
                if ($time >= "17" && $time < "19") {
                  $slo = "Good evening";
                } else
                  /* Finally, show good night if the time is greater than or equal to 1900 hours */
                  if ($time >= "19") {
                    $slo = "Good night";
                  }
            ?>
            @if(Auth::user()->user_role == 'S' )
            <h1 class="welcome-text">{{$slo}}, <span class="text-black fw-bold">{{Auth::User()->user_fname}} {{Auth::User()->user_lname}}</span></h1>
            <h3 class="welcome-sub-text">
              <button type="button" onclick="copyText('{{Session::get('cryptUrl')}}')" class="btn btn-success btn-fw p-2 copyBtn">Copy Refferal URL</button>
            </h3>
            @endif
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          @if(Auth::user()->user_role == 'S' )
          <li class="nav-item d-lg-block">
            <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker datepicker-popup">
              <span class="input-group-addon input-group-prepend border-right">
                <span class="icon-calendar input-group-text calendar-icon"></span>
              </span>
              <input type="text" class="form-control">
            </div>
          </li>
          @endif
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- User NAme Top Bar End -->
    <div class="container-fluid page-body-wrapper pt-0 proBanner-padding-top">
      <!-- partial:partials/_sidebar.html -->
      @if(Auth::user()->user_role != 'S' )
      @include('layouts.sidebar')
      @else
      @include('layouts.sidebar_superadmin')
      @endif
      <!-- partial -->
      <div class="main-panel">
        @if (Session::has('create_id_alert'))
        <div class="alert alert-warning alert-dismissible" role="alert">
          <strong>Warning !</strong> {{ session('create_id_alert') }}
        </div>
        @endif
        @if (Session::has('create_id_error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
          <strong>Error !</strong> {{ session('create_id_error') }}
        </div>
        @endif