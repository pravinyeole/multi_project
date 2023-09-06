@extends('layouts/common_template')

@section('title', 'Dashboard')

@section('vendor-style')
@endsection
@section('page-style')


@endsection

@section('content')
<div class="content-wrapper mobile-wrap">
  <section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <!-- Sales Card -->
                <div class="col-xxl-4 col-md-4" style="padding: 10px;">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Admin <!-- <span>| Today</span> --></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$activeAdmin}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Sales Card -->
                <div class="col-xxl-4 col-md-4" style="padding: 10px;">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title"> Total Users <!-- <span>| This Month</span> --></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$activeUsers}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4" style="padding: 10px;">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Pending PIN Request</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$pinReuqest}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4" style="padding: 10px;">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Today's Total Users</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$todaysUsers}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4" style="padding: 10px;">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Total One Week Users</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{$weekUsers}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4" style="padding: 10px;">
                  <div class="card info-card customers-card">
                      <div class="card-body">
                          <h5 class="card-title">Total Pin Genrated</h5>
                          <div class="d-flex align-items-center">
                              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                  <i class="bi bi-people"></i>
                              </div>
                              <div class="ps-3">
                                  <h6>{{$activeAdmin}}</h6>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
        </div>
    </div>
  </section>
</div>

{{-- <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
                    <div class="row">
                      <div class="col-lg-12 d-flex flex-column">
                        <div class="row flex-grow">
                          <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                    <div class="statistics-details d-flex align-items-center justify-content-between">
                                      <div>
                                        <p class="statistics-title">Total Admin</p>
                                        <h3 class="rate-percentage">{{$activeAdmin}}</h3>
                                      </div>
                                      <div>
                                        <p class="statistics-title">Total Users</p>
                                        <h3 class="rate-percentage">{{$activeUsers}}</h3>
                                      </div>
                                      <div>
                                        <p class="statistics-title">Total Pending PIN Request </p>
                                        <h3 class="rate-percentage">{{$pinReuqest}}</h3>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-content tab-content-basic">
                      <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
                        <div class="row">
                          <div class="col-lg-12 d-flex flex-column">
                            <div class="row flex-grow">
                              <div class="col-12 grid-margin stretch-card">
                                <div class="card card-rounded">
                                  <div class="card-body">
                                      <div class="statistics-details d-flex align-items-center justify-content-between">
                                        <div>
                                          <p class="statistics-title">Today's Total Users</p>
                                          <h3 class="rate-percentage">{{$todaysUsers}}</h3>
                                        </div>
                                        <div>
                                          <p class="statistics-title">Total One Week Users </p>
                                          <h3 class="rate-percentage">{{$weekUsers}}</h3>
                                        </div>
                                        <div>
                                          <p class="statistics-title">Total Pin Genrated</p>
                                          <h3 class="rate-percentage">{{$activeAdmin}}</h3>
                                        </div>
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
@endsection



@section('page-script')
@endsection