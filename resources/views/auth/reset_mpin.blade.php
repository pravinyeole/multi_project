<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{env('APP_NAME')}}</title>
  <link rel="stylesheet" href="{{asset('vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/typicons/typicons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/simple-line-icons/css/simple-line-icons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{asset('css/vertical-layout-light/style.css')}}">
  <link rel="shortcut icon" href="{{asset('images/logo/hpa_logo.png')}}" />
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
</head>
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
    padding-bottom: 15px;
  }

  .otp-input-group input {
    width: 48px;
    margin: 10px 5px 10px 5px;
    height: 48px;
    border: 1px solid #adadad;
    border-radius: 6px;
    box-shadow: 5px 5px 16px rgba(0, 0, 0, 0.15);
    background: #fff;
    text-align: center;
  }

  @media all and (max-width:360px) {
    .otp-input-group input {
      width: 38px;
      height: 38px;
      margin: 0px 5px 0px 5px;
    }
  }

  @media all and (max-height:660px) {
    .otp-input-group input {
      width: 35px;
      height: 35px;
      margin: 0px 5px 0px 5px;
    }
  }

  .otp-input-group .form-control {
    flex: 1;
    max-width: 40px;
  }

  .otp-input-group .form-control:not(:last-child) {
    margin-right: 5px;
  }

  #loginBtn,
  #otpCheckBtn {
    display: none;
  }

  #mobileNumber {
    text-align: center;
    font-size: 20px;
  }

  .alert-error {
    background: #f96868;
    color: white;
  }

  .mpin_err {
    color: black;
    font-size: 20px;
    font-weight: bold;
  }

  #timer {
    color: #989696b8;
    font-size: 14px;
  }

  .reset-otp-o {
    display: none;
  }
