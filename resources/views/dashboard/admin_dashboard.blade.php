@extends('layouts/common_template')

@section('title', 'Dashboard')

@section('vendor-style')
@endsection
@section('page-style')


@endsection

@section('content')
<div class="content-wrapper mobile-wrap">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="announsement">
                  <div class="info">
                    <h5><b>Announcement Section</b></h5>
                    @if(isset($data['Announcement']))
                      @if(strtotime(date("Y-m-d")) >= strtotime($data['Announcement']['start_time']) || strtotime(date("Y-m-d")) >= strtotime($data['Announcement']['end_time']))
                        <p>{{$data['Announcement']['announce']}}</p>
                      @else
                        <p>No new Announcement</p>
                      @endif
                    @else
                      <p>No new Announcement</p>
                    @endif
                  </div>
                  <img src="images/announce.png" alt="" class="img-fuild" />
                </div>
                <div class="users mb-3 d-flex flex-direction-column">
                  <div class="card card-orange">
                    <div class="card-body">
                      <i data-feather="users"></i>
                      <h5>No of Users Registers</h5>
                      <h3><a href="{{url('request-pin/direct_ref_user_list')}}">{{$data['myReferalUser']}}</a></h3>
                    </div>
                  </div>
                </div>
                <div class="pinBal mb-3 d-flex align-items-center justify-content-space-between">
                    <h5>bPIN Balance</h5>
                  <div class="info">
                    <p>Total</p>
                    <h3>{{$data['myPinBalance']}}</h3>
                  </div>
                </div>
                <div class="row flex-grow mb-3">
                  <div class="col-6 pb-3">
                    <div class="card card-orange stat-card">
                      <div class="card-body">
                            <div class="statistics-details d-block">
                              <img src="images/pending.png" alt="" class="img-fuild" />
                                <p class="statistics-title">Total Pending SH</p>
                                <h3 class="rate-percentage">{{$sendHelpData}}</h3>
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-6 pb-3">
                    <div class="card card-green stat-card">
                      <div class="card-body">
                            <div class="statistics-details d-block">
                              <img src="images/approved.png" alt="" class="img-fuild" />
                                <p class="statistics-title">Total Pending GH</p>
                                <h3 class="rate-percentage">{{$compltesendHelpData}}</h3>
                            </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
                @if(isset($data['myReferalUser_list']) && count($data['myReferalUser_list']))
                <div class="heading d-flex align-items-center justify-content-between">
                  <h3>Direct Ref Users</h3>
                  <a href="{{url('/request-pin/direct_ref_user_list')}}" class="btn btn-view">View All</a>
                </div>
                <div class="trans-table dashboard-table pb-4">
                  <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>Mobile No.</th>
                        <th>Creation Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data['myReferalUser_list'] AS $key => $ref)
                      <tr>
                        <td>{{ucfirst($ref->user_fname.' '.$ref->user_lname)}}</td>
                        <td>{{$ref->mobile_number}}</td>
                        <td>{{$ref->created_at}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @endif
                <div class="heading d-flex align-items-center justify-content-between">
                  <h3>Referral Code</h3>
                </div>
                <div class="refForm mb-4">
                  <form action="#">
                  <div class="input-group">
                    <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="" value="{{$data['myadminSlug']}}" readonly>
                    <div class="input-group-prepend">
                      <button type="button" class="input-group-text copyBtn" id="idcopy" onclick="copyText('{{$data['cryptUrl']}}')">Copy</button>
                    </div>
                  </div>
                  </form>
                </div>
                
                <div class="card">
                  <div class="card-body">
                    <!-- <div class="heading pt-0 d-flex align-items-center justify-content-between">
                      <h3 class="pt-0">bPIN Details</h3>
                    </div> -->
                    <div class="row flex-grow pin-details pt-0">
                      <div class="col-6">
                      <a href="{{url('request-pin')}}"><p>Request Sent Count</p>
                          <h3>{{$data['requestedPins']}}</h3></a>
                      </div>
                      <div class="col-6 bdr-left">
                        <a href="{{url('superadmin/revokepin')}}"><p>Revoke Pins</p>
                        <h3>{{$data['revokePins']}}</h3></a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
@endsection



@section('page-script')
@endsection