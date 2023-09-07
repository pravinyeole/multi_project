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
            <button class="btn-sm create-new btn btn-info m-2">Pins: {{Session::get('myPinBalance')}}</button>
            <div class="card">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Send Help (SP) for {{$mobileId ?? ' '}}
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
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
                    <!-- @if(empty($sendHelpData)) -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Get Help (GH) for {{$mobileId ?? ' '}}
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
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
                    <!-- @endif -->
                </div>
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