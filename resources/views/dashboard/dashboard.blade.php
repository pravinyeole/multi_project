@extends('layouts/common_template')

@section('title', 'Dashboard')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap.min.css')) }}">
@endsection
@section('page-style')
<!-- Page css files -->
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
{{-- <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}"> --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-invoice-list.css')) }}">
@endsection

@section('content')
<!-- Dashboard Analytics Start -->
<div class="container {{ Auth::user()->user_role == 'A' ? '' : 'd-none' }}" style="margin-top:30px">

</div>
<div class="container {{ Auth::user()->user_role == 'U' ? '' : 'd-none' }}">
    <div class="row">
        <div class="container-fluid my-3">
            <div class="row">
                <div class="col-sm-12 card">
                    <div class="card-datatable">
                        <div class="card-header">
                        </div>
                        @include('normaluser.index')
                    </div>

                </div>

            </div><!-- /.row -->
            <div class="row">

            </div><!-- /.row -->

        </div><!-- /.row -->

    </div>
</div>

<div class="container {{ Auth::user()->user_role == 'S' ? '' : 'd-none' }}">
    <div class="row">
        <div class="container-fluid my-3">

            <h1 class="text-center mb-4">Hello there ,
                <?php
                $hour = date('H');
                $dayTerm = $hour >= 17 ? 'Evening' : ($hour >= 12 ? 'Afternoon' : 'Morning');
                echo 'Good ' . $dayTerm . ' !'; ?> &#128522;
            </h1>
            <div class="row">
                <div class="col-sm-12 card">
                    <div class="card-datatable">
                        <div class="card-header">
                            <h4 class="card-title">Selected Paper<span style="font-size: 13px"></span></h4>
                        </div>
                        <table class="dt-responsive table dataTable dtr-column collapsed" id="table_department">
                            <thead style="color:#1158EC;">
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Department</th>
                                    <th>Selected Paper</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div><!-- /.row -->
            <div class="row">
                <div class="col-md-4">

                    <!-- /.card -->
                </div>

                <div class="col-md-4">
                    <!-- /.card -->
                </div>
            </div><!-- /.row -->

        </div><!-- /.row -->

    </div>
</div>

<!-- Dashboard Analytics end -->
@endsection


@section('vendor-script')
{{-- vendor files --}}
<script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
<!-- Page js files -->
{{-- <script src="{{ asset(mix('js/scripts/pages/dashboard-analytics.js')) }}"></script> --}}
@endsection