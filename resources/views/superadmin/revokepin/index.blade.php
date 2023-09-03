@extends('layouts/common_template')

@section('title', $title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
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
<div class="content-wrapper" id="responsive-datatable">
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="card-title">Search user</h4>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons d-inline-flex">
                                {{-- <p class="m-1">Pins:  <span class="text-primary">{{$getNoOfPins->pins ?? ''}}</span></p> --}}
                            </div>
                        </div>
                    </div>
                        <div class="col-md-12">
                            <select id="search" class="form-control select2" name="search">
                                <option value="">Select a user</option>
                                {{-- Add your options here --}}
                                @foreach ($getAllUser as $user)
                                <option value="{{$user->id}}">{{$user->user_fname}}{{$user->user_lname}}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card" id="userDetailsDiv" style="display: none; margin-top:20px;">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="card-title">User Details &nbsp;<span id="error" style="font-size: 13px; color: red;"></span>
                        </h4>
                        <div>
                            <!-- User details will be displayed here -->
                            <form id="editState" method="POST" class="editState" action="{{url('superadmin/save_revoke')}}" autocomplete="off">
                                @csrf
                                <div class="card">
                                    <input type="hidden" id="user_id" name="user_id" value="">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="first_name">First Name<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder='{{__("labels.department.title")}}' value="" maxlength="100" required="" pattern="[A-Za-z\s]+" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="last_name">Last Name<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder='{{__("labels.department.title")}}' value="" maxlength="100" required="" pattern="[A-Za-z\s]+" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="mobile_number">Mobile Number<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder='{{__("labels.department.title")}}' value="" maxlength="10" required="" pattern="[0-9]{10}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="email">Email Id<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="email" name="email" placeholder='{{__("labels.department.title")}}' value="" maxlength="100" required="" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="email">Pins<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="no_of_pins" name="no_of_pins" value="" maxlength="100" required="" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="email">Revoke Pins<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="revoke_pins" name="revoke_pins" value="" maxlength="100" required="" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="submit" class="btn btn-success">Revoke</button>
                                                <a href="{{ url('superadmin/admin') }}"> <button type="button" class="btn btn-danger">{{__("labels.cancel")}}</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('.select2-selection--single').hide();
        // Show user details when a user is selected
        $('#search').on('change', function() {
            var userId = $(this).val();
            if (userId) {
                // Make an AJAX request to fetch user details based on the selected user ID
                $.ajax({
                    url: 'user/details/' + userId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('.select2-selection__rendered').hide();
                        if (response.success) {
                            var userDetails = response.data; // Assuming the response contains user details in the 'data' field

                            // Update the input fields with the retrieved user details
                            $('#user_id').val(userDetails.user_id);
                            $('#first_name').val(userDetails.user_fname);
                            $('#last_name').val(userDetails.user_lname);
                            $('#no_of_pins').val(userDetails.no_of_pins);
                            $('#mobile_number').val(userDetails.mobile);
                            $('#email').val(userDetails.email);

                            // Enable or disable the 'revoke_pins' input field based on the 'no_of_pins' value
                            if (userDetails.no_of_pins == 0) {
                                $('#revoke_pins').prop('disabled', true);
                                $('#error').text('(Please add the pins to revoke)');
                            } else {
                                $('#revoke_pins').prop('disabled', false);
                                $('#error').text('');
                            }
                            // Show the 'userDetailsDiv'
                            $('#userDetailsDiv').show();
                        } else {
                            console.error('Failed to fetch user details.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            } else {
                // If no user is selected, hide the 'userDetailsDiv'
                $('#userDetailsDiv').hide();
            }
        });
    });
</script>
@endsection