@extends('layouts/common_template')

@section('content')

<style>
    .footer {
        padding: 0
    }
</style>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="page-title">
                    <h4>
                        Get Help (GH)
                    </h4>
                </div>
                <div class="card-body gray-bg">
                    <div class="title tbl-heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg> GH1</div>
                    <div class="table-responsive">
                        <table class="table table-hover responsive nowrap" style="width:100%" id="table_user">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>User ID</th>
                                    <th>Timestamp</th>
                                    <th>Status</th>
                                    <!-- <th>{{__("labels.action")}}</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($getHelpData))
                                @foreach($getHelpData AS $key => $gh)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>
                                        <div class="">
                                            <a href="#update" data-target="#update" data-toggle="modal" class="link">{{$gh->user_fname}} {{$gh->user_lname}}
                                                <p class="text-muted mb-0">M: {{$gh->mobile_number}}</p>
                                            </a>
                                        </div>
                                    </td>
                                    <td>25 Apr</td>
                                    <td><a href="#update" data-target="#update" data-toggle="modal" class="btn btn-warning btn-sm">Pending</a></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- <div class="title tbl-heading mt-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg> GH2</div>
                    <div class="table-responsive">
                        <table class="table table-hover responsive nowrap" style="width:100%" id="table_user2">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>User ID</th>
                                    <th>Timestamp</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Approve / Reject Help</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body py-0">
                    <div class="alert alert-success note" role="alert">
                        <h4 class="alert-heading">Note</h4>
                        <p>Kindly verify if the payment is received for below details. This cannot be reversed once approved.</p>
                    </div>
                    <div class="row info">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="d-block font-weight-bold">Name</label>
                                <h4>First Last Name</h4>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="d-block font-weight-bold">Mobile</label>
                                <h4>7777777777</h4>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="d-block font-weight-bold">Email</label>
                                <h4>info@domain.com</h4>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="d-block font-weight-bold">Payment Method</label>
                                <h4><img src="{{asset('images/Google-Pay-logo.png')}}" alt="" class="img-fuild" style="max-width:80px;" /></h4>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="d-block font-weight-bold">Transaction ID / UTR No.</label>
                                <h4>ABCB0000012456</h4>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="d-block font-weight-bold">Comments</label>
                                <p>test comment</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-start">
                    <button type="submit" class="btn btn-danger w-50 m-0 b-r-r-0">Reject</button>
                    <button type="submit" class="btn btn-success w-50 m-0 b-l-r-0">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function() {
        // DataTable for organization
        if (document.getElementById("table_user")) {
            var table = $('#table_user').DataTable({
                processing: true,
                bLengthChange: false,
                responsive: true,
                order: [],
                pageLength: 5,
                responsive: true,

                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ]
            });

            $(".dataTables_filter input")
                .attr("placeholder", "Search here...")
                .css({
                    width: "300px",
                    display: "inline-block"
                });

            $('[data-toggle="tooltip"]').tooltip();
        };
        if (document.getElementById("table_user2")) {
            var table = $('#table_user2').DataTable({
                processing: true,
                bLengthChange: false,
                responsive: true,
                order: [],
                pageLength: 5,
                responsive: true,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ]
            });

            $(".dataTables_filter input")
                .attr("placeholder", "Search here...")
                .css({
                    width: "300px",
                    display: "inline-block"
                });

            $('[data-toggle="tooltip"]').tooltip();
        };
        $("#table_user_processing").hide();
        // Enable/disable pins input and event checkbox based on checkbox click
        $(document).on('change', '.event-checkbox', function() {
            var isChecked = $(this).prop('checked');
            if ($(this).closest('tr').find('.pins-input').val().length === 0) {
                isChecked = false;
            }
            $(this).closest('tr').find('.pins-input').prop('disabled', !isChecked);
        });

        // Enable/disable event checkboxes based on pins input
        $(document).on('input', '.pins-input', function() {
            var pinsInput = $(this);
            var isChecked = pinsInput.val().length > 0;
            pinsInput.closest('tr').find('.event-checkbox').prop('disabled', !isChecked);
        });

        // Enable/disable pins input and event checkboxes for all rows
        function enableDisableInputs(enable) {
            var rows = table.rows().nodes().to$();
            rows.each(function() {
                var pinsInput = $(this).find('.pins-input');
                var eventCheckbox = $(this).find('.event-checkbox');
                var isChecked = pinsInput.val().length > 0;
                eventCheckbox.prop('disabled', !isChecked);
                pinsInput.prop('disabled', !enable || isChecked);
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