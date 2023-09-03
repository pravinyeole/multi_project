@extends('layouts/common_template')

@section('title', $title)

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta1/css/all.css" integrity="YOUR-INTEGRITY-CODE" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .sublist-item {
            background-color: #f2f2f2;
            margin-bottom: 5px;
            padding: 5px;
        }
    </style>
@endsection

@section('page-script')
    {{-- jQuery UI for drag and drop --}}
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            // Make the Get Help and Send Help lists sortable (drag and drop)
            $("#get-help-list, #send-help-list").sortable({
                connectWith: ".sortable",
                items: "li",
                receive: function (event, ui) {
                    var senderId = ui.sender.attr('id');
                    var receiverId = ui.item.closest('ul').attr('id');
    
                    // Check if the item is moved from Send Help list to Get Help sublist
                    if (senderId === 'send-help-list' && receiverId === 'get-help-list') {
                        // Check if the Get Help sublist already has two sub-items
                        if ($("#get-help-list .sublist-item").length >= 2) {
                            ui.sender.sortable('cancel');
                            toastr.error('Maximum two users required.');
                        } else {
                            ui.item.addClass('sublist-item'); // Add a CSS class to change the color
                        }
                    }
    
                    // Update the ID of the sublist
                    ui.item.find("ul").attr("id", "send-help-sublist");
                },
                remove: function (event, ui) {
                    // Remove the CSS class when the item is removed from the Get Help sublist
                    if (ui.item.hasClass('sublist-item')) {
                        ui.item.removeClass('sublist-item');
                    }
                }
            });
    
            // Assign User button click event
            $('.btn-primary').on('click', function () {
                var sendHelpItems = [];
    
                $('#send-help-list li').each(function () {
                    var sendHelpName = $(this).text().trim();
                    var sendHelpId = $(this).data('user-id');
                    sendHelpItems.push({
                        id: sendHelpId,
                        name: sendHelpName
                    });
                });
    
                var getHelpItems = [];
    
                $('#get-help-list li').each(function () {
                    var getHelpName = $(this).find('strong').text().trim();
                    var getHelpId = $(this).data('user-id');
                    var sublistItems = [];
    
                    $(this).find('.sublist-item').each(function () {
                        sublistItems.push($(this).text().trim());
                    });
    
                    getHelpItems.push({
                        id: getHelpId,
                        name: getHelpName,
                        sublist: sublistItems
                    });
                });
    
                var data = {
                    sendHelp: sendHelpItems,
                    getHelp: getHelpItems
                };
    
                // Send the data via AJAX
                $.ajax({
                    url: '{{ route("superadmin.save-assigne-user") }}',
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        // Handle the response here
                        console.log(response);
                    },
                    error: function (xhr, status, error) {
                        // Handle the error here
                        console.log(error);
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
    <section id="responsive-datatable">
        <div class="card">
            <div class="card-header">
                {{-- <h4 class="card-title">Get Help / Send Help</h4> --}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Get Help</h4>
                            </div>
                            <div class="card-body">
                                <ul id="get-help-list" class="sortable">
                                    @foreach ($getOldUser as $user)
                                        <li data-user-id="{{ $user->id }}">
                                            <strong>{{ $user->mobile_id }} ({{ $user->user_fname }} {{ $user->user_lname }})</strong>
                                            <ul class="sublist"></ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Send Help</h4>
                            </div>
                            <div class="card-body">
                                <ul id="send-help-list" class="sortable">
                                    @foreach ($getRecentlyJoinUser as $user)
                                        <li data-user-id="{{ $user->id }}">
                                            {{ $user->user_fname }} {{ $user->user_lname}} ({{ $user->mobile_number }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button class="btn btn-primary">Assign User</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
