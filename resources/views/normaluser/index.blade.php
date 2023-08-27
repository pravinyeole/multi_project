@extends('layouts/common_template')

{{-- @section('title', $title) --}}

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
<div class="content-wrapper">
    <div class="row">
        @if (Session::has('alert'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert">
                <i class="fa fa-times"></i>
            </button>
            <strong>Success !</strong> {{ session('alert') }}
        </div>
        @endif
        <div class="col-12">
            <div class="card" style="padding: 10px;">
                <div class="card-header border-bottom">
                    <div>
                        <div style="float:left;margin-top:-20px;">
                            <h4 class="card-title">
                                <p class="m-1">Pins: <span class="text-primary">{{ $userDetails->pins ?? '' }}</span></p>
                            </h4>
                        </div>
                        <div style="float:right">
                            <div class="dt-action-buttons text-right">
                                <div class="dt-buttons d-inline-flex">
                                    @if(Auth::user()->user_role != "S")
                                    <?php
                                    date_default_timezone_set('Asia/Kolkata');
                                    $slo = '';
                                    $time = date("H");
                                    $timezone = date("e");
                                    
                                    if($time >= "10" && $time <= "16")
                                    {
                                        $slo = "10";
                                    }
                                    else 
                                    {
                                        $slo = "not_login";
                                    }
                                    ?>
                                    
                                    {{-- @if($timer == 'start' || !empty($timer)) --}}
                                        @if($slo != 'not_login')
                                            <button class="dt-button create-new btn btn-primary {{$createIdLimit ?? ''}}" tabindex="0" type="button" data-toggle="modal" data-target="#modals-slide-in">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus mr-50 font-small-4">
                                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    </svg>
                                                    Create Id
                                                </span>
                                            </button>
                                        @endif
                                    {{-- @endif --}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-datatable">
                    <table class="table table-striped table-bordered" id="table_user">
                        <thead>
                            <tr>
                                <th>{{ __('labels.no') }}</th>
                                <th>User ID</th>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>{{ __('labels.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="modals-slide-in" tabindex="-1" role="dialog" aria-labelledby="exampleModalSlideLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalSlideLabel">Please Wait</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start">
                    <div>
                        <p class="msg-date text-black mb-0">
                            Are you sure want to create Id ?
                        <div id="timer">1:00</div>
                        </p>
                    </div>
                </div>
                <br>
                <div class="d-flex flex-column-reverse flex-md-row gap-20 justify-content-end">
                    <button type="button" class="btn btn-danger mb-2 " data-dismiss="modal">Cancel</button>
                    <form id="createIdForm" action="{{ route('normal_user.create_id') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $userDetails->id ?? '' }}">
                        <button type="submit" id="createButton" class="btn btn-primary mb-2">Create</button>
                    </form>
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
    $(document).ready(function() {
        //refresh the page at:59:55 AM daily
        // Calculate the time until 9:59:55 AM (in milliseconds)
        var now = new Date();
        var targetTime = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 9, 59, 55);
        if (targetTime <= now) {
            targetTime.setDate(targetTime.getDate() + 1); // Move to the next day
        }
        var timeUntilRefresh = targetTime - now;

        // Schedule the page refresh
        setTimeout(function() {
            location.reload();
        }, timeUntilRefresh);

        console.log(base_url);
        // DataTable for organization
        if (document.getElementById("table_user")) {
            var table = $('#table_user').DataTable({
                processing: true,
                serverSide: true,
                bLengthChange: false,
                responsive: true,
                order: [1, 'ASC'],
                // dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12  col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                ajax: {
                    url: base_url + "/normal_user",
                    data: function(data) {}
                },
                "columns": [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'mobile_id',
                        name: 'mobile_id'
                    },
                    {
                        data: 'id_created_date',
                        name: 'id_created_date'

                    },
                    {
                        data: 'id_status',
                        name: 'id_status'

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    var statusColumn = data['id_status'];

                    if (statusColumn === 'Yellow') {
                        $(row).find('td:eq(3)').css('color', 'yellow');
                    } else if (statusColumn === 'Green') {
                        $(row).find('td:eq(3)').css('color', 'green');
                    } else if (statusColumn === 'Gray') {
                        $(row).find('td:eq(3)').css('color', 'gray');
                    } else if (statusColumn === 'Red') {
                        $(row).find('td:eq(3)').css('color', 'red');
                    }
                }
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

    function startTimer() {
        var timerElement = $('#timer');
        var createButton = $('#createButton');
        var modal = $('#modals-slide-in');

        timerElement.text('1:00'); // Initial time
        createButton.hide();
        // modal.modal('show');

        var timeLeft = 05; // Time in seconds
        var timerInterval = setInterval(function() {
            timeLeft--;
            var minutes = Math.floor(timeLeft / 60);
            var seconds = timeLeft % 60;
            var timeString = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
            timerElement.text(timeString);

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                // timerElement.text('Time\'s up!');
                createButton.show();
            }
        }, 1000);
    }

    startTimer(); // Call the timer function to start the countdown

    $('#createButton').on('click', function() {
        $('#createIdForm').submit();
    });
</script>
@endsection