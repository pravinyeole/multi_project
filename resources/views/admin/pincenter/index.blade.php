@extends('layouts/common_template')

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="bpin-balance">
        <h2>{{$getNoOfPins}}</h2>
        <p>Total ƀPIN Balance</p>
    </div>
    <div class="note mb-4">
        <div class="affilates d-flex justify-content-between px-0 py-3">
        <h4>Request ƀPINs</h4>
        </div>
        <form id="requestPinForm" action="{{ route('request-pin.send-request') }}" method="POST">
        @csrf
            <div class="row">
            <div class="col-8 col-lg-8 pe-0">
                <input type="hidden" name="admin_slug" value="{{$adminAssingToLoginUser->admin_slug ?? ''}}">
                <div class="form-group m-0">
                    <input type="number" class="form-control" name="no_of_pin_requested" id="requestBpin" placeholder="Enter No. Of ƀPINs" required/>
                </div>
            </div>
            <div class="col-4 btn-group pt-0">
            <button type="submit" class="input-group-text copyBtn w-100 flex-100"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key menu-icon"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg> Request</button>
        </form>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 pb-4">
            <div class="card">
                <div class="page-title d-flex justify-content-between align-items-center">
                    <h4>
                        Pending Requests
                    </h4>
                    <a href="pins-request/pendingrequests" class="btn btn-sm btn-success">View All</a>
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
                                    @if($request->requesttype == 'inpin')
                                        <td>IN</td>
                                        <td><a href="#" data-target="#" data-toggle="modal" class="btn btn-warning btn-sm">Pending</a></td>
                                    @elseif($request->requesttype == 'outpin')
                                        <td>OUT</td>
                                        <td><a href="#" data-target="#" data-toggle="modal" class="btn btn-warning btn-sm">Pending</a></td>
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
    <div class="note mb-4">
        <div class="affilates d-flex justify-content-between px-0 py-3">
        <h4>Send ƀPINs</h4>
        </div>
        @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif
        <form  action="{{url('/transferpin/transsubmit_everyone')}}" method="POST">
            @csrf
            <div class="row">
            
                <div class="col-4 col-lg-4 pe-0">
                    <div class="form-group m-0">
                        <input type="number" class="form-control" name="mobile_no" id="mobile_no" placeholder="Enter Mobile No." required/>
                        <input type="hidden" name="current_bpin" value="{{$getNoOfPins}}">
                    </div>
                </div>
                <div class="col-4 col-lg-4 pe-0">
                    <div class="form-group m-0">
                        <input type="number" class="form-control" name="requestBpin" id="requestBpin" placeholder="No. of ƀPIN" required/>
                    </div>
                </div>
                <div class="col-4 btn-group pt-0">
                <button type="submit" class="input-group-text copyBtn w-100 flex-100"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> Send</button>
            
            </div>
        </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="page-title d-flex justify-content-between align-items-center">
                    <h4>
                        ƀPINs Transaction History
                    </h4>
                    <a href="pins-request/transactionhistory" class="btn btn-sm btn-success">View All</a>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <div id="table_user_wrapper" class="dataTables_wrapper no-footer">
                        <table class="table table-striped dataTable no-footer mt-0 py-0" id="table_Requests" aria-describedby="table_user_info">
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>User Name</th>
                                    <th>ƀPIN Qty</th>
                                    <th>Trxn Type</th>
                                    <th>Date Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tarnsferHistory as $k=>$v)
                                <tr>
                                    <td>{{$k + 1}}</td>
                                    <td><h6 class="m-0 p-0 font-weight-bold pb-2">{{$v->user_fname.' '.$v->user_lname}}</h6>{{$v->mobile_number}}</td>
                                    <td>{{$v->trans_count}}</td>
                                    <td><span class="text-danger font-weight-bold">Dr.</span></td>
                                    <td>{{date('d F Y',strtotime($v->created_at))}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Update Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form>
        <div class="modal-body py-0">
            <div class="alert alert-success note" role="alert">
                <h4 class="alert-heading">Note</h4>
                <p>Kindly send ₹500 to below user and share payment screenshot with the user directly.</p>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="fname" aria-describedby="fname" placeholder="Firat Name" required="">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="lname" aria-describedby="lname" placeholder="Last Name" required="">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <input type="number" class="form-control" id="mnumber" aria-describedby="mnumber" placeholder="Mobile Number" required="">
                        <a href="#" class="copy-btn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Copy</a>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                    <input type="email" class="form-control" id="email1" aria-describedby="emailHelp" placeholder="Email">
                    </div>
                </div>
                <div class="col-12">
                <label class="d-block font-weight-bold mb-2">Payment Method</label>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="d-block" for="Gpay"><img src="http://localhost:8000/images/Google-Pay-logo.png" alt="" class="img-fuild" style="max-height:25px;"></label>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <label class="switch" for="Gpay">
                        <input type="radio" name="payment" id="Gpay">
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="d-block" for="PhonePe"><img src="http://localhost:8000/images/phonePe.png" alt="" class="img-fuild" style="max-height:28px;"></label>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <label class="switch" for="PhonePe">
                        <input type="radio" name="payment" id="PhonePe">
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="d-block" for="PayTM"><img src="http://localhost:8000/images/paytm_logo.png" alt="" class="img-fuild" style="max-height:22px;"></label>
                    </div>
                </div>
                <div class="col-6 text-end">
                    <label class="switch" for="PayTM">
                        <input type="radio" name="payment" id="PayTM">
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="utrnumber" aria-describedby="utrnumber" placeholder="Transaction ID / UTR No." required="">
                        <a href="#" class="copy-btn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Copy</a>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="Comments" aria-describedby="Comments" placeholder="Comments" required="">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-start">
          <button type="submit" class="btn btn-secondary w-50 m-0 b-r-r-0 waves-effect waves-float waves-light" data-dismiss="modal" aria-label="Close">Cancel</button>
          <button type="submit" class="btn btn-success w-50 m-0 b-l-r-0 waves-effect waves-float waves-light">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $(document).ready(function() {
        // DataTable for organization
        if (document.getElementById("table_user")) {
            var table = $('#table_user').DataTable({
                processing: true,
                serverSide: true,
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