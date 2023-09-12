@extends('layouts/common_template')

@section('title', $title)

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                    <div class="page-title">
                        <h4>Revoke Pin</h4>
                    </div>
                <div class="card-body">
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <strong>Success !</strong> {{ session('success') }}
                    </div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <strong>Error !</strong> {{ session('error') }}
                    </div>
                    @endif
                    <form id="editState" method="POST" class="editState" action="{{url('superadmin/save_revoke')}}" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">Select User<span class="text-danger">*</span></label>
                                    <select id="user_id" class="form-control select2" name="user_id">
                                        <option value="">Select a user</option>
                                        @foreach ($getAllUser as $user)
                                        <option value="{{$user->id}}">{{$user->user_fname}}{{$user->user_lname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                            <div class="col-sm-12 mt-2">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="{{ url('superadmin/admin') }}"> <button type="button" class="btn btn-danger" style="width:100%">{{__("labels.cancel")}}</button></a>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-success" style="width:100%">Revoke</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if(count($revokeHistory))
                    
                    <div class="table-responsive">
                    <table class="table table-striped common-table m-2">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>User Name</th>
                                <th>Number of Pins</th>
                                <th>Reason</th>
                                <th>Revoked Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revokeHistory AS $key => $tu)
                            <tr>
                                <td>{{($key+1)}}</td>
                                <td>{{$tu->user_fname.' '.$tu->user_lname}}</td>
                                <td>{{$tu->revoke_count}}</td>
                                <td>{{$tu->revoke_reason}}</td>
                                <td>{{date('d F Y',strtotime($tu->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    @endif
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
        $('#user_id').on('change', function() {
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
                        } else {
                            console.error('Failed to fetch user details.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }
        });
    });
</script>
@endsection