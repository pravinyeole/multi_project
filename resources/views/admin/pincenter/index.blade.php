@extends('layouts/common_template')
<style>
    .d-none{display:none;}
</style>
@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="bpin-balance">
        <h2>{{$getNoOfPins}}</h2>
        <p>Total rPIN Balance</p>
    </div>
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>Error !</strong> {{ session('error') }}
        </div>
    @endif
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <strong>Success !</strong> {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="row flex-grow pin-details">
            <div class="col-6">
                <p>No. of rPIN Transferred</p>
                <h3>{{$data['pinTransferSend']}}</h3>
            </div>
            <div class="col-6 bdr-left">
                <p>No. of rPIN Used</p>
                <h3>{{$data['pinused']}}</h3>
            </div>
            <!-- <div class="col-4 bdr-left">
                <p>No. of rPIN Requested</p>
                <h3>{{$data['pinTransferRequest']}}</h3>
            </div> -->
            </div>
        </div>
    </div>
    <div class="note mb-4">
        <div class="affilates d-flex justify-content-between px-0 py-3">
        <h4>Send rPINs</h4>
        </div>
        @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif
        <form  action="{{url('/transferpin/transsubmit_everyone')}}" method="POST">
            @csrf
            <div class="row">
            
                <div class="col-5 col-lg-5 pe-0">
                    <div class="form-group m-0">
                        <input type="text" class="form-control" name="mobile_no" id="mobile_no" placeholder="Enter Mobile No." onkeypress="return validateNumber(event)" required/>
                        <input type="hidden" name="current_bpin" value="{{$getNoOfPins}}">
                    </div>
                </div>
                <div class="col-4 col-lg-4 pe-0">
                    <div class="form-group m-0">
                        <input type="text" class="form-control" name="requestBpin" id="requestBpin" placeholder="No. of rPIN" onkeypress="return validateNumber(event)" required/>
                    </div>
                </div>
                <div class="col-3 btn-group pt-0">
                <button type="submit" class="input-group-text copyBtn w-100 flex-100"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> Send</button>
            
            </div>
        </form>
        </div>
    </div>
    <!-- <div class="note mb-4">
        <div class="affilates d-flex justify-content-between px-0 py-3">
        <h4>Request rPINs</h4>
        </div>
        <form id="requestPinForm" action="{{ route('request-pin.send-request') }}" method="POST">
        @csrf
            <div class="row">
            <div class="col-8 col-lg-8 pe-0">
                <input type="hidden" name="admin_slug" value="{{$adminAssingToLoginUser->admin_slug ?? ''}}">
                <div class="form-group m-0">
                    <input type="text" class="form-control" name="no_of_pin_requested" id="requestBpin" placeholder="Enter Qty" onkeypress="return validateNumber(event)" required/>
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
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Date/Time</th>
                                    <th>Type(In/Out)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requestedPins as $k => $request)
                                    @php
                                    $cryptStr= Crypt::encryptString($request->pin_request_id);
                                    $cryptUrl= url('/request-pin/update_request_pin/').'/'.$cryptStr;
                                    @endphp
                                <tr>
                                    @if($request->requesttype == 'inpin')
                                        <td class="username_{{($k+1)}}" >{{'To Admin'}}</td>
                                    @elseif($request->requesttype == 'outpin')
                                        <td class="username_{{($k+1)}}" data-suburl="{{$cryptUrl}}">{{$request->user_name}}</td>
                                    @endif
                                    <td class="requestpin_{{($k+1)}}" >{{ $request->no_of_pin }}</td>
                                    <td class="requestdate_{{($k+1)}}">{{ date('d-m-Y',strtotime($request->req_created_at)) }}</td>
                                    @if($request->requesttype == 'inpin')
                                        <td>IN</td>
                                        <td><a href="javascript::void(0);" data-target="#" data-toggle="modal" class="btn btn-warning btn-sm">Pending</a></td>
                                    @elseif($request->requesttype == 'outpin')
                                        <td>OUT</td>
                                        <td><a href="#update" data-target="#" data-toggle="modal" class="btn btn-warning btn-sm" data-backdrop="static" data-keyboard="false" onclick="showrequest('{{($k+1)}}')">Pending</a></td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="page-title d-flex justify-content-between align-items-center">
                    <h4>
                        rPINs Transaction History
                    </h4>
                    <a href="pins-request/transactionhistory" class="btn btn-sm btn-success">View All</a>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <div id="table_user_wrapper" class="dataTables_wrapper no-footer">
                        <table class="table table-striped dataTable no-footer mt-0 py-0" id="table_Requests" aria-describedby="table_user_info">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>rPIN Qty</th>
                                    <th>Trxn Type</th>
                                    <th>Date Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tarnsferHistory as $k=>$v)
                                <tr>
                                    <td><h6 class="m-0 p-0 font-weight-bold pb-2">{{$v->user_fname.' '.$v->user_lname}}</h6>{{$v->mobile_number}}</td>
                                    <td>{{$v->trans_count}}</td>
                                    <td><span class="text-success font-weight-bold">Dr.</span></td>
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
        <h5 class="modal-title" id="exampleModalLabel">Update Request</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form class="requestPinSubmit" method="get" action="{{url('request-pin/update_request_pin/')}}" onsubmit="return confirm('Do you really want to approve this request?');">
        <div class="modal-body py-0">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label type="text" class="form-control requestByName" ></label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label type="text" class="form-control" >Pin Requested : <span class="requestCount">Aniket</span></label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label type="te xt" class="form-control" >Pin Requested Date : <span class="requestDate">Aniket</span></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-start">
          <button type="button" class="btn btn-secondary w-50 m-0 b-r-r-0 waves-effect waves-float waves-light" data-dismiss="modal" aria-label="Close">Cancel</button>
          <button type="submit" class="btn btn-success w-50 m-0 b-l-r-0 waves-effect waves-float waves-light">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <script>
    function showrequest(rid){
        $('.requestByName').text($('.username_'+rid).text());
        $('.requestCount').text($('.requestpin_'+rid).text());
        $('.requestDate').text($('.requestdate_'+rid).text());
        $('.requestPinSubmit').attr('action', $('.username_'+rid).attr('data-suburl'));
    }
    function validateNumber(e) {
        const pattern = /^[0-9]$/;

        return pattern.test(e.key )
    }
    </script>
@endsection
