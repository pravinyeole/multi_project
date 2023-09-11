@extends('layouts/common_template')

@section('title', $title)

@section('page-style')
    <style type="text/css">
        .table td, .table th{
            padding: 0.72rem 0.5rem !important;
        }
        #termTable td {
            vertical-align: top;
        }
         .error {
        color: red;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }
    </style>
@endsection
 
@section('content')
<section class="bs-validation">
    <div class="row">
        <div class="col-12">
            <form id="addDepartment" method="POST" class="addDepartment" action="{{url('normal_user/save-send-help')}}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4><center>Send Help </center><span style="font-size: 13px;" class="pl-2 text-warning">{{ucwords($getPaymentStatus->payment_status ?? '')}}</span></h4>
                    </div>
                    
                    @if (Session::has('error'))
                    <div class="alert alert-error alert-dismissible" role="alert">
                        <strong>Error !</strong> {{ session('error') }}
                    </div>
                    @endif
                    @if (Session::has('success'))
                    <div class="alert alert-error alert-dismissible" role="alert">
                        <strong>Success !</strong> {{ session('success') }}
                    </div>
                    @endif
                    <div class="card-body">
                        <div class="row">
                                <input type="hidden" name="user_mobile_id" value="{{$mobileId}}">
                                <input type="hidden" name="sender_id" value="{{Auth::user()->id}}">
                                <input type="hidden" name="recevier_id" value="{{$senderUserDetails->id}}">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">First Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder='Enter First Name' value="{{$senderUserDetails->user_fname}}" maxlength="100" required="" pattern="[A-Za-z\s]+" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Last Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder='Enter Last Name' value="{{$senderUserDetails->user_lname}}" maxlength="100" required="" pattern="[A-Za-z\s]+" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="mobile_number">Mobile Number<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder='Enter Mobile Number' value="{{$senderUserDetails->mobile_number}}" maxlength="10" required="" pattern="[0-9]{10}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email Id<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder='Enter Email Id' value="{{$senderUserDetails->email}}" maxlength="100" required="" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{-- @dd($senderUserDetails); --}}
                                    <label class="form-label" for="payment_mode">Select Payment Mode<span class="text-danger">*</span></label>
                                    @if (isset($senderUserDetails->google_pay))
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_mode" id="google_pay" value="google_pay" @if(old('google_pay', $senderUserDetails->google_pay) === 'google_pay') checked @endif>
                                        <label class="form-check-label" for="google_pay">Google Pay</label>
                                    </div>
                                    @endif
                                    @if (isset($senderUserDetails->phone_pay))

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_mode" id="phone_pay" value="phone_pay" @if(old('phone_pay', $senderUserDetails->phone_pay) === 'phone_pay') checked @endif>
                                        <label class="form-check-label" for="phonepay">PhonePay</label>
                                    </div>
                                    @endif
                                    @if (isset($senderUserDetails->upi))

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_mode" id="upi" value="upi" @if(old('upi', $senderUserDetails->upi) === 'upi') checked @endif>
                                        <label class="form-check-label" for="upi">UPI</label>
                                    </div>
                                    @endif
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_mode" id="other_payment_mode" value="other_payment_mode" @if(old('other_payment_mode', $senderUserDetails->other_payment_mode) === 'other_payment_mode') checked @endif>
                                        <label class="form-check-label" for="other_payment_mode">Other Payment Mode</label>
                                    </div>
                                    @if (isset($senderUserDetails->paytm))

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_mode" id="paytm" value="paytm" @if(old('payment_mode', $senderUserDetails->paytm) === 'paytm') checked @endif>
                                        <label class="form-check-label" for="paytm">Paytm</label>
                                    </div>
                                    @endif
                                    
                                </div>
                            </div>
                            {{-- @if(isset($getPaymentStatus))
                                <div class="col-sm-6">
                                    <div>
                                        <label class="form-label">Attached Screenshot Preview:</label>
                                        <img src="{{ asset('storage/attached_screenshots/'.basename($getPaymentStatus->attachment)) }}" alt="Attached Screenshot" width="200px">
                                    </div>
                                </div>
                            @endif --}}
                        
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="transaction_number">Transaction Number<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="transaction_number" name="transaction_number" required>
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="attached_screenshot">Attached Screenshot<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="attached_screenshot" name="attached_screenshot" required="">
                                    <img id="image_preview" src="#" alt="Attached Screenshot" width="200px" style="display: none;">
                                </div>
                            </div> -->
                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-6">
                                <center><button type="submit" class="btn btn-success">{{__("labels.submit")}}</button>
                                <a href="{{ url('normal_user') }}"> <button type="button" class="btn btn-danger">{{__("labels.cancel")}}</button></a>
                            </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('page-script')
    <script>
        $(document).ready(function () {
            // Initialize form validation
            $('#addDepartment').validate({
                rules: {
                    first_name: {
                        required: true,
                        pattern: /^[A-Za-z\s]+$/
                    },
                    last_name: {
                        required: true,
                        pattern: /^[A-Za-z\s]+$/
                    },
                    mobile_number: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        digits: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    no_of_pins:{
                        required: true,
                        number: true
                    }
                },
                messages: {
                    first_name: {
                        required: 'Please enter the first name.',
                        pattern: 'Please enter a valid first name.'
                    },
                    last_name: {
                        required: 'Please enter the last name.',
                        pattern: 'Please enter a valid last name.'
                    },
                    mobile_number: {
                        required: 'Please enter a 10-digit mobile number.',
                        minlength: 'The mobile number must be 10 digits long.',
                        maxlength: 'The mobile number must be 10 digits long.',
                        digits: 'Please enter a valid mobile number.'
                    },
                    email: {
                        required: 'Please enter an email address.',
                        email: 'Please enter a valid email address.'
                    }
                    no_of_pins: {
                        required: 'Please enter the number of pins.',
                        number: 'Please enter a valid number.'
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
    
    {{-- <script src="{{ asset('js/custom/department.js') }}"></script> --}}
@endsection

@section('page-script')
    <script>
        $(document).ready(function () {
            // Function to handle file input change event
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#image_preview')
                            .attr('src', e.target.result)
                            .show(); // Display the image preview
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Trigger image preview when file input changes
            $('#attached_screenshot').on('change', function () {
                readURL(this);
            });

        });
    </script>
@endsection

