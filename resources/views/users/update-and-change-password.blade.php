@extends('layouts/contentLayoutMaster')

@section('title', 'Account Settings')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel='stylesheet' href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
<!-- account setting page -->
<section id="page-account-settings">
  <div class="row">
    <!-- left menu section -->
    <div class="col-md-3 mb-2 mb-md-0">
      <ul class="nav nav-pills flex-column nav-left">
        <!-- general -->
        <li class="nav-item"><a class="nav-link active" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
            <i data-feather="user" class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">General</span>
          </a>
        </li>
        <!-- change password -->
        <li class="nav-item">
          <a class="nav-link" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false">
            <i data-feather="lock" class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">Change Password</span>
          </a>
        </li>
        <!-- information -->
        @if(Session::get('USER_TYPE') == 'O' && Auth::user()->is_role_assign == "N")
        <li class="nav-item">
          <a class="nav-link" id="account-pill-card" data-toggle="pill" href="#account-vertical-card" aria-expanded="false">
            <i data-feather="credit-card" class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">Card Details</span>
          </a>
        </li>

         <li class="nav-item">
          <a class="nav-link" id="account-pill-subscription" data-toggle="pill" href="#account-vertical-subscription" aria-expanded="false">
            <i data-feather="list" class="font-medium-3 mr-1"></i>
            <span class="font-weight-bold">Subscriptions</span>
          </a>
        </li>
        @endif

      </ul>
    </div>
    <!--/ left menu section -->

    <!-- right content section -->
    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <div class="tab-content">
            <!-- general tab -->
            <div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
              <!-- form -->
              <!-- <form class="update_profile" id="update_profile" action="{{url('users/update-profile')}}" method="post">  -->
                <form class="update_profile" id="update_profile"  >
                @csrf
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <div class="form-group">
                        <label class="form-label" for="basic-default-name">First Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="user_fname" value="{{$user->user_fname}}"  name="user_fname" placeholder="First Name"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <div class="form-group">
                        <label class="form-label" for="basic-default-name">Last Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="user_lname" value="{{$user->user_lname}}" name="user_lname" placeholder="Last Name"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <div class="form-group">
                        <label class="form-label" for="basic-default-email">Email<span class="text-danger">*</span></label>
                        <input type="text" id="email" name="email" readonly class="form-control" value="{{$user->email}}" placeholder="Email"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="row">
                        <div class="col-md-4 pr-0">
                            <div class="form-group">
                                <label class="form-label" for="user_country_code">Country Code<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" maxlength="3"  id="user_country_code" name="user_country_code" value="{{ $user->user_country_code }}" required placeholder="Country Code"  tabindex="1" disabled=""  />
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label" for="user_phone_no">Phone No<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" onkeypress="check_phone_format()"  id="user_phone_no" name="user_phone_no" value="{{ $user->user_phone_no }}"  placeholder="Phone No"  tabindex="1"  />
                            </div>
                        </div>
                    </div>
                  </div>

                  @if(\Session::get('USER_TYPE')=="U" )
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <div class="form-group">
                        <label class="form-label" for="basic-default-email">Team Name<span class="text-danger">*</span></label>
                        <textarea class="form-control" disabled>{{ $user->teamName }}</textarea>
                      </div>
                    </div>
                  </div>
                   @endif
                  @if(\Session::get('USER_TYPE')=="O" )
                  @if(\Auth::user()->is_role_assign == "N")
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label class="form-label" for="basic-default-name">Insurance Agency Name<span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="insurance_agency_name" value="{{$user->insurance_agencies->insurance_agency_name??''}}" name="insurance_agency_name" placeholder="Insurance Agency Name"/>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6 is_mfa">
                    <div class="form-group">
                    <label class="form-label" for="basic-default-name">MFA<span class="text-danger">*</span></label>
                    <select class="form-control" id="is_mfa"  name="is_mfa"  onchange="mfa_change()" required>
                        <option value="Y"  {{ $user->insurance_agencies->is_mfa == "Y" ? 'selected="selected"' : '' }}>Yes</option>
                        <option value="N"  {{ $user->insurance_agencies->is_mfa == "N" ? 'selected="selected"' : '' }}>No</option>
                    </select>
                  </div>
                  </div>
                  <div class="col-12 col-sm-6 mfa_type" style="<?php if( $user->insurance_agencies->is_mfa != "Y"){ echo 'display:none'; }  ?>">
                    <div class="form-group">
                    <label class="form-label" for="mfa_type">MFA Type<span class="text-danger">*</span></label>
                    <select class="form-control" id="mfa_type"  name="mfa_type" required>
                        <option value="both"  {{ $user->insurance_agencies->mfa_type == "both" ? 'selected="selected"' : '' }}>Both</option>
                        <option value="email" {{ $user->insurance_agencies->mfa_type == "email" ? 'selected="selected"' : '' }}>Email</option>
                        <option value="phone" {{ $user->insurance_agencies->mfa_type == "phone" ? 'selected="selected"' : '' }}>Phone</option>
                    </select>
                    </div>
                  </div>

                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label for="group_id">Select {{__("labels.organization.groups")}}</label>
                        <select class="form-control" id="group_id" name="group_id" disabled>
                            <option value="">Select</option>
                            @if(isset($groups) && count($groups) > 0)
                                @foreach($groups as $group)
                                    <option value="{{ $group->group_id }}" @if (isset($insurGroup->id) && $insurGroup->id != '' && $insurGroup->group_id == $group->group_id) selected @endif>{{ $group->group_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                    {{-- <div class="row col-md-12"> --}}
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="basic-default-contact_name">{{__("labels.organization.contact_name")}}<span class="text-danger">*</span></label>
                                <input type="text" id="contact_name" name="contact_name" class="form-control"  placeholder="Contact Name"  value="{{$user->insurance_agencies->contactinfo->contact_name??''}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="basic-default-contact_email">{{__("labels.organization.contact_email")}}<span class="text-danger">*</span></label>
                                <input type="email" id="contact_email" name="contact_email" class="form-control"  placeholder="Contact Email"  value="{{$user->insurance_agencies->contactinfo->contact_email??''}}" autocomplete="new-contact_email">
                            </div>
                        </div>
                    {{-- </div> --}}
                    {{-- <div class="row col-md-12"> --}}
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="basic-default-contact_no">{{__("labels.organization.contact_no")}}<span class="text-danger">*</span></label>
                                <input type="text" id="contact_no" maxlength="13" minlength="13" onkeypress="check_phone_format()" name="contact_no"  class="form-control" placeholder="Contact No"  value="{{$user->insurance_agencies->contactinfo->contact_no??''}}" autocomplete="off">
                            </div>
                        </div>
                    {{-- </div> --}}

                  @else
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                    <label class="form-label" for="basic-default-name">Insurance Agency Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" readonly id="insurance_agency_name" value="{{$user->get_org_name->insurance_agency_name??''}}" name="insurance_agency_name" placeholder="Insurance Agency Name"/>
                  </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                    <label class="form-label" for="basic-default-name">MFA<span class="text-danger">*</span></label>

                    <input type="hidden" class="form-control" id="is_mfa"  name="is_mfa" value="{{ $user->get_org_name->is_mfa }}">
                    <select class="form-control"  disabled required>
                        <option   {{ $user->get_org_name->is_mfa == "Y" ? 'selected="selected"' : '' }}>Yes</option>
                        <option   {{ $user->get_org_name->is_mfa == "N" ? 'selected="selected"' : '' }}>No</option>
                    </select>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                    <label class="form-label" for="mfa_type">MFA Type<span class="text-danger">*</span></label>
                    <input type="hidden" class="form-control" id="mfa_type"  name="mfa_type" value="{{ $user->get_org_name->is_mfa }}">
                    <select class="form-control" disabled required>
                        <option value="email" {{ $user->get_org_name->mfa_type == "email" ? 'selected="selected"' : '' }}>Email</option>
                        <option value="phone" {{ $user->get_org_name->mfa_type == "phone" ? 'selected="selected"' : '' }}>Phone</option>
                        <option value="both"  {{ $user->get_org_name->mfa_type == "both" ? 'selected="selected"' : '' }}>Both</option>
                    </select>
                    </div>
                  </div>

                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label for="group_id">Select {{__("labels.organization.groups")}}</label>
                        <select class="form-control" id="group_id" name="group_id" disabled>
                            <option value="">Select</option>
                            @if(isset($groups) && count($groups) > 0)
                                @foreach($groups as $group)
                                    <option value="{{ $group->group_id }}" @if (isset($insurGroup->id) && $insurGroup->id != '' && $insurGroup->group_id == $group->group_id) selected @endif>{{ $group->group_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                    {{-- <div class="row col-md-12"> --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="basic-default-contact_name">{{__("labels.organization.contact_name")}}<span class="text-danger">*</span></label>
                                <input type="text" id="contact_name" name="contact_name" class="form-control" readonly placeholder="Contact Name"  value="{{$user->insurance_agencies->contactinfo->contact_name??''}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="basic-default-contact_email">{{__("labels.organization.contact_email")}}<span class="text-danger">*</span></label>
                                <input type="email" id="contact_email" name="contact_email" class="form-control" readonly placeholder="Contact Email"  value="{{$user->insurance_agencies->contactinfo->contact_email??''}}" autocomplete="new-contact_email">
                            </div>
                        </div>
                    {{-- </div> --}}
                    {{-- <div class="row col-md-12"> --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="basic-default-contact_no">{{__("labels.organization.contact_no")}}<span class="text-danger">*</span></label>
                                <input type="text" id="contact_no" maxlength="14" minlength="14" name="contact_no" readonly class="form-control" placeholder="Contact No"  value="{{$user->insurance_agencies->contactinfo->contact_no??''}}" autocomplete="off">
                            </div>
                        </div>
                    {{-- </div> --}}
                  @endif
                  @endif
                  
                  @if (Session::get('USER_TYPE')=="T")
                  <div class="col-12 col-sm-6">
                    <label class="form-label" for="basic-default-email">Team Name<span class="text-danger">*</span></label>
                    <textarea class="form-control" disabled>{{ $user->teamName }}</textarea>
                  </div>
                  @endif
                  <div class="col-12">
                      <br>
                    <button type="submit" class="btn btn-primary" >{{__("labels.submit")}}</button>
                    <a href="{{url('users/profile')}}"> <button type="button" class="btn btn-danger" >{{__("labels.cancel")}}</button></a>
                  </div>
                </div>
              </form>
              <!--/ form -->
            </div>
            <!--/ general tab -->

            <!-- change password -->
            <div class="tab-pane fade" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
              <!-- form -->
              <form class="form" id="form" action="{{url('users/change-password')}}" method="post">
                @csrf
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-old-password">Old Password<span class="text-danger">*</span></label>
                      <div class="input-group form-password-toggle input-group-merge">
                        <input type="password" class="form-control" id="old_password" name="old_password" autocomplete="off" required placeholder="Old Password"/>
                        <div class="input-group-append">
                          <div class="input-group-text cursor-pointer">
                            <i data-feather="eye"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-new-password">New Password<span class="text-danger">*</span></label>
                      <div class="input-group form-password-toggle input-group-merge">
                        <input type="password" id="new_password" name="new_password" autocomplete="off" required class="form-control" placeholder="New Password"/>
                        <div class="input-group-append">
                          <div class="input-group-text cursor-pointer">
                            <i data-feather="eye"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                      <label for="account-retype-new-password">Confirm New Password<span class="text-danger">*</span></label>
                      <div class="input-group form-password-toggle input-group-merge">
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" autocomplete="off" required placeholder="Confirm New Password"/>
                        <div class="input-group-append">
                          <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary" >{{__("labels.submit")}}</button>
                    <a href="{{url('users/profile')}}"> <button type="button" class="btn btn-danger" >{{__("labels.cancel")}}</button></a>
                  </div>
                </div>
              </form>
              <!--/ form -->
            </div>
            <!--/ change password -->

            <!-- card details -->
            @if(Session::get('USER_TYPE') == 'O'  && Auth::user()->is_role_assign == "N")
            <div class="tab-pane fade" id="account-vertical-card" role="tabpanel" aria-labelledby="account-pill-card" aria-expanded="false">
                <h4 class="mb-1">Current Payment Method</h4>
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label for="account-old-password">Last 4 digit</label>
                        <div class="input-group form-password-toggle input-group-merge">
                          <input type="number" class="form-control" name="" autocomplete="off" value="{{$paymentMethod->card->last4}}" readonly="" />
                        </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label for="account-old-password">Brand</label>
                        <div class="input-group form-password-toggle input-group-merge">
                          <input type="text" class="form-control" name="" autocomplete="off" value="{{$paymentMethod->card->brand}}" readonly="" />
                        </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                        <a href="{{url('subscription/card')}}" class="btn btn-primary">Change Payment Method</a>
                  </div>
                </div>
            </div>

            <div class="tab-pane fade" id="account-vertical-subscription" role="tabpanel" aria-labelledby="account-pill-subscription" aria-expanded="false">
                <div class="row">
                  <table class="table-responsive table dataTable dtr-column collapsed">
                    <thead>
                      <tr>
                        <th>Plan Name</th>
                        <th>Type</th>
                        <th>Rate</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Period</th>
                        <!-- <th>Valid Till</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($subscriptions as $subscription)
                        <tr>
                          <td>{{$subscription->name}}</td>
                          <td>@if($subscription->name == "CoverageWizard Team Access Licenses")
                                User Seats
                              @else
                                Platform Subscription
                              @endif
                          </td>
                          <td>${{$subscription->amount}}</td>
                          <td>{{$subscription->quantity}}</td>
                          <td>${{$subscription->amount * $subscription->quantity}}</td>
                          <td>
                            @if($subscription->stripe_status == "trialing" && $subscription->trial_ends_at!='')
                                Ends {{date("m-d-Y", strtotime($subscription->trial_ends_at))}}
                            @else
                              {{ucfirst($subscription->interval)}}ly
                            @endif
                          </td>
                          <!-- <td>{{date("Y-m-d",(Auth::user()->subscription($subscription->name)->asStripeSubscription()->current_period_end))}}</td> -->
                        </tr>
                      @endforeach 
                    </tbody>
                  </table>
                  <div class="col-md-12 mt-2">
                    @if(!$user->subscription($productData->name)->canceled())
                      <button type="button" class="btn btn-danger cancel_subscription">Cancel My Subscription</button>
                    @else
                      <button type="button" class="btn btn-success resume_subscription">Resume My Subscription</button>
                    @endif
                  </div>
                </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    <!--/ right content section -->
  </div>
</section>
<!-- / account setting page -->


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="float:left">Please Enter OTP</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      <form class="otp_form" id="otp_form">
       <input type="hidden" class="form-control" name="phoneNo" id="phoneNo" required >
       <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" required minlength="6" maxlength="6">
       <br>
       <a class="resend" style="float:right"><p><u>Resend</u></p></a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="otpSubmit">Verify OTP</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('vendor-script')
  <!-- vendor files -->
  {{-- select2 min js --}}
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  {{--  jQuery Validation JS --}}
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

@endsection
@section('page-script')
  <!-- Page js files -->
{{--  <script src="{{ asset(mix('js/scripts/pages/page-account-settings.js')) }}"></script>--}}
  <script>
    jQuery.validator.addMethod("validate_email", function(value, element) {
        if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
            return true;
        } else {
            return false;
        }
    }, "Please enter valid email");

    jQuery.validator.addMethod("phoneNoValidation", function(value, element) {
        if(value == "(000)000-0000" || value == "0000000000"){
        return false;
        }
        return true;
    }, "* Please enter valid phone no");

    jQuery.validator.addMethod("notEqualTo",
      function(value, element, param) {
          var notEqual = true;
          value = $.trim(value);
          for (i = 0; i < param.length; i++) {
              if (value == $.trim($(param[i]).val())) { notEqual = false; }
          }
          return this.optional(element) || notEqual;
      },
      "Old and New Password should be different.."
    );

    jQuery.validator.addMethod("strongPassword", function(value) {
      return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
  },"The password must contain at least 1 number, 1 lower case letter,1 upper case letter and 1 special symbol"); 

  // /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/

  // /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]{10,12}$/
    $('#form').validate({
      rules: {
        old_password:{
          required: true,
        },
        new_password:{
          minlength:8,
          required: true,
          notEqualTo:["#old_password"],
          strongPassword:true,
        },
        password_confirm : {
          equalTo : "#new_password",
          notEqualTo:["#old_password"],
        }
      },

      messages: {
        old_password:{
          required: "Please enter password",
          // validate_password: "Password must contain at least one uppercase letter, one number, one special character, and must be at least 8 characters long",
        },
        new_password:{
          required: "Please enter new password",
          // notEqualTo: "old password",
          // validate_password: "Password must contain at least one uppercase letter, one number, one special character, and must be at least 8 characters long",
        },
        password_confirm:{
          equalTo: "Confirm password does not match",
        }
      },
      errorElement: 'span',
    });

    
    $('#update_profile').validate({
      rules: {
        user_fname:{
          required: true,
        },
        user_lname:{
          required: true,
        },
        email:{
          email:true,
          required: true,
          validate_email: true,
        },
        user_country_code:{
            required: true,
            maxlength:3,
          },
        user_phone_no:{
            // maxlength:14,
            // minlength:14,
            phoneNoValidation:true,
            required: true
        },
        'contact_name': {
          required: true,
        },
        'contact_email': {
          email:true,
          required: true,
          validate_email: true,
        },
        'contact_no': {
          phoneNoValidation:true,
          required: true,
        },

      },
      messages: {
        user_fname:{
          required: "Please enter first name"
        },
        user_lname:{
          required: "Please enter last name"
        },
        email:{
          required: "Please enter email"
        },
        user_country_code:{
          required: "Please country code",
          maxlength: "Please enter valid country code",
        },
        // user_phone_no:{
        // required: "Please enter phone number",
        // digits: "Please enter valid phone number",
        // minlength: "Please enter valid phone number",
        // maxlength: "Please enter valid phone number",
        // },
      },
      errorElement: 'span',
    });


    // document.getElementById('user_phone_no').addEventListener('input', function (e) {
    //     var x = e.target.value.replace(/\D/g, '').match(/(\d{3})(\d{3})(\d{4})/);
    //     e.target.value = '(' + x[1] + ') ' + x[2] + '-' + x[3];
    // });

 var base_url = "{{ url('') }}";
 $('#otp_form').validate({
      rules: {
        otp: {
            required: true
        },
      },
      messages: {
        otp:{
          required: "Please Enter OTP"
        },
      },
      errorElement: 'span',
    });

    function mfa_change()
    {
        var is_mfa = $('#is_mfa').val();
        // alert(user_type);
        if(is_mfa == 'Y') {
          $(".mfa_type").show();
        }else{
          $(".mfa_type").hide();
        }
    }

    $('#update_profile').on('submit',function(e){
     e.preventDefault();
     if ($('#update_profile').valid()) {
      $('#loader').show();
        var form = $(this);
        // var user_fname = $('#user_fname').val();
        // var user_lname = $('#user_lname').val();
        // var email = $('#email').val();
        var user_country_code = $('#user_country_code').val();
        // var user_phone_no = $('#user_phone_no').val();
        // var insurance_agency_name = $('#insurance_agency_name').val();
        // var is_mfa = $('#is_mfa').val();
        // var mfa_type = $('#mfa_type').val();
        var team_name = $('#team_name').val();
        var base_url = "{{ url('') }}";
        // var token = jQuery("input[name='_token']").val();
        // var contact_number = $("#contact_name").val();
        // var contact_email = $("#contact_email").val();
        // var contact_no = $("#contact_no").val();
        $.ajax({
        url: base_url + '/users/updateProfileAction',
        type:"POST",
        data : form.serialize() + '&user_country_code='  + user_country_code + '&team_name=' +  team_name ,
        // data:{
        //     "_token": token,
        //     'user_fname': user_fname,
        //     'user_lname': user_lname,
        //     'email': email,
        //     'user_country_code': user_country_code,
        //     'user_phone_no': user_phone_no,
        //     'insurance_agency_name': insurance_agency_name,
        //     'is_mfa': is_mfa,
        //     'mfa_type': mfa_type,
        //     'team_name':team_name,
        //     'contact_name' : contact_name,
        //     'contact_email' : contact_email,
        //     'contact_no' : contact_no,
        // },
        success:function(response){
            $('#loader').hide();
            if(response.is_changed == 'Y'){
                $('#myModal').modal('show');
                $('#phoneNo').val(response.phoneNo);
            }else{
                if(response.result == "success"){
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }

            }
        },
        error: function(response) {
            toastr.error(response.message);
        },
        });
     }
    });
  </script>
  <script>
  $('#otpSubmit').click(function(ev){
   ev.preventDefault();

   var token = jQuery("input[name='_token']").val();
   var otp = $('#otp').val();
   var phoneNo = $('#phoneNo').val();
   $.ajax({
      url: base_url + '/users/checkOTP',
      type:"POST",
      data:{
        "_token": token,
         "otp": otp,
         "phoneNo": phoneNo,
       },
       success:function(res){
           if(res.status == false){
            toastr.error(res.message);
            return false;
           }else{
            toastr.success(res.message);
             location.reload()
           }
      },
      error: function(res) {
        toastr.error(res.message);
      },
    });
  });


$('.resend').click(function(ever){
   ever.preventDefault();
   var token = jQuery("input[name='_token']").val();
   var phoneNo = $('#phoneNo').val();
   $.ajax({
      url: base_url + '/users/resendOTP',
      type:"POST",
      data:{
        "_token": token,
         "phoneNo": phoneNo,
       },
       success:function(res){
           if(res.status == false){
            toastr.error(res.message);
            return false;
           }else{
            toastr.success(res.message);
             return false;
           }
      },
      error: function(res) {
        toastr.error(res.message);
        return false;
      },
    });
});

function check_phone_format()
{
	 $('#user_phone_no').on('input', function () {
		 var number = $(this).val().replace(/[^\d]/g, '');
           // console.log(number);
		 if (number.length == 4) {
			number = number.replace(/(\d\d\d)/, "($1)");
		 }
		 else if (number.length == 7) {
			number = number.replace(/(\d\d\d)(\d\d\d)/, "($1)$2");
		 }
		 else if (number.length == 10) {
			number = number.replace(/(\d\d\d)(\d\d\d)(\d\d\d\d)/, "($1)$2-$3");
		 }
		 else
		 {
			number = number.replace(/(\d\d\d)(\d\d\d)(\d\d\d\d)/, "($1)$2-$3");
		 }
		 $(this).val(number);
		 $('#user_phone_no').attr({maxLength: 13});
	 });
   $('#contact_no').on('input', function () {
		 var number = $(this).val().replace(/[^\d]/g, '');
           // console.log(number);
		 if (number.length == 4) {
			number = number.replace(/(\d\d\d)/, "($1)");
		 }
		 else if (number.length == 7) {
			number = number.replace(/(\d\d\d)(\d\d\d)/, "($1)$2");
		 }
		 else if (number.length == 10) {
			number = number.replace(/(\d\d\d)(\d\d\d)(\d\d\d\d)/, "($1)$2-$3");
		 }
		 else
		 {
			number = number.replace(/(\d\d\d)(\d\d\d)(\d\d\d\d)/, "($1)$2-$3");
		 }
		 $(this).val(number);
		 $('#contact_no').attr({maxLength: 13});
	 });
}

$(".cancel_subscription").click(function(){
  bootbox.confirm({
        title: "Cancel Subscription",
        message: "Are you sure, you want to cancel subscription?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-danger'
            },
            cancel: {
                label: 'No',
                className: 'btn-secondary'
            }
        },
        callback: function (result) {
            if (result == true) {
                $('#loader').show();
                var endpoint = base_url+'/subscription/cancel';
                $.ajax({
                    url: endpoint,
                    method: 'GET',
                    
                    dataType: "json",
                    success: function (data) {
                        if(data.title == 'Error'){
                            toastr.error(data.message, data.title);
                        }else{
                            toastr.success(data.message, data.title);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            }
        }
    });
});

$(".resume_subscription").click(function(){
  bootbox.confirm({
        title: "Resume Subscription",
        message: "Are you sure, you want to resume subscription?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-danger'
            },
            cancel: {
                label: 'No',
                className: 'btn-secondary'
            }
        },
        callback: function (result) {
            if (result == true) {
                $('#loader').show();
                var endpoint = base_url+'/subscription/resume';
                $.ajax({
                    url: endpoint,
                    method: 'GET',
                    
                    dataType: "json",
                    success: function (data) {
                        if(data.title == 'Error'){
                            toastr.error(data.message, data.title);
                        }else{
                            toastr.success(data.message, data.title);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            }
        }
    });
});
</script>
@endsection