</style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper login-wrapper background-none d-flex align-items-center auth px-0 justify-content-center">
        <div class="auth-form-light text-center p-0">
          <div class="brand-logo">
            <img src="{{asset('images/logo/inrb_logo.svg')}}" alt="logo">
          </div>
          <div class="login-body">
            <div class="header">
              <h1>Reset mPIN</h1>
            </div>
            @if (session('error'))
            <div class="alert alert-error m-1" role="alert" style="padding: 2%">
              {{ session('error') }}
            </div>
            @endif
            <div id="otp-section-reset" class="mt-2 reset-otp-t">
              <h5>Enter OTP</h5>
              <div class="otp-input-group d-flex align-items-center justify-content-center">
                <input type="password" class="otp-input-reset" maxlength="1" name="resetotp[]">
                <input type="password" class="otp-input-reset" maxlength="1" name="resetotp[]">
                <input type="password" class="otp-input-reset" maxlength="1" name="resetotp[]">
                <input type="password" class="otp-input-reset" maxlength="1" name="resetotp[]">
                <input type="password" class="otp-input-reset" maxlength="1" name="resetotp[]">
                <input type="password" class="otp-input-reset" maxlength="1" name="resetotp[]">
              </div>
              <div class="text-center">
                <a href="javaScript:void(0);" class="font-weight-medium resentOTP">Resend OTP ?</a>
              </div>
              <div class="text-center">
                <button id="otpCheckBtn" class="btn btn-block btn-primary btn-lg font-weight-medium login-btn">Verify OTP</button>
              </div>
            </div>
            <form id="mobileForm" method="POST" action="{{url('update-mpin')}}">
              @csrf
              <input type="hidden" class="form-control" name="mobileNumber" id="mobileNumber" maxlength="10" placeholder="Enter your mobile number" value="{{$mobileNumber}}" readonly>
              <input type="hidden" name="id" value="{{$user_id}}">
              <div id="otp-section" class="mt-2 reset-otp-o">
                <h5>Enter New mPIN</h5>
                <div class="otp-input-group d-flex align-items-center justify-content-center">
                  <input type="password" class="otp-input" maxlength="1" name="otp[]">
                  <input type="password" class="otp-input" maxlength="1" name="otp[]">
                  <input type="password" class="otp-input" maxlength="1" name="otp[]">
                  <input type="password" class="otp-input" maxlength="1" name="otp[]">
                </div>
              </div>
              <div id="otp-section-two" class="mt-2 reset-otp-o">
                <h5>Confirm New mPIN</h5>
                <div class="otp-input-group d-flex align-items-center justify-content-center">
                  <input type="password" class="otp-input-two" maxlength="1" name="otpTwo[]">
                  <input type="password" class="otp-input-two" maxlength="1" name="otpTwo[]">
                  <input type="password" class="otp-input-two" maxlength="1" name="otpTwo[]">
                  <input type="password" class="otp-input-two" maxlength="1" name="otpTwo[]">
                </div>
              </div>
              <p class="mpin_err"></p>
              <div class="text-center">
                <button id="loginBtn" class="btn btn-block btn-primary btn-lg font-weight-medium login-btn">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
  </div>
  <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
  <script src="{{asset('js/forms/validation/jquery.validate.min.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{asset('js/off-canvas.js')}}"></script>
  <script src="{{asset('js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('js/settings.js')}}"></script>
  <script src="{{asset('js/todolist.js')}}"></script>
  <script src="{{asset('js/custom/feather.min.js')}}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
  <!-- endinject -->
  <script>
    $(function() {
      feather.replace();
      var base_url = "{{url('/')}}";
      var jqForm = $('#mobileForm');
      var otpSectionReset = $('#otp-section-reset');
      var otpInputsReset = otpSectionReset.find('.otp-input-reset');
      var otpSection = $('#otp-section');
      var otpSectionTwo = $('#otp-section-two');
      var timer = $('#timer');
      var mobileNumberInput = $('#mobileNumber');
      var getOtpBtn = $('#getOtpBtn');
      var resendOtpBtn = $('#resendOtpBtn');
      var otpInputs = otpSection.find('.otp-input');
      var otpInputsTwo = otpSectionTwo.find('.otp-input-two');
      var loginBtn = $('#loginBtn');
      var otpCheckBtn = $('#otpCheckBtn');
      var loginTitle = $('.login-title');
      var loginOtpIcon1 = $('.enterphone');
      var loginOtpIcon2 = $('.veritifcation-otp');
      var timerDuration = 120; // Duration in seconds
      var timerInterval; // Variable to hold the timer interval

      // Mobile number validation method
      jQuery.validator.addMethod("validate_mobile", function(value, element) {
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

      // Get OTP button click event
      getOtpBtn.click(function(e) {
        e.preventDefault();
        if (jqForm.valid()) {
          $.ajax({
            // url: jqForm.attr('action'),
            url: base_url + "/common/send-otp",
            method: 'POST',
            data: jqForm.serialize(),
            success: function(response) {
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
            error: function(error) {
              console.log(error);
              // Handle the error response here
              toastr.error('Failed to send OTP');
            }
          });
        }
      });
      // Resend OTP button click event
      resendOtpBtn.click(function(e) {
        e.preventDefault();
        // Send mobile number with AJAX
        $.ajax({
          url: base_url + "/common/resend-otp",
          method: 'POST',
          data: jqForm.serialize(),
          success: function(response) {
            console.log(response);
            // Handle the success response here
            toastr.success('OTP sent successfully');
          },
          error: function(error) {
            console.log(error);
            toastr.success('Somthing went wrong!!');
            // Handle the error response here
          }
        });
      });
      // OTP Reset
      var resetOtp;
      otpInputsReset.on('input', function(e) {
        var currentInput = $(this);
        resetOtp = otpInputsReset.map(function() {
          return $(this).val();
        }).get().join('');
        if (resetOtp.length === 6) {
          otpCheckBtn.show();
        } else {
          otpCheckBtn.hide();
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

      // OTP input change event
      var otp;
      var otpTwo;
      otpInputs.on('input', function(e) {
        var currentInput = $(this);
        otp = otpInputs.map(function() {
          return $(this).val();
        }).get().join('');
        if (otp.length === 4 && otpTwo == otp) {
          loginBtn.show();
          $('.mpin_err').text('');
        } else {
          $('.mpin_err').text('New mPIN & Confirm mPIN does not match.');
          loginBtn.hide();
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
      otpInputsTwo.on('input', function(e) {
        var currentInput = $(this);
        var otpTwo = otpInputsTwo.map(function() {
          return $(this).val();
        }).get().join('');
        if (otpTwo.length === 4 && otpTwo == otp) {
          loginBtn.show();
          $('.mpin_err').text('');
        } else {
          $('.mpin_err').text('New mPIN & Confirm mPIN does not match.');
          loginBtn.hide();
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
        timerInterval = setInterval(function() {
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
      $('.resentOTP').click(function() {
        var userid = '{{$user_id}}';
        var usermobile = '{{$mobileNumber}}';
        $.ajax({
          // url: jqForm.attr('action'),
          url: base_url + "/common/resend-otp",
          method: 'POST',
          data: {
            "_token": "{{ csrf_token() }}",
            'mobileNumber': usermobile
          },
          success: function(response) {
            console.log(response);
            // Handle the success response here
            toastr.success(response.message);
          },
          error: function(error) {
            console.log(error);
            // Handle the error response here
            toastr.error('Failed to send OTP');
          }
        });
      });
      $('#otpCheckBtn').click(function() {
        var userid = '{{$user_id}}';
        var usermobile = '{{$mobileNumber}}';
        $.ajax({
          // url: jqForm.attr('action'),
          url: base_url + "/common/verify-otp",
          method: 'POST',
          data: {
            "_token": "{{ csrf_token() }}",
            'mobileNumber': usermobile,
            'userid': userid,
            'resetOtp':resetOtp
          },
          success: function(response) {
            console.log(response);
            // Handle the success response here
            if(response.status == 'success'){
              toastr.success(response.message);
              $('.reset-otp-t').css('display','none');
              $('.reset-otp-o').css('display','block');
            }else{
              toastr.error(response.message);
            }
          },
          error: function(error) {
            console.log(error);
            // Handle the error response here
            toastr.error('Failed to send OTP');
          }
        });
      });
    });
  </script>
</body>

</html>