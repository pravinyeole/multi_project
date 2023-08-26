@extends('layouts/common_template')

@section('title', $title)

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">{{__("labels.user.list")}}</h4>
            <div class="dt-action-buttons text-right">
                <div class="dt-buttons d-inline-flex">
                   <p class="m-1">Pins:  <span class="text-primary">{{$getNoOfPins->pins ?? ''}}</span></p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table_user">
                <thead>
                    <tr>
                        <th>{{__("labels.no")}}</th>
                        <th>Username</th>                             
                        <th>Email</th>
                        <th>Mobile No</th>
                        {{-- <th>Pins</th>
                        <th>Event</th> --}}
                        <th>{{__("labels.action")}}</th>
                    </tr>
                </thead>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>

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
<script>
$(document).ready(function () {
    // DataTable for organization
    if (document.getElementById("table_user")) {
        var table = $('#table_user').DataTable({
            processing: true,
            serverSide: true,
            order: [1, 'ASC'],
            dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12 col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            ajax: {
                url: base_url + "/superadmin/users",
                data: function (data) {
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'user_name', name: 'user_name'},
                {data: 'email', name: 'email'},
                {data: 'mobile_number', name: 'mobile_number'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            drawCallback: function (settings) {
                enableDisableInputs(true);
            }
        });

    }

    $('.addDepartment').on('submit', function (e) {
        if ($(".addDepartment").valid()) {
            $('#loader').show();
            return true;
        }
    });
    $('.editDepartment').on('submit', function (e) {
        if ($(".editDepartment").valid()) {
            $('#loader').show();
            return true;
        }
    });
});

</script>
@endsection