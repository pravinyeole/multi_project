@extends('layouts/common_template')

@section('title', 'Dashboard')

@section('vendor-style')
@endsection
@section('page-style')


@endsection

@section('content')
<div class="content-wrapper">
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
                                        <h3 class="rate-percentage">32.53%</h3>
                                        <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p>
                                      </div>
                                      <div>
                                        <p class="statistics-title">Total Users</p>
                                        <h3 class="rate-percentage">7,682</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
                                      </div>
                                      <div>
                                        <p class="statistics-title">Total Pending PIN Request </p>
                                        <h3 class="rate-percentage">68.8</h3>
                                        <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
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
                                          <h3 class="rate-percentage">2m:35s</h3>
                                          <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                                        </div>
                                        <div>
                                          <p class="statistics-title">Total One Week Users </p>
                                          <h3 class="rate-percentage">68.8</h3>
                                          <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                                        </div>
                                        <div>
                                          <p class="statistics-title">Total Pin Genrated</p>
                                          <h3 class="rate-percentage">2m:35s</h3>
                                          <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
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
        </div>
@endsection



@section('page-script')
@endsection