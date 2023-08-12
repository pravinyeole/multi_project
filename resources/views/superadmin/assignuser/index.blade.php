@extends('layouts/contentLayoutMaster')

@section('title', $title)
@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
@endsection

@section('content')
    <section id="responsive-datatable">
        <div class="card">
            <div class="card-header">
                {{-- <h4 class="card-title">Help Section</h4> --}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Assign User</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('superadmin.save-assigne-user') }}" method="POST" id="manaualAssign">
                                    <input type="hidden" name="type" value="GH">
                                    @csrf
                                    @if(isset($getOldUser))
                                    @foreach ($getOldUser as $user)
                                        <label value="{{ $user->id }}_{{ $user->mobile_id }}">
                                            {{ $user->mobile_id }} ({{ $user->user_fname }} {{ $user->user_lname }})
                                        </label>
                                        <select name="{{ $user->id }}_{{ $user->mobile_id }}[]" class="select2" multiple="multiple">
                                            @foreach ($getRecentlyJoinUser as $recentUser)
                                                <option value="{{ $recentUser->id }}">
                                                    {{ $recentUser->user_fname }} {{ $recentUser->user_lname }} ({{ $recentUser->mobile_number }})
                                                </option>
                                            @endforeach
                                        </select>
                                    @endforeach
                                    @endif
                                </form>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input custom-checkbox mr-2" type="checkbox" id="auto-assign-checkbox">
                                            <label class="form-check-label ml-2" for="auto-assign-checkbox">Auto Assign</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" form="manaualAssign" class="btn btn-primary" id="assign-user-btn">Assign User</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page-script')
    {{-- Add Select2 JS and custom script --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2 on the dropdown menus
            $('.select2').select2();

            // Auto Assign checkbox change event
            $('#auto-assign-checkbox').on('change', function () {
                if ($(this).is(':checked')) {
                    // Auto assign users
                    $('.select2').each(function () {
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
            $('#assign-user-btn').on('click', function () {
                var getHelpUsers = [];
                var sendHelpUsers = [];

                $('.select2').each(function () {
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
            $('.select2').on('select2:select', function (e) {
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
