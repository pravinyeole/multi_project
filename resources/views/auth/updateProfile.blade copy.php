@extends('layouts/fullLayoutMaster')

@section('title', 'Update Profile')

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

        <h4 class="card-title mb-1">Welcome to Coverage Wizard. Please complete the next few steps to set up your account.</h4>
        {{-- <p class="card-text mb-2">Please fill your profile here</p> --}}

        <form class="auth-forgot-password-form mt-2" id="update_profile" method="POST" action="{{ url('two-fact-auth/updateProfileAction') }}">
          @csrf
          <div class="row">
            <div class="col-sm-4 pr-0">
                <div class="form-group">
                    <label for="user_country_code" class="form-label">Country Code</label>
                    <input type="number" class="form-control"  maxlength="3"   id="user_country_code" name="user_country_code" value="1" required placeholder="Country Code"  tabindex="1" autofocus readonly="" />
                  </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="user_phone_no" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" onkeyup="check_phone_format()"  id="user_phone_no" name="user_phone_no" value="{{ old('user_phone_no') }}" required placeholder="Phone Number"  tabindex="1" autofocus />
                  </div>
            </div>
        </div>
          <button type="submit" class="btn btn-primary btn-block" tabindex="2">Update Profile</button>
        </form>

        {{-- <p class="text-center mt-2">
          @if (Route::has('login'))
          <a href="{{ route('login') }}"> <i data-feather="chevron-left"></i> Back to login </a>
          @endif
        </p> --}}
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
    jQuery.validator.addMethod("phoneNoValidation", function(value, element) {
        if(value == "(000)000-0000" || value == "0000000000"){
        return false;
        }
        return true;
    }, "* Please enter valid phone no");

      $('#update_profile').validate({
        rules: {
            user_country_code:{
            required: true,
          },
          user_phone_no:{
            // maxlength:14,
            // minlength:14,
            phoneNoValidation:true,
            required: true
          },

        },
        messages: {
            user_country_code:{
            required: "Please country code"
          },
        //   user_phone_no:{
        //     required: "Please enter phone number",
        //     digits: "Please enter valid phone number",
        //     minlength: "Please enter valid phone number",
        //     maxlength: "Please enter valid phone number",
        //   },
        },
        errorElement: 'span',
      });

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
        // document.getElementById('user_phone_no').addEventListener('input', function (e) {
        // var x = e.target.value.replace(/\D/g, '').match(/(\d{3})(\d{3})(\d{4})/);
        // e.target.value = '(' + x[1] + ') ' + x[2] + '-' + x[3];
        // });


    function check_phone_format()
	{
		 $('#user_phone_no').on('input', function () {
			 var number = $(this).val().replace(/[^\d]/g, '');

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
	}
    </script>
@endsection
