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
  <link rel="shortcut icon" href="{{asset('images/logo/hpa_logo.png')}}" />
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
</head>
<style>
  body{
  background: rgb(255,255,255);
background: -moz-radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(255,241,226,1) 100%);
background: -webkit-radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(255,241,226,1) 100%);
background: radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(255,241,226,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ffffff",endColorstr="#fff1e2",GradientType=1);
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
      <div class="content-wrapper login-wrapper background-none d-flex align-items-center auth px-0 justify-content-center">
            <div class="auth-form-light text-center p-0">
              <div class="brand-logo">
                <img src="{{asset('images/logo/inrb_logo.svg')}}" alt="logo">
              </div>
              <div class="login-body text-center">
              
              <!-- <h6 class="fw-light">Sign in to continue.</h6> -->
                @csrf <!-- Add CSRF token field -->
                <div class="header">
                <h1 class="pb-2 pt-4">Logged Out Successfully</h1>
                  <a href="{{url('/')}}" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn mt-2">Login Again</a>
                </div>
                </form>
                <!--div class="seprator"></div-->
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <img src="{{asset('images/digital-india.png')}}" alt="logo" style="max-width:200px;margin: 0 auto;width:48%">
                    <img src="{{asset('images/make-india.png')}}" alt="logo" style="max-width:120px;margin: 0 auto;width:48%">
                </div>
                <div class="pb-2 social text-white">
                  <h4>Follow us</h4>
                  <div class="social-icon d-flex justify-content-between flex-nowrap">
                    <a href="{{config('custom.custom.facebook_id')}}" type="button" target="_blank" class="btn-icon">
                      <i class="icon" data-feather="facebook"></i>
                    </a>
                    <a href="{{config('custom.custom.youtube_id')}}" type="button" target="_blank" class="btn-icon">
                    <i class="icon" data-feather="youtube"></i>
                    </a>
                    <a href="{{config('custom.custom.twitter_id')}}" type="button" target="_blank" class="btn-icon">
                    <i class="icon" data-feather="twitter"></i>
                    </a>
                    <a href="{{config('custom.custom.instagram_id')}}" type="button" target="_blank" class="btn-icon">
                    <i class="icon" data-feather="instagram"></i>
                    </a>
                    <a href="{{config('custom.custom.whatsapp_id')}}" type="button" target="_blank" class="btn-icon">
                      <i><img width="24" height="24" src="{{asset('images/auth/whatsap.png')}}" alt="whatsapp--v1" /></i>
                    </a>  
                    <a href="{{config('custom.custom.telegram_id')}}" type="button" target="_blank" class="btn-icon">
                    <i class="icon" data-feather="send"></i>
                    </a>
                  </div>
                </div>
                <div class="mt-1 mb-2">
                  <span id="siteseal">
                      <script async="" type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=2gqQMOxnoyXrA7J9uoghOodRZmWSAdJVhXoELNzA9WvSL5kS2MydfWEGsoK9"></script>
                    </span>
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
  <script src="{{asset('js/custom/feather.min.js')}}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page
  <script src="{{asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script> -->
  <!-- End plugin js for this page -->
  <!-- inject:js
  <script src="{{asset('js/off-canvas.js')}}"></script>
  <script src="{{asset('js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('js/settings.js')}}"></script>
  <script src="{{asset('js/todolist.js')}}"></script> -->
  <!-- endinject -->
  <script>
    $(function() {
      
    feather.replace()

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
              toastr.success(response.message);
              window.location.href = response.redirect_url;
            },
            error: function(error) {
              // Handle the error response here
              if (error.responseJSON.message == null || error.responseJSON.message==''){
                toastr.error('Failed to Send OTP');
              }else{
                toastr.error(error.responseJSON.message);
              }

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