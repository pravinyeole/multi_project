@extends('layouts/fullLayoutMaster')

@section('title', 'Register Page')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<style>
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
<div class="auth-wrapper auth-v1" style="background-color: #66C3F4;">
  <div class="auth-inner py-2">
    <div class="d-flex justify-content-center align-items-center container">
      <div class="card py-5 px-3">
        <h5 class="m-0">Register</h5>
        <h5 class="m-0">Mobile phone verification <span class="text-primary">{{$user->mobile_number}}</span></h5>
        <form id="registrationForm" method="POST" action="{{route('register_user')}}">
          @csrf <!-- Add CSRF token field -->
          <div class="mt-2">
            <input type="hidden" name="mobile_number" value="{{$user->mobile_number}}">
            <input type="text" class="form-control" name="user_fname" id="user_fname"  placeholder="Enter First Name" value="{{ old('user_fname') }}">
          </div>
          <div class="mt-2">
            <input type="text" class="form-control" name="user_lname" id="user_lname"  placeholder="Enter Last Name"  value="{{ old('user_lname') }}">
          </div>
          <div class="mt-2">
            <input type="text" class="form-control" name="email" id="email"  placeholder="Enter your email id" value="{{ old('email') }}">
          </div>
          <div class="mt-2">
            <input type="text" class="form-control" name="referal_code" id="referal_code"  placeholder="Enter Referal Mobile Number" value="{{ old('referal_code') }}" maxlength="10">
          </div>
           <div class="mt-2">
            <input type="text" class="form-control" name="admin_referal_code" id="admin_referal_code"  placeholder="Enter Admin Referal Code" value="{{ old('admin_referal_code') }}">
          </div>
          <div class="text-center mt-2">
            <button id="registerBtn" class="btn btn-primary">Register</button>
          </div>
        </form>
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
