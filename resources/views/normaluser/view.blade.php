@extends('layouts/common_template')

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
<section id="responsive-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">View</h4>
                    <div class="dt-action-buttons text-right">
                        <div class="dt-buttons d-inline-flex">
                            <p class="m-1">Pins: <span class="text-primary">{{$userDetails->pins ?? ''}}</span></p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="accordion" id="helpAccordion">
                        <div class="card-header" id="sendHelpHeading">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#sendHelpCollapse" aria-expanded="true" aria-controls="sendHelpCollapse">
                                    Send Help (SP) for {{$mobileId ?? ' '}}
                                </button>
                            </h5>
                        </div>
                        <input type="hidden" id="mobileId" name="mobileId" value="{{$mobileId}}">
                        <div class="card-datatable">
                            <table class="table table-striped table-bordered" id="send_help_table">
                                <thead>
                                    <tr>
                                        <th>{{__("labels.no")}}</th>
                                        <th>Username</th>
                                        <th>{{__("labels.action")}}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                @if(empty($sendHelpData))
                <div class="card-body">
                    <div class="accordion" id="helpAccordion">
                        <div class="card-header" id="getHelpHeading">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#sendHelpCollapse" aria-expanded="true" aria-controls="sendHelpCollapse">
                                    Get Help (GH) for {{$mobileId ?? ' '}}
                                </button>
                            </h5>
                        </div>
                        <div class="card-datatable">
                            <table class="table table-striped table-bordered" id="get_halp_table">
                                <thead>
                                    <tr>
                                        <th>{{__("labels.no")}}</th>
                                        <th>Username</th>
                                        <th>{{__("labels.action")}}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        console.log(base_url);

        // Databale for organization
        if (document.getElementById("send_help_table")) {
            var table = $('#send_help_table').DataTable({
                processing: true,
                serverSide: true,
                order: [1, 'ASC'],
                bLengthChange: false,
                responsive: true,
                // dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12  col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                ajax: {
                    url: base_url + "/normal_user/send_help",
                    data: function(d) {
                        d.mobileId = $('#mobileId').val(); // Set the value of mobileId
                    }
                },
                "columns": [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'cities.name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        }


        // Databale for organization
        if (document.getElementById("get_halp_table")) {
            var table = $('#get_halp_table').DataTable({
                processing: true,
                serverSide: true,
                order: [1, 'ASC'],
                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12  col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                ajax: {
                    url: base_url + "/normal_user/get_help",
                    data: function(d) {
                        d.mobileId = $('#mobileId').val(); // Set the value of mobileId
                    }
                },
                "columns": [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'cities.name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        }

        $('.addDepartment').on('submit', function(e) {
            if ($(".addDepartment").valid()) {
                $('#loader').show();
                return true;
            }
        });
        $('.editDepartment').on('submit', function(e) {
            if ($(".editDepartment").valid()) {
                $('#loader').show();
                return true;
            }
        });
    });
</script>
@endsection