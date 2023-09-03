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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/base/plugins/forms/pickers/form-flat-pickr.css') }}">
@endsection

@section('content')
    {{-- <section id="responsive-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Pin Request</h4>
                        <div class="dt-action-buttons text-right">
                        </div>
                    </div>
                    <div class="card-datatable">
                        <table class="table table-striped table-bordered" id="table_user">
                             <thead>
                                 <tr>
                                     <th>{{__("labels.no")}}</th>
                                     <th>Username</th>                             
                                     <th>Email</th>
                                     <th>Mobile No</th>
                                     <th>Status</th>
                                     <th>Create At</th>
                                     <th>{{__("labels.action")}}</th>
                                 </tr>
                             </thead>
                         </table>
                     </div>
                </div>
            </div>
        </div>
    </section> --}}

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">{{__("labels.user.list")}}</h4>
            <div class="table-responsive">
                <table class="table table-striped" id="table_user">
                <thead>
                    <tr>
                        <th>{{__("labels.no")}}</th>
                        <th>Username</th>                             
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Status</th>
                        <th>Create At</th>
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

@section('page-script')
<script>
$(document).ready(function () {
    // DataTable for organization
    console.log( base_url + "/pins-request");
    if (document.getElementById("table_user")) {
        var table = $('#table_user').DataTable({
            processing: true,
            serverSide: true,
            order: [1, 'ASC'],
            dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12 col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            ajax: {
                url: base_url + "/pins-request",
                data: function (data) {
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user_name', name: 'user_name' },
                { data: 'email', name: 'email' },
                { data: 'mobile_number', name: 'mobile_number'},
                {
                    data: 'status',
                    name: 'status',
                    render: function (data, type, row) {
                        if (data === 'pending') {
                            return '<span class="badge badge-warning">' + data + '</span>';
                        } else if (data === 'completed') {
                            return '<span class="badge badge-success">' + data + '</span>';
                        } else {
                            return data;
                        }
                    }
                },
                { data: 'req_created_at', name: 'req_created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            drawCallback: function (settings) {
                // Enable/disable pins input and event checkboxes for all rows
                enableDisableInputs(true);
            }
        });
    }
});

</script>
@endsection
