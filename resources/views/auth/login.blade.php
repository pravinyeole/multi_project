<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="INRBHARAT Is a network to grow together">
  <title>{{env('APP_NAME')}}</title>
  <link rel="stylesheet" href="{{asset('vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/typicons/typicons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/simple-line-icons/css/simple-line-icons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{asset('css/vertical-layout-light/style.css')}}">
  <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
</head>
<style>
  .auth .brand-logo img {
    width: 150px;
  }

  .alert-error {
    background: #f96868;
    color: white;
  }
</style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <center><img src="{{asset('images/logo/hpa_logo_150_150.png')}}" alt="logo"></center>
              </div>
              <center>
                <h3>Hello! let's get started</h3>
              </center>
              @if (session('error'))
              <div class="alert alert-error m-1" role="alert" style="padding: 2%">
                {{ session('error') }}
              </div>
              @endif
              <!-- <h6 class="fw-light">Sign in to continue.</h6> -->
              <form class="pt-3" id="mobileForm" method="POST" action="{{url('login')}}">
                @csrf <!-- Add CSRF token field -->
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="mobileNumber" id="mobileNumber" maxlength="10" placeholder="Enter your mobile number">
                </div>
                <div class="mt-3">
                  <center><button id="getOtpBtn" class="btn btn-block btn-primary btn-lg btn-rounded font-weight-medium auth-form-btn">Get OTP</button></center>
                  <button id="resendOtpBtn" class="font-weight-bold btn btn-danger cursor" style="display: none;">Resend OTP</button>
                </div>
                </form>
                <div class="mb-3">
                  <div class="template-demo d-flex justify-content-between flex-nowrap">
                    <a href="{{config('custom.custom.facebook_id')}}" type="button" target="_blank" class="btn btn-primary btn-sm btn-icon">
                      <i class="ti-facebook"></i>
                    </a>
                    <a href="{{config('custom.custom.youtube_id')}}" type="button" target="_blank" class="btn btn-danger btn-sm btn-icon">
                      <i class="ti-youtube"></i>
                    </a>
                    <a href="{{config('custom.custom.twitter_id')}}" type="button" target="_blank" class="btn btn-primary btn-dark btn-sm btn-icon">
                      <i class="ti-twitter"></i>
                    </a>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="template-demo d-flex justify-content-between flex-nowrap">
                    <a href="{{config('custom.custom.instagram_id')}}" type="button" target="_blank" class="btn btn-danger btn-sm btn-icon">
                      <i class="ti-instagram"></i>
                    </a>
                    <a href="{{config('custom.custom.whatsapp_id')}}" type="button" target="_blank" class="btn btn-success btn-sm btn-icon">
                      <i><img width="22" height="22" src="https://img.icons8.com/color/48/whatsapp--v1.png" alt="whatsapp--v1" /></i>
                    </a>
                    <a href="{{config('custom.custom.telegram_id')}}" type="button" target="_blank" class="btn btn-info btn-sm btn-icon">
                      <i><img width="22" height="22" src="https://img.icons8.com/color/48/telegram-app--v1.png" alt="telegram-app--v1" /></i>
                    </a>
                  </div>
                </div>
                <div class="mt-5">
                  <center><span id="siteseal">
                      <script async="" type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=2gqQMOxnoyXrA7J9uoghOodRZmWSAdJVhXoELNzA9WvSL5kS2MydfWEGsoK9"></script>
                    </span></center>
                </div>
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
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
  <!-- endinject -->
  <script>
    $(function() {
      var jqForm = $('#mobileForm');
      var otpSection = $('#otp-section');
      var timer = $('#timer');
      var mobileNumberInput = $('#mobileNumber');
      var getOtpBtn = $('#getOtpBtn');
      var resendOtpBtn = $('#resendOtpBtn');
      var otpInputs = otpSection.find('.otp-input');
      var loginBtn = $('#loginBtn');
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
      var base_url = "{{url('/')}}";
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
              window.location.href = response.redirect_url;
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

      // OTP input change event
      otpInputs.on('input', function() {
        var currentInput = $(this);
        var otp = otpInputs.map(function() {
          return $(this).val();
        }).get().join('');

        if (otp.length === 6) {
          loginBtn.show();
        } else {
          loginBtn.hide();
          if (currentInput.next().length) {
            currentInput.next().focus(); // Focus on the next OTP input field
          }
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

    });
  </script>
</body>

</html>