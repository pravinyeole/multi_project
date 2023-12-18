@extends('layouts/fullLayoutMaster')

@section('title', 'Register Page')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{asset('css/vertical-layout-light/style.css')}}">
<link rel="shortcut icon" href="{{asset('images/logo/hpa_logo.png')}}">
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

  #otp-section,
  #verify_mobile,
  #resendOtpBtn {
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

  .error {
    color: red !important;
    font-size: large;
  }

  #timer {
    color: #989696b8;
    font-size: 14px;
  }

  .otp-input-group {
    display: flex;
    padding-bottom: 15px;
  }

  .nextstep {
    display: none;
  }

  .otp-input-group input {
    width: 45px;
    margin: 5px 5px 5px 5px;
    height: 45px;
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

  .loader {
    top: 0;
    left: 0;
    position: fixed;
    opacity: 0.8;
    z-index: 10000000;
    background: Black;
    height: 100%;
    width: 100%;
    margin: auto;
  }

  .strip-holder {
    top: 50%;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
    left: 50%;
    margin-left: -50px;
    position: relative;
  }

  .strip-1,
  .strip-2,
  .strip-3 {
    width: 20px;
    height: 20px;
    background: #0072bc;
    position: relative;
    -webkit-animation: stripMove 2s ease infinite alternate;
    animation: stripMove 2s ease infinite alternate;
    -moz-animation: stripMove 2s ease infinite alternate;
  }

  .strip-2 {
    -webkit-animation-duration: 2.1s;
    animation-duration: 2.1s;
    background-color: #23a8ff;
  }

  .strip-3 {
    -webkit-animation-duration: 2.2s;
    animation-duration: 2.2s;
    background-color: #89d1ff;
  }

  @@-webkit-keyframes stripMove {
    0% {
      transform: translate3d(0px, 0px, 0px);
      -webkit-transform: translate3d(0px, 0px, 0px);
      -moz-transform: translate3d(0px, 0px, 0px);
    }

    50% {
      transform: translate3d(0px, 0px, 0px);
      -webkit-transform: translate3d(0px, 0px, 0px);
      -moz-transform: translate3d(0px, 0px, 0px);
      transform: scale(4, 1);
      -webkit-transform: scale(4, 1);
      -moz-transform: scale(4, 1);
    }

    100% {
      transform: translate3d(-50px, 0px, 0px);
      -webkit-transform: translate3d(-50px, 0px, 0px);
      -moz-transform: translate3d(-50px, 0px, 0px);
    }
  }

  @@-moz-keyframes stripMove {
    0% {
      transform: translate3d(-50px, 0px, 0px);
      -webkit-transform: translate3d(-50px, 0px, 0px);
      -moz-transform: translate3d(-50px, 0px, 0px);
    }

    50% {
      transform: translate3d(0px, 0px, 0px);
      -webkit-transform: translate3d(0px, 0px, 0px);
      -moz-transform: translate3d(0px, 0px, 0px);
      transform: scale(4, 1);
      -webkit-transform: scale(4, 1);
      -moz-transform: scale(4, 1);
    }

    100% {
      transform: translate3d(50px, 0px, 0px);
      -webkit-transform: translate3d(50px, 0px, 0px);
      -moz-transform: translate3d(50px, 0px, 0px);
    }
  }

  @@keyframes stripMove {
    0% {
      transform: translate3d(-50px, 0px, 0px);
      -webkit-transform: translate3d(-50px, 0px, 0px);
      -moz-transform: translate3d(-50px, 0px, 0px);
    }

    50% {
      transform: translate3d(0px, 0px, 0px);
      -webkit-transform: translate3d(0px, 0px, 0px);
      -moz-transform: translate3d(0px, 0px, 0px);
      transform: scale(4, 1);
      -webkit-transform: scale(4, 1);
      -moz-transform: scale(4, 1);
    }

    100% {
      transform: translate3d(50px, 0px, 0px);
      -webkit-transform: translate3d(50px, 0px, 0px);
      -moz-transform: translate3d(50px, 0px, 0px);
    }
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
        <div class="loader" id="AjaxLoader" style="display:none;">
          <div class="strip-holder">
            <div class="strip-1"></div>
            <div class="strip-2"></div>
            <div class="strip-3"></div>
          </div>
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
            <div class="form-group mb-2 form-row">
              <div class="col">
                <input type="text" class="form-control form-row text-left" name="user_fname" id="user_fname" placeholder="First Name" value="{{ $user->user_fname }}">
              </div>
              <div class="col">
                <input type="text" class="form-control form-row text-left" name="user_lname" id="user_lname" placeholder="Last Name" value="{{ $user->user_lname }}">
              </div>
            </div>
            <P class="error name_err m-0"></P>
            <div class="form-group form-row mb-0">
              <div class="col-12 mb-2">
                <input type="text" class="form-control form-row text-left" readonly id="mobile_number" name="mobile_number" placeholder="Telegram Phone Number" value="{{$user->mobile_number}}">
              </div>
              <div class="col-12 mb-2 d-flex gap-1">
                <input type="text" class="form-control form-row text-left" name="my_upi_id" id="my_upi_id" placeholder="UPI ID" value="{{ $user->upi }}">
                <button type="button" id="checkBtn" class="btn auth-form-btn text-white px-3 py-2 m-0 w-auto">Verify</button>
              </div>
            </div>
            <div class="form-group form-row mb-0">
              <div class="col-8 mb-2">
                <input type="text" pattern="[0-9]{10}" class="form-control form-row text-left" name="telegram_chat_Id" id="telegram_chat_Id" placeholder="Telegram Chat ID" value="{{ old('telegram_chat_Id') }}" autocomplete="false">
              </div>
              <div class="col-4 mb-2">
                <a href="{{config('custom.custom.telegram_bot_join')}}" target="_blank" type="button" id="getChatID" class="form-control form-row btn-success">Get Chat ID</a>
              </div>
            </div>
            <P class="error email_err m-0"></P>
            <input type="hidden" class="form-control form-row" name="referal_code" id="referal_code" placeholder="Enter Referal Mobile Number" value="{{ $referal_check->referral_id }}" maxlength="10"><input type="hidden" class="form-control form-row" name="admin_referal_code" id="admin_referal_code" placeholder="Enter System Access Code" value="{{ $referal_check->admin_slug }}">
            <P class="error code_err m-0"></P>
            <div class="form-group mb-2 form-row">
              <div class="col">
                <input type="password" class="form-control form-row text-left" name="my_mpin" id="my_mpin" placeholder="Set mPIN" maxlength="4" autocomplete="false" required>
              </div>
              <div class="col">
                <input type="password" class="form-control form-row text-left" name="confirm_my_mpin" id="confirm_my_mpin" placeholder="Confirm mPIN" maxlength="4" autocomplete="false" required>
              </div>
            </div>
            <div class="form-group form-row mb-0">
              <div class="col-12 mb-2 d-flex gap-1">
                <input type="text" class="form-control form-row text-left" name="telegram_id" id="telegram_id" placeholder="Telegram ID" value="">
                <button type="button" id="telegram_id_btn" class="btn auth-form-btn text-white px-3 py-2 m-0 w-auto">Verify</button>
              </div>
            </div>
            <P class="error mpin_err m-0"></P>
            <div class="text-center form-group mb-0">
              <button type="button" id="registerBtn" class="btn auth-form-btn text-white">Register</button>
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
            <div class="form-group form-row mb-2">
              <div class="col-12 mb-2">
                <input type="text" pattern="[0-9]{10}" class="form-control form-row text-left" name="mobile_number" id="mobile_number" placeholder="Mobile Number" maxlength="10" value="{{ old('mobile_number') }}" autocomplete="false">
              </div>
            </div>
            <div class="form-group form-row mb-0 otp-section" id="otp-section">
              <div class="otp-input-group d-flex align-items-center justify-content-center">
                <input type="text" class="otp-input" maxlength="1" name="otp[]">
                <input type="text" class="otp-input" maxlength="1" name="otp[]">
                <input type="text" class="otp-input" maxlength="1" name="otp[]">
                <input type="text" class="otp-input" maxlength="1" name="otp[]">
                <input type="text" class="otp-input" maxlength="1" name="otp[]">
                <input type="text" class="otp-input" maxlength="1" name="otp[]">
              </div>
            </div>
            <div class="form-group form-row mb-2">
              <button type="button" id="get_otp" class="form-control form-row btn-success">Get OTP</button>
              <button type="button" id="verify_mobile" class="form-control form-row btn-success">Verify Mobile</button>
              <center><a href="" id="resendOtpBtn"><u>Resend OTP?</u></a></center>
            </div>
            <P class="error email_err m-0"></P>
            <div class="form-group form-row mb-0 nextstep">
              <div class="col-12 mb-2 d-flex">
                <input type="text" class="form-control form-row text-left" name="user_fname" id="user_fname" placeholder="First Name" value="{{ old('user_fname') }}" autocomplete="false">
                <input type="text" class="form-control form-row text-left" name="user_lname" id="user_lname" placeholder="Last Name" value="{{ old('user_lname') }}" autocomplete="false">
              </div>
            </div>
            <P class="error name_err m-0"></P>
            <div class="form-group form-row mb-2 nextstep">
              <div class="col-12 mb-2">
                <input type="text" class="form-control form-row text-left" name="my_upi_id" id="my_upi_id" placeholder="UPI ID" value="{{ old('my_upi_id') }}" autocomplete="false">
              </div>
            </div>
            <!--<div class="form-group form-row mb-0">-->
            <!--  <div class="col-8 mb-2">-->
            <!--    <input type="text" pattern="[0-9]{10}" class="form-control form-row text-left" name="telegram_chat_Id" id="telegram_chat_Id" placeholder="Telegram Chat ID" value="{{ old('telegram_chat_Id') }}" autocomplete="false">-->
            <!--  </div>-->
            <!--  <div class="col-4 mb-2">-->
            <!--    <a href="{{config('custom.custom.telegram_bot_join')}}" target="_blank" type="button" id="getChatID" class="form-control form-row btn-success">Get Chat ID</a>-->
            <!--  </div>-->
            <!--</div>-->
            <input type="hidden" class="form-control form-row" name="referal_code" id="referal_code" placeholder="Enter Referal Mobile Number" value="{{ $invitation_mobile }}" maxlength="10" @if(isset($invitation_mobile) && $invitation_mobile !=null) readonly @endif autocomplete="false"><input type="hidden" class="form-control form-row" name="admin_referal_code" id="admin_referal_code" placeholder="Enter System Access Code" value="{{$invitation_ID}}" @if(isset($invitation_ID) && $invitation_ID !=null) readonly @endif autocomplete="false">
            <P class="error code_err m-0"></P>
            <div class="form-group mb-2 form-row nextstep">
              <div class="col d-flex">
                <input type="password" class="form-control form-row text-left" name="my_mpin" id="my_mpin" placeholder="Set mPIN" maxlength="4" autocomplete="false" required>
                <input type="password" class="form-control form-row text-left" name="confirm_my_mpin" id="confirm_my_mpin" placeholder="Confirm mPIN" maxlength="4" autocomplete="false" required>
              </div>
              <div class="col">
              </div>
            </div>
            <P class="error mpin_err m-0"></P>
            <div class="text-center mt-2 nextstep">
              <button type="button" id="registerBtn" class="btn auth-form-btn text-white">Register</button>
            </div>
          </form>
          @endif

          <div class="seprator"></div>
          <div class="mt-1 mb-2">
            <span id="siteseal">
              <script async="" type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=2gqQMOxnoyXrA7J9uoghOodRZmWSAdJVhXoELNzA9WvSL5kS2MydfWEGsoK9"></script>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection

  @section('vendor-script')
  <!-- vendor files -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/forms/validation/jquery.validate.min.js') }}"></script>
  @endsection

  @section('page-script')
  <script>
    $(document).ready(function() {
      var uid;
      var base_url = "{{url('/')}}";
      $('#get_otp').click(function() {
        if ($('#mobile_number').val() == null || $('#mobile_number').val().length != 10) {
          $('.email_err').text('Please Enter Valid Mobile Number.');
          return false;
        } else {
          $.ajax({
            // url: jqForm.attr('action'),
            url: base_url + "/common/send-otp",
            method: 'POST',
            data: {
              "_token": "{{ csrf_token() }}",
              mobileNumber: $('#mobile_number').val(),
              pagename: 'registerpage'
            },
            success: function(response) {
              if (response.status == 'success') {
                toastr.success('OTP sent successfully');
                uid = response.uid;
                $('#get_otp').css('display', 'none');
                $('#otp-section').css('display', 'block');
                $('#verify_mobile').css('display', 'block');
                $('#resendOtpBtn').css('display', 'block');
              } else {
                toastr.error(response.message);
                window.setTimeout(function() {
                    window.location.href = response.redirect_url; 
                }, 4000);
              }
            }
          });
        }
      });

      // OTP Verification Code
      $('#verify_mobile').click(function() {
        var otp = $('.otp-input').map(function() {
          return $(this).val();
        }).get().join('');
        if (otp.length === 6) {
          $.ajax({
            // url: jqForm.attr('action'),
            url: base_url + "/common/verify-otp",
            method: 'POST',
            data: {
              "_token": "{{ csrf_token() }}",
              resetOtp: otp,
              userid: uid
            },
            success: function(response) {
              if (response.status == 'success') {
                toastr.success('OTP Verified successfully');
                $('#get_otp').css('display', 'none');
                $('#otp-section').css('display', 'none');
                $('#verify_mobile').css('display', 'none');
                $('#resendOtpBtn').css('display', 'none');
                $('.nextstep').css('display', 'block');
              } else {
                toastr.error('Invalid or Expired OTP');
              }
            }
          });
        } else {
          toastr.error('Enter received OTP on Mobile number');
        }
      });

      // Resend OTP button click event
      $('#resendOtpBtn').click(function(e) {
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
      var otpSection = $('#otp-section');
      var otpInputs = otpSection.find('.otp-input');
      // OTP input change event
      $('.otp-input').on('input', function(e) {
        var currentInput = $(this);
        var otp = $('.otp-input').map(function() {
          return $(this).val();
        }).get().join('');

        if (otp.length === 6) {
          //   loginBtn.show();
        } else {
          //   loginBtn.hide();
          //   if (currentInput.next().length) {
          //      currentInput.next().focus(); // Focus on the next OTP input field
          //   }
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
      $('#registerBtn').click(function() {
        if ($('#user_fname').val() == null || $('#user_fname').val().length <= 2) {
          $('.name_err').text('User First Name Required.(Atleast 3 letters.)');
          return false;
        } else {
          $('.name_err').text('');
        }
        if ($('#user_lname').val() == null || $('#user_lname').val().length <= 2) {
          $('.name_err').text('User Last Name Required.(Atleast 3 letters.)');
          return false;
        } else {
          $('.name_err').text('');
        }
        if ($('#mobile_number').val() == null || $('#mobile_number').val().length != 10) {
          $('.email_err').text('Please Enter Valid Mobile Number.');
          return false;
        } else {
          $('.email_err').text('');
        }
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var userUPI = $('#my_upi_id').val();
        if (userUPI == null || userUPI.length <= 13) {
          $('.email_err').text('Valid UPI ID Required.');
          return false;
        } else {
          $('.email_err').text('');
        }
        if ($('#my_mpin').val() !== $('#confirm_my_mpin').val() || $('#confirm_my_mpin').val().length != 4) {
          $('.mpin_err').text('Entered valid 4 digit mPIN only');
          return false;
        } else {
          $('.mpin_err').text('');
        }
        $('#AjaxLoader').show();
        $.ajax({
          // url: jqForm.attr('action'),
          url: base_url + "/common/verify-upi",
          method: 'POST',
          data: {
            "_token": "{{ csrf_token() }}",
            userUPI: userUPI
          },
          success: function(response) {
            if (response.status == 'success') {
              $('#registrationForm').submit();
            } else {
              toastr.error('Invalid UPI Id');
              $('#AjaxLoader').hide();
            }
          }
        });
      });
    });
  </script>
  @endsection