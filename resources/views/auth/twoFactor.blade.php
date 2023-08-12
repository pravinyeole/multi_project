@extends('layouts/fullLayoutMaster')

@section('title', 'Two Factor Authentication')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-v1 px-2">
  <div class="auth-inner py-2">
    <!-- Forgot Password v1 -->
    <div class="card mb-0">
      <div class="card-body">
        <a href="javascript:void(0);" class="brand-logo">
          <img src="{{asset('images/logo/logo.svg')}}" width="100%">
{{--          <h2 class="brand-text text-primary ml-1">Coverage Wizard</h2>--}}
        </a>

        <h4 class="card-title mb-1">Two Factor Authentication</h4>
        {{-- <p class="card-text mb-2">Please fill your profile here</p> --}}

        <form class="auth-forgot-password-form mt-2" id="update_profile" method="POST" action="{{ url('two-fact-auth/verifyOtp') }}">
          @csrf
                 @if ($mfa_type == "both")
                    <div class="form-group">
                        <label for="email_otp" class="form-label">Email OTP </label>
                        <input type="text" class="form-control" id="email_otp" maxlength="6" minlength="6" name="email_otp" value="{{ old('email_otp') }}" required placeholder="Email OTP"  tabindex="1" autofocus />
                    </div>

                    <div class="form-group">
                        <label for="phone_otp" class="form-label">Phone OTP</label>
                        <input type="text" class="form-control" id="phone_otp" maxlength="6" minlength="6" name="phone_otp" value="{{ old('phone_otp') }}" required placeholder="Phone OTP"  tabindex="1" autofocus />
                    </div>
                @elseif ($mfa_type == "email")
                <div class="form-group">
                    <label for="email_otp" class="form-label">Email OTP </label>
                    <input type="text" class="form-control" id="email_otp" maxlength="6" minlength="6" name="email_otp" value="{{ old('email_otp') }}" required placeholder="Email OTP"  tabindex="1" autofocus />
                </div>
                @elseif ($mfa_type == "phone")
                <div class="form-group">
                    <label for="phone_otp" class="form-label">Phone OTP</label>
                    <input type="text" class="form-control" id="phone_otp" maxlength="6" minlength="6" name="phone_otp" value="{{ old('phone_otp') }}" required placeholder="Phone OTP"  tabindex="1" autofocus />
                </div>

                @endif
                <a href="{{ url('two-fact-auth/resend') }}" style="float: right" tabindex="2"><u>Resend</u></a>

                <button type="submit" class="btn btn-primary " width="100%" tabindex="2">Verify OTP</button>

                <a href="{{ url('logout') }}" tabindex="2">
                  <button type="button" class="btn btn-danger " width="100%" tabindex="2">Logout</button>
                </a>
            </div>


        </form>
        <p class="text-center mt-2">
          @if (Route::has('login'))
          {{-- <a href="{{ route('login') }}"> <i data-feather="chevron-left"></i> Back to login </a> --}}
          @endif
        </p>

      </div>
    </div>
    <!-- /Forgot Password v1 -->
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
<script>
    var jqForm = $('#update_profile');
        if (jqForm.length) {
        // jQuery.validator.addMethod("validate_email", function(value, element) {
        //     if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }, "Please enter valid email");
        // jQuery.validator.addMethod("require_field", function(value, element) {
        //     if(value.trim() ==''){
        //         return false;
        //     }
        //     return true;
        // }, "This field is required.");
        jqForm.validate({
        rules: {
            email_otp:{
            required: true,
            number:true,

          },
          phone_otp:{
            required: true,
            number:true,
          },

        },
        messages: {
            email_otp:{
            required: "Please enter otp"
          },
          phone_otp:{
            required: "Please enter contact otp"
          },
        },
        errorElement: 'span',
      });
    }

      $(document).ready(function () {
            $('#update_profile').validate({
                errorElement: 'span',
                errorPlacement: function (error, element) {
                  error.addClass('invalid-feedback');
                  element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                }
            });
        });
    </script>
@endsection
