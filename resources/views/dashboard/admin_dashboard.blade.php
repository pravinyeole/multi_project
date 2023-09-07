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
                {{--<div class="d-sm-flex align-items-center justify-content-between border-bottom">
                   <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">Audiences</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Demographics</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab" aria-selected="false">More</a>
                    </li>
                  </ul>
                </div> --}}
                <div class="announsement">
                  <div class="info">
                    <h5>Welcome to <b>event name!</b></h5>
                    <p>Wishing you a warm welcome to all of our attendees/ sponsors! Be sure to create your profile to start networking.</p>
                  </div>
                  <img src="images/announce.png" alt="" class="img-fuild" />
                </div>
                <div class="users mb-3 d-flex flex-direction-column">
                  <div class="card card-orange">
                    <div class="card-body">
                      <i data-feather="users"></i>
                      <h5>No of Users Registers</h5>
                      <h3>510</h3>
                    </div>
                  </div>
                </div>
                <div class="pinBal mb-3 d-flex align-items-center justify-content-space-between">
                    <h5>bPIN Balance</h5>
                  <div class="info">
                    <p>Total</p>
                    <h3>510</h3>
                  </div>
                </div>
                <div class="row flex-grow mb-3">
                  <div class="col-6 pb-3">
                    <div class="card card-orange stat-card">
                      <div class="card-body">
                            <div class="statistics-details d-block">
                              <img src="images/pending.png" alt="" class="img-fuild" />
                                <p class="statistics-title">Total Pending SH</p>
                                <h3 class="rate-percentage">68</h3>
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
                                <h3 class="rate-percentage">68</h3>
                            </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="heading d-flex align-items-center justify-content-between">
                  <h3>Referral Code</h3>
                </div>
                <div class="refForm mb-4">
                  <form action="#">
                  <div class="input-group">
                    <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="" value="a521541" readonly>
                    <div class="input-group-prepend">
                      <div class="input-group-text" id="idcopy">Copy</div>
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
                          <p>bPIN Request Recieved</p>
                          <h3>15</h3>
                      </div>
                      <div class="col-6 bdr-left">
                        <p>Revoke Pins</p>
                        <h3>20</h3>
                      </div>
                    </div>
                  </div>
                </div>
                <a href="#" class="floating-btn">Create ID<span>+</span></a>
            </div>
          </div>
        </div>
@endsection



@section('page-script')
@endsection