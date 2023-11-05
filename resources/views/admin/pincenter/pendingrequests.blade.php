@extends('layouts/common_template')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12">
        <div class="card">
                <div class="page-title d-flex justify-content-between align-items-center">
                    <h4>
                        Pending Requests
                    </h4>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <div id="table_user_wrapper" class="dataTables_wrapper no-footer">
                        <table class="table table-striped dataTable no-footer mt-0 py-0" id="table_Requests" aria-describedby="table_user_info">
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Qty</th>
                                    <th>Date/Time</th>
                                    <th>Type(In/Out)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                                @foreach ($requestedPins as $request)
                                <tr>
                                    <td>{{$i }}</td>
                                    <td>{{ $request->no_of_pin }}</td>
                                    <td>{{ date('d-m-Y',strtotime($request->req_created_at)) }}</td>
                                    @if ($request->status == 'pending')                                                            
                                    <td>Out</td>
                                    <td><a href="#" data-target="#" data-toggle="modal" class="btn btn-warning btn-sm">Pending</a></td>
                                    @else
                                    <td>In</td>
                                    <td><a href="#" data-target="#" data-toggle="modal" class="btn btn-success btn-sm">Received</a></td>
                                    @endif
                                </tr>
                                @php $i++; @endphp
                                @endforeach
                            </tbody>
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
    $(document).ready(function() {
        // DataTable for organization
        if (document.getElementById("table_Requests")) {
            var table = $('#table_Requests').DataTable({
                processing: true,
                //serverSide: true,
                bLengthChange: false,
                responsive: true,
                /* order: [1, 'ASC'],
                ajax: {
                    url: base_url + "/pin_center",
                    data: function(data) {}
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile_number',
                        name: 'mobile_number'
                    },
                    // {data: 'pins', name: 'pins', orderable: false, searchable: false},
                    // {data: 'event', name: 'event', orderable: false, searchable: false},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function(settings) {
                    // Enable/disable pins input and event checkboxes for all rows
                    enableDisableInputs(true);
                } */
            });
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
