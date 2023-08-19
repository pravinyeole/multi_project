<!-- common_template/contentLayoutMaster -->
@extends('layouts/common_template')

@section('title', $title)
@section('vendor-style')
@endsection

@section('content')
<section id="responsive-datatable">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Assign Users</h4>
        </div>
        <div class="card-body mt-3">
            <form class="form-horizontal" action="{{ route('superadmin.save-assigne-user') }}" method="POST" id="manaualAssign">
                @csrf
                @if(isset($getOldUser))
                <input type="hidden" name="type" value="GH">
                @foreach ($getOldUser as $user)
                <div class="form-group row">
                    <label value="{{ $user->id }}_{{ $user->mobile_id }}" class="col-sm-4 control-label">
                        <center>{{ $user->mobile_id }} ({{ $user->user_fname }} {{ $user->user_lname }})</center>
                    </label>
                    <div class="col-sm-4">
                        <select name="{{ $user->id }}_{{ $user->mobile_id }}[]" class="form-control select2" multiple="multiple">
                            @foreach ($getRecentlyJoinUser as $recentUser)
                                @if($user->id != $recentUser->id)
                                    <option value="{{ $recentUser->id }}">{{ $recentUser->user_fname }} {{ $recentUser->user_lname }} ({{ $recentUser->mobile_number }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
                @endforeach
                @endif
            
            <div class="form-check">
                <center><input class="form-check-input custom-checkbox" type="checkbox" id="auto-assign-checkbox">
                <label class="form-check-label ml-1" for="auto-assign-checkbox">Auto Assign</label>
                <button type="submit" form="manaualAssign" class="btn btn-primary ml-5" id="assign-user-btn">Assign User</button></center>
            </div>
            </form>
        </div>

    </div>
    </div>
</section>
@endsection

@section('page-script')
{{-- Add Select2 JS and custom script --}}
<script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> -->
<script>
    $(document).ready(function() {
        // Initialize Select2 on the dropdown menus
        $('.select2').select2();

        // Auto Assign checkbox change event
        $('#auto-assign-checkbox').on('change', function() {
            if ($(this).is(':checked')) {
                // Auto assign users
                $('.select2').each(function() {
                    var selectElement = $(this);
                    var options = selectElement.find('option');

                    // Clear previous selections
                    selectElement.val(null).trigger('change');

                    // Select the first two options
                    options.slice(0, 2).prop('selected', true);
                    selectElement.trigger('change');
                });
            } else {
                // Clear selections
                $('.select2').val(null).trigger('change');
            }
        });

        // Assign User button click event
        $('#assign-user-btn').on('click', function() {
            var getHelpUsers = [];
            var sendHelpUsers = [];

            $('.select2').each(function() {
                var selectedOptions = $(this).val();

                if (selectedOptions) {
                    if (selectedOptions.length !== 2) {
                        toastr.error('Please select exactly two users in each dropdown.');
                        return false; // Exit the loop
                    }

                    if ($(this).attr('name') === 'get-help-user[]') {
                        getHelpUsers.push(selectedOptions);
                    } else if ($(this).attr('name') === 'send-help-user[]') {
                        sendHelpUsers.push(selectedOptions);
                    }
                }
            });

            if (getHelpUsers.length === 2 && sendHelpUsers.length === 2) {
                // Perform your desired action with the selected users
                console.log('Get Help Users:', getHelpUsers);
                console.log('Send Help Users:', sendHelpUsers);
            }
        });

        // Update event handler to limit the number of selected options
        $('.select2').on('select2:select', function(e) {
            var selectedOptions = $(this).val();
            if (selectedOptions.length > 2) {
                $(this).val(null).trigger('change');
                toastr.error('Please select only two users.');
            }
        });
    });
</script>
@endsection

@section('page-style')
{{-- Add custom CSS for checkbox style --}}
<style>
    .custom-checkbox {
        width: 1.5rem;
        height: 1.5rem;
    }
</style>
@endsection