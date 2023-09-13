@extends('layouts/common_template')

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="page-title">
            <h4>Pending Payment List</h4>
        </div>                    
        <table class="table table-striped common-table m-2">
            <thead>
                <tr>
                    <th>Sr.No.</th>
                    <th>User Name</th>
                    <th>Mobile Number </th>
                    <th>Mobile Id </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data AS $key => $tu)
                <tr>
                    <td>{{($key+1)}}</td>
                    <td>{{$tu['payment_has']['user_fname'].' '.$tu['payment_has']['user_lname']}}</td>
                    <td>{{$tu['payment_has']['mobile_number']}}</td>
                    <td>{{$tu['mobile_id']}}</td>
                    <td><a href="{{url('/normal_user/payment_accept')}}/{{$tu['payment_id']}}/{{$tu['mobile_id']}}" class="btn btn-primary" onclick="return confirm('Are you sure you would like to accept Payment?');">Payment Accept</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row" style="margin-top:20px;">
        <div class="page-title">
            <h4>Completed Payment List</h4>
        </div>                    
        <table class="table table-striped common-table m-2">
            <thead>
                <tr>
                    <th>Sr.No.</th>
                    <th>User Name</th>
                    <th>Mobile Number </th>
                    <th>Mobile Id </th>
                </tr>
            </thead>
            <tbody>
                @foreach($complted AS $key => $tu)
                <tr>
                    <td>{{($key+1)}}</td>
                    <td>{{$tu['payment_has']['user_fname'].' '.$tu['payment_has']['user_lname']}}</td>
                    <td>{{$tu['payment_has']['mobile_number']}}</td>
                    <td>{{$tu['mobile_id']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-script')
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
        $("#editState").on("submit",function(e) {
            var ava_bal = $('#no_of_pins').val();
            var rev_bal = $('#revoke_pins').val();
            
            if (ava_bal >= rev_bal) {
                
                return true;
            }
            else
            {
                alert('Pins are not equal');
                return false;
            }
            
        });

    });

    
</script>
@endsection