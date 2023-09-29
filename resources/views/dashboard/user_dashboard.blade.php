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
                    <h5><b>Announcements</b></h5>
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
                <div class="pinBal mb-3 d-flex align-items-center justify-content-space-between">
                    <h5>ƀPIN Balance</h5>
                  <div class="info">
                    <p>Total</p>
                    <h3>{{$data['myPinBalance']}}</h3>
                  </div>
                </div>
                <div class="quota-timer">
                  Today’s Quota is Full. Please try after <p id="demo"></p>
                  <script>
                    // Set the date we're counting down to
                    var countDownDate = new Date("Sep 30, 2023 10:00:00").getTime();
                    // Update the count down every 1 second
                    var x = setInterval(function() {
                      // Get today's date and time
                      var now = new Date().getTime();
                      // Find the distance between now and the count down date
                      var distance = countDownDate - now;
                      // Time calculations for days, hours, minutes and seconds
                      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60) + (days * 24));
                      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                      // Output the result in an element with id="demo"
                      document.getElementById("demo").innerHTML = hours + "H : "
                      + minutes + "M : " + seconds + "S";
                      // If the count down is over, write some text 
                      if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("demo").innerHTML = "EXPIRED";
                      }
                    }, 1000);
                  </script>
                </div>
                <!-- <a href="#" class="floating-btn">Create Id<span>+</span></a>
                <div class="heading d-flex align-items-center justify-content-between">
                  <h3>Referral Code</h3>
                </div> -->
                <div class="refForm mb-4">
                  <div class="affilates d-flex justify-content-between px-3 py-3">
                    <h4>Total Affiliates</h4>
                    <h3>300</h3>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="p-3 ref-code">
                        <p>My Referral Code</p>
                        <h3>{{$data['myadminSlug']}}</h3>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="p-3 sys-code">
                        <p>System Access Code</p>
                        <h3>XYZ</h3>
                      </div>
                    </div>
                  </div>
                  <div class="btn-group d-flex">
                      <button type="button" class="input-group-text copyBtn" id="idcopy" onclick="copyText('{{$data['cryptUrl']}}')}}')"><i data-feather="copy"></i>Copy</button>
                      <button type="button" class="input-group-text copyBtn" id="idshare"><i data-feather="share-2"></i>Share</button>
                  </div>
                </div>
                <a href="#" class="income">
                  <h4><svg class="svg-icon" style="vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M933.034667 494.933333h-17.066667v-119.466666a51.2 51.2 0 0 0-51.2-51.2h-38.4l-19.797333-74.069334a51.2 51.2 0 0 0-62.634667-36.181333l-18.773333 5.12L699.733333 165.717333a51.2 51.2 0 0 0-68.266666-24.746666L237.738667 324.266667h-21.504a51.2 51.2 0 0 0-51.2 51.2v191.829333a164.864 164.864 0 0 0 68.266666 314.88h631.466667a51.2 51.2 0 0 0 51.2-51.2v-119.466667h17.066667a17.066667 17.066667 0 0 0 17.066666-17.066666V512a17.066667 17.066667 0 0 0-17.066666-17.066667z m-286.72-324.266666a17.066667 17.066667 0 0 1 22.698666 8.192l22.528 48.469333-155.306666 41.642667a17.066667 17.066667 0 1 0 8.874666 33.109333l207.701334-55.637333a17.066667 17.066667 0 0 1 20.821333 11.946666l17.066667 65.194667H318.464zM102.4 716.8a130.901333 130.901333 0 1 1 130.901333 130.730667A131.072 131.072 0 0 1 102.4 716.8z m779.434667 51.2H570.709333a17.066667 17.066667 0 0 0 0 34.133333h311.125334v28.330667a17.066667 17.066667 0 0 1-17.066667 17.066667H333.312A161.962667 161.962667 0 0 0 374.101333 802.133333h46.933334a17.066667 17.066667 0 0 0 0-34.133333h-31.061334a164.864 164.864 0 0 0-156.672-216.234667 159.744 159.744 0 0 0-34.133333 3.584V375.466667a17.066667 17.066667 0 0 1 17.066667-17.066667h648.533333a17.066667 17.066667 0 0 1 17.066667 17.066667v119.466666H705.365333a62.464 62.464 0 0 0-62.464 62.464V648.533333a62.464 62.464 0 0 0 62.464 62.464h176.469334z m34.133333-91.136H705.365333A28.330667 28.330667 0 0 1 677.034667 648.533333v-90.624A28.330667 28.330667 0 0 1 705.365333 529.066667h210.602667z" fill="#3D3D63" /><path d="M728.234667 620.544h25.6a17.066667 17.066667 0 0 0 0-34.133333h-25.6a17.066667 17.066667 0 0 0 0 34.133333zM500.565333 768H477.866667a17.066667 17.066667 0 0 0 0 34.133333h22.698666a17.066667 17.066667 0 0 0 0-34.133333zM242.517333 792.576l1.877334 5.290667a17.066667 17.066667 0 0 0 16.042666 11.264 17.066667 17.066667 0 0 0 5.802667-1.024 17.066667 17.066667 0 0 0 10.24-21.845334l-2.389333-6.826666A47.616 47.616 0 0 0 290.133333 761.173333a45.226667 45.226667 0 0 0 1.536-34.133333 45.738667 45.738667 0 0 0-58.88-27.306667l-10.922666 3.413334a11.434667 11.434667 0 0 1-7.68-20.48l21.504-7.850667a17.066667 17.066667 0 1 0 32.085333-11.605333 34.133333 34.133333 0 0 0-17.066667-19.285334 34.133333 34.133333 0 0 0-26.112-1.194666L221.866667 636.586667a17.066667 17.066667 0 1 0-32.085334 11.605333l2.56 6.997333a45.397333 45.397333 0 0 0 41.301334 80.042667l10.581333-3.925333a11.434667 11.434667 0 0 1 14.677333 6.826666 11.093333 11.093333 0 0 1 0 8.704 10.581333 10.581333 0 0 1-6.314666 5.802667l-21.504 7.850667a17.066667 17.066667 0 1 0-31.914667 11.776 34.133333 34.133333 0 0 0 31.914667 22.357333 34.133333 34.133333 0 0 0 11.776-2.048z" fill="#3D3D63" /></svg>
                  Total Income</h4>
                  <h1><sup>₹</sup>20000</h1>
                  </a>
                <div class="row flex-grow mb-3">
                  <div class="col-12 pb-3">
                    <div class="card card-orange stat-card">
                      <div class="card-body">
                            <div class="statistics-details">
                              <!-- <img src="images/pending.png" alt="" class="img-fuild" /> -->
                              <div class="row">
                                <a href="#" class="col-6">
                                  <p class="statistics-title pt-0">Completed SH</p>
                                  <h3 class="rate-percentage">68</h3>
                                </a>
                                <a href="#" class="col-6">
                                  <p class="statistics-title pt-0">Pending SH</p>
                                  <h3 class="rate-percentage">68</h3>
                                </a>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 pb-3">
                    <div class="card card-green stat-card">
                      <div class="card-body">
                            <div class="statistics-details">
                              <!-- <img src="images/pending.png" alt="" class="img-fuild" /> -->
                              <div class="row">
                                <a href="#" class="col-6">
                                  <p class="statistics-title pt-0">Completed GH</p>
                                  <h3 class="rate-percentage">68</h3>
                                </a>
                                <a href="#" class="col-6">
                                  <p class="statistics-title pt-0">Pending GH</p>
                                  <h3 class="rate-percentage">68</h3>
                                </a>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @if(count($data['myReferalUser']))
                <div class="heading d-flex align-items-center justify-content-between">
                  <h3>Direct Ref Users</h3>
                  <a href="{{url('/normal_user')}}" class="btn btn-view">View All</a>
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
                      @foreach($data['myReferalUser'] AS $key => $ref)
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
                
                <!-- <div class="card">
                  <div class="card-body">
                    <div class="heading pt-0 d-flex align-items-center justify-content-between">
                      <h3 class="pt-0">bPIN Details</h3>
                    </div>
                    <div class="row flex-grow pin-details">
                      <div class="col-6">
                          <p>No. of bPIN Transferred</p>
                          <h3>{{$data['pinTransferSend']}}</h3>
                      </div>
                      <div class="col-6 bdr-left">
                        <p>No. of bPIN Requested</p>
                        <h3>{{$data['pinTransferRequest']}}</h3>
                      </div>
                    </div>
                  </div>
                </div> -->
                <?php
                    date_default_timezone_set('Asia/Kolkata');
                    $slo = '';
                    $time = date("H");
                    $timezone = date("e");
                    if($time >= "10" && $time <= "16")
                    {   $slo = "10";}
                    else 
                    {  $slo = "not_login";}
                    ?>
                    
                @if($slo != 'not_login')
                  <a href="{{url('/normal_user')}}" class="floating-btn">Create ID<span>+</span></a>
                @endif  
            </div>
          </div>
        </div>
@endsection
@section('page-script')
@endsection