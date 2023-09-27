@extends('layouts/fullLayoutMaster')

@section('title', 'Register Page')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{asset('css/vertical-layout-light/style.css')}}">
<link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
<style>
  html body {
    background: rgb(255, 255, 255);
    background: -moz-radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(255, 241, 226, 1) 100%);
    background: -webkit-radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(255, 241, 226, 1) 100%);
    background: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(255, 241, 226, 1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ffffff", endColorstr="#fff1e2", GradientType=1);
  }

  @media (max-width: 576px) {
    .auth-inner {
      margin: 0 30px;
    }
  }

  .card {
    width: 350px;
    padding: 10px;
    border-radius: 20px;
    background: #fff;
    border: none;
    /* height: 470px; */
    position: relative;
  }

  .container {
    height: 100vh;
  }

  body {
    background: #eee;
  }

  .mobile-text {
    color: #989696b8;
    font-size: 15px;
  }

  .form-control {
    margin-right: 12px;
  }

  .form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #ff8880;
    outline: 0;
    box-shadow: none;
  }

  .cursor {
    cursor: pointer;
  }

  #otp-section {
    display: none;
  }

  #timer {
    color: #989696b8;
    font-size: 14px;
  }

  .otp-input-group {
    display: flex;
  }

  .otp-input-group .form-control {
    flex: 1;
    max-width: 40px;
  }

  .otp-input-group .form-control:not(:last-child) {
    margin-right: 5px;
  }

  #loginBtn {
    display: none;
  }


  #timer {
    color: #989696b8;
    font-size: 14px;
  }
</style>
@endsection

@section('content')
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper login-wrapper background-none d-flex align-items-center auth px-0 justify-content-center">
      <div class="auth-form-light text-center p-0">
        <div class="brand-logo">
          <img src="{{asset('images/logo/inrb_logo.svg')}}" alt="logo">
        </div>
        <div class="login-body">
          @if(isset($user->mobile_number) && $user->mobile_number != null)
          @if (session('error'))
          <div class="alert alert-error m-1" role="alert" style="padding: 2%">
            {{ session('error') }}
          </div>
          @endif
          <div class="header pb-2">
            <h1>Register</h1>
            <h3 class="text-dark text-small">Mobile phone verification <span class="text-primary">{{$user->mobile_number}}</span></h3>
          </div>

          <form id="registrationForm" method="POST" action="{{route('register_user')}}">
            @csrf <!-- Add CSRF token field -->
            <div class="form-group mb-2">
              <input type="hidden" name="mobile_number" value="{{$user->mobile_number}}">
              <input type="text" class="form-control form-control-md" name="user_fname" id="user_fname" placeholder="Enter First Name" value="{{ old('user_fname') }}">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control form-control-md" name="user_lname" id="user_lname" placeholder="Enter Last Name" value="{{ old('user_lname') }}">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control form-control-md" name="email" id="email" placeholder="Enter your email id" value="{{ old('email') }}">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control form-control-md" name="referal_code" id="referal_code" placeholder="Enter Referal Mobile Number" value="{{ old('referal_code') }}" maxlength="10">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control form-control-md" name="admin_referal_code" id="admin_referal_code" placeholder="Enter System Access Code" value="{{ old('admin_referal_code') }}">
            </div>
            <div class="text-center form-group mb-0">
              <button id="registerBtn" class="btn auth-form-btn text-white">Register</button>
            </div>
          </form>
          @else
          @if (session('error'))
          <div class="alert alert-error m-1" role="alert" style="padding: 2%">
            {{ session('error') }}
          </div>
          @endif
          <div class="header pb-2">
            <h1>Register</h1>
          </div>
          <form id="registrationForm" method="POST" action="{{route('register_user')}}">
            @csrf <!-- Add CSRF token field -->
            <div class="form-group mb-2">
              <input type="text" pattern="[0-9]{10}" class="form-control form-control-md" name="mobile_number" id="mobile_number" placeholder="Enter Mobile Number" value="{{ old('mobile_number') }}">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control form-control-md" name="user_fname" id="user_fname" placeholder="Enter First Name" value="{{ old('user_fname') }}">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control form-control-md" name="user_lname" id="user_lname" placeholder="Enter Last Name" value="{{ old('user_lname') }}">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control form-control-md" name="email" id="email" placeholder="Enter your email id" value="{{ old('email') }}">
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control form-control-md" name="referal_code" id="referal_code" placeholder="Enter Referal Mobile Number" value="{{ $invitation_mobile }}" maxlength="10" @if(isset($invitation_mobile) && $invitation_mobile !=null) readonly @endif>
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control form-control-md" name="admin_referal_code" id="admin_referal_code" placeholder="Enter System Access Code" value="{{$invitation_ID}}" @if(isset($invitation_ID) && $invitation_ID !=null) readonly @endif>
            </div>
            <div class="text-center mt-2">
              <button id="registerBtn" class="btn auth-form-btn text-white">Register</button>
            </div>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>
  @endsection

  @section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  @endsection

  @section('page-script')
  <script>
    $(document).ready(function() {
      $('#registrationForm').validate({
        rules: {
          user_fname: {
            required: true,
            minlength: 2
          },
          user_lname: {
            required: true,
            minlength: 2
          },
          email: {
            required: true,
            email: true
          },
          referal_code: {
            required: true
          },
          admin_referal_code: {
            required: true
          }
        },
        messages: {
          user_fname: {
            required: "Please enter your first name",
            minlength: "Your first name must be at least 2 characters long"
          },
          user_lname: {
            required: "Please enter your last name",
            minlength: "Your last name must be at least 2 characters long"
          },
          email: {
            required: "Please enter your email",
            email: "Please enter a valid email address"
          },
          referal_code: {
            required: "Please enter the referral mobile number"
          },
          admin_referal_code: {
            required: "Please enter the admin referral code"
          }
        },
        submitHandler: function(form) {
          form.submit();
        }
      });
    });
  </script>

  @endsection