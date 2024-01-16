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
    .table td,
    .table th {
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
            <form id="addDepartment" method="POST" class="addDepartment" action="{{url('normal_user/save-get-help-form')}}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Get Help for {{$mobileId ?? ''}} <span style="font-size: 13px;" class="pl-2 text-warning">{{ucwords($getPaymentStatus->status ?? '')}}</span></h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="mobile_id" value="{{$mobileId}}">
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
                            <input type="hidden" name="sender_id" value="{{Auth::user()->id}}">
                            <input type="hidden" name="recevier_id" value="{{$senderUserDetails->id}}">
                            <div class="col-sm-6">
                                <div>
                                    <label class="form-label">Attached Screenshot Preview:</label>
                                    @if(isset($getPaymentStatus))
                                    <img src="{{ asset('storage/attached_screenshots/'.basename($getPaymentStatus->attachment)) }}" alt="Attached Screenshot" width="200px">
                                    @else
                                    <label class="form-label">Attachment Not Found</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success">Approve</button>
                                <a href="{{ url('normal_user') }}"> <button type="button" class="btn btn-danger">{{__("labels.cancel")}}</button></a>
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
    $(document).ready(function() {
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
                no_of_pins: {
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
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
{{-- <script src="{{ asset('js/custom/department.js') }}"></script> --}}
@endsection