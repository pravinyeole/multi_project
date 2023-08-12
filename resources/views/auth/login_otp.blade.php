@extends('layouts/fullLayoutMaster')

@section('title', 'Login Page')

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
    width: 100;
    padding: 10px;
    border-radius: 20px;
    background: #fff;
    border: none;
    height: 350px;
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
<div class="auth-wrapper auth-v1" style="background-color: #fff;">
  <div class="auth-inner py-2">
    <div class="d-flex justify-content-center align-items-center container">
      <div class="py-5 px-3" style="border: 3px solid #1a75bc;border-radius: 30px;">
          <img src="{{asset('images/logo/otp-logo-2.png')}}" width="100%" style="height: 260px"  class="veritifcation-otp" id="otp-logo-2"/>
        <h2 class="m-0 text-center login-title">OTP Verification</h2>
        <form id="mobileForm" method="POST" action="{{url('login')}}">
          @csrf <!-- Add CSRF token field -->
          <div class="mt-2">
            <input type="text" class="form-control" name="mobileNumber" id="mobileNumber" maxlength="10" placeholder="Enter your mobile number" value="{{$mobileNumber}}" readonly>
          </div>
          <input type="hidden" name="id" value="{{$user_id}}">
          <div id="otp-section" class="mt-2">
            <div class="otp-input-group">
              <input type="text" class="form-control otp-input" maxlength="1" name="otp[]">
              <input type="text" class="form-control otp-input" maxlength="1" name="otp[]">
              <input type="text" class="form-control otp-input" maxlength="1" name="otp[]">
              <input type="text" class="form-control otp-input" maxlength="1" name="otp[]">
              <input type="text" class="form-control otp-input" maxlength="1" name="otp[]">
              <input type="text" class="form-control otp-input" maxlength="1" name="otp[]">
            </div>
          </div>
          <div class="text-center mt-2">
            <button id="loginBtn" class="btn btn-primary">Login</button>
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
  $(function () {
    var jqForm = $('#mobileForm');
    var otpSection = $('#otp-section');
    var timer = $('#timer');
    var mobileNumberInput = $('#mobileNumber');
    var getOtpBtn = $('#getOtpBtn');
    var resendOtpBtn = $('#resendOtpBtn');
    var otpInputs = otpSection.find('.otp-input');
    var loginBtn = $('#loginBtn');
    var loginTitle = $('.login-title');
    var loginOtpIcon1 =$('.enterphone');
    var loginOtpIcon2 =$('.veritifcation-otp');
    var timerDuration = 120; // Duration in seconds
    var timerInterval; // Variable to hold the timer interval

    // Mobile number validation method
    jQuery.validator.addMethod("validate_mobile", function (value, element) {
      if (/^\d{10}$/.test(value)) {
        return true;
      } else {
        return false;
      }
    }, "Please enter a 10-digit mobile number");

    jqForm.validate({
      rules: {
        mobileNumber: {
          required: true,
          validate_mobile: true,
        },
      },
      messages: {
        mobileNumber: {
          required: "Please enter your mobile number",
          validate_mobile: "Please enter a valid 10-digit mobile number",
        },
      },
    });
    var base_url = "{{url('/')}}";
    // Get OTP button click event
    getOtpBtn.click(function (e) {
      e.preventDefault();
      if (jqForm.valid()) {
        $.ajax({
          // url: jqForm.attr('action'),
          url: base_url + "/common/send-otp",
          method: 'POST',
          data: jqForm.serialize(),
          success: function (response) {
            console.log(response);
            // Handle the success response here
            toastr.success('OTP sent successfully');
            startTimer();
            getOtpBtn.hide();
            otpSection.show();
            loginOtpIcon1.addClass('d-none');
            loginOtpIcon2.removeClass('d-none');
            loginTitle.html('OTP Verification')
            otpInputs.first().focus(); // Focus on the first OTP input field
            resendOtpBtn.hide(); // Hide the resend OTP button
          },
          error: function (error) {
            console.log(error);
            // Handle the error response here
            toastr.error('Failed to send OTP');
          }
        });
      }
    });

    // Resend OTP button click event
    resendOtpBtn.click(function (e) {
      e.preventDefault();
      // Send mobile number with AJAX
      $.ajax({
        url: base_url + "/common/resend-otp",
        method: 'POST',
        data: jqForm.serialize(),
        success: function (response) {
          console.log(response);
          // Handle the success response here
          toastr.success('OTP sent successfully');
        },
        error: function (error) {
          console.log(error);
          toastr.success('Somthing went wrong!!');
          // Handle the error response here
        }
      });
    });

    // OTP input change event
    otpInputs.on('input', function (e) {
      var currentInput = $(this);
      var otp = otpInputs.map(function () {
        return $(this).val();
      }).get().join('');

      if (otp.length === 6) {
        loginBtn.show();
      } else {
        loginBtn.hide();
        // if (currentInput.next().length) {
        //   currentInput.next().focus(); // Focus on the next OTP input field
        // }
      }
        // Handle focus moving to the next input
        if (e.originalEvent.inputType !== 'deleteContentBackward' && currentInput.next().length) {
            currentInput.next().focus();
        }

        // Handle focus moving to the previous input on backspace/delete
        if (e.originalEvent.inputType === 'deleteContentBackward' && currentInput.prev().length) {
            currentInput.prev().focus();
        }
    });

    // Start the timer
    function startTimer() {
      var duration = timerDuration;
      timer.text('');
      clearInterval(timerInterval); // Clear any existing timer interval
      timerInterval = setInterval(function () {
        if (duration <= 0) {
          clearInterval(timerInterval);
          timer.text('');
          otpInputs.val('');
          resendOtpBtn.show(); // Show the resend OTP button
          mobileNumberInput.attr("disabled", false);
        } else {
          var minutes = Math.floor(duration / 60);
          var seconds = duration % 60;
          var timeText = "Resend OTP in " + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds < 10 ? "0" + seconds : seconds);
          timer.text(timeText);
          duration--;
        }
      }, 1000);
    }

  });
</script>
@endsection
