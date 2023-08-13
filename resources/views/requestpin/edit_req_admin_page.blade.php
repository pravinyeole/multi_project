@extends('layouts/common_template')

@section('title', $title)

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  {{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}"> --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/base/plugins/forms/pickers/form-flat-pickr.css') }}">
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
            <form id="addDepartment" method="POST" class="addDepartment" action="{{url('pins-request/update-request')}}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><span class="text-primary">{{$user->user_fname}} {{$user->user_lname}} </span>Requested Of Pin <span style="font-size: 13px;" class="pl-2 text-warning">{{ucwords($getPaymentStatus->status ?? '')}}</span></h4>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">First Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder='Enter First Name' value="{{$user->user_fname}}" maxlength="100" required="" pattern="[A-Za-z\s]+" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Last Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder='Enter Last Name' value="{{$user->user_lname}}" maxlength="100" required="" pattern="[A-Za-z\s]+" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="mobile_number">Mobile Number<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder='Enter Mobile Number' value="{{$user->mobile_number}}" maxlength="10" required="" pattern="[0-9]{10}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email Id<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder='Enter Email Id' value="{{$user->email}}" maxlength="100" required="" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="country_id">Enter No of Pin's<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="no_of_pins" name="no_of_pins" placeholder="Enter Number of Pin's" value="{{$user->no_of_pin}}" maxlength="100" readonly>
                                </div>
                            </div>
                            <input type="hidden" name="pin_request_id" value="{{$user->pin_request_id}}">
                            <input type="hidden" name="req_user_id" value="{{$user->req_user_id}}">
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success">Approve</button>
                                <a href="{{ url('pins-request') }}"> <button type="button" class="btn btn-danger">{{__("labels.cancel")}}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
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
                    },
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
