
@extends('layouts/common_template')

@section('title', $title)

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection


@section('content')
    <!-- Responsive Datatable -->
    <section id="responsive-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">{{__("labels.user.list")}}</h4>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons d-inline-flex">
                                @if (Session::get('USER_TYPE') == 'T' || Session::get('USER_TYPE') == 'O')
                                <a href="{{url('users/map-user')}}" > <button class="dt-button create-new btn btn-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-toggle="modal" data-target="#modals-slide-in"><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus mr-50 font-small-4"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>{{__("labels.user.addnew")}}</span></button></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row col-12">
                        <!-- @if(Session::get('USER_TYPE') == 'SA' || Session::get('USER_TYPE') == 'A')
                        <div class="col-md-3">
                            <label>Insurance Agency </label>
                            <select id="agency" class=" form-control">
                            <option value="">Select</option>
                            @if($agency_list->isNotEmpty())
                                @foreach($agency_list as $agency)
                                <option value="{{$agency->insurance_agency_id}}">{{$agency->insurance_agency_name}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                        @endif -->


                        <div class="col-md-3">
                            <label>Status </label>
                            <select id="status" class="form-control" >
                            <option value="">Select</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-datatable">
                        <table class="table table-striped table-bordered" id="table_users">
                            <thead>
                            <tr>
                                <th>{{__("labels.no")}}</th>
                                <th></th>
                                <th>{{__("labels.user.user_fname")}}</th>
                                <th>{{__("labels.user.user_lname")}}</th>
                                <th>{{__("labels.user.user_email")}}</th>
                                @if(Session::get('USER_TYPE') != 'O')
                                <!-- <th>{{__("labels.user.org_name")}}</th> -->
                                @else
                                <th>{{__("labels.user.user_role")}}</th>
                                @endif
                                <th>{{__("labels.user.createdby")}}</th>
                                <th>{{__("labels.created_at")}}</th>
                                <th>{{__("labels.user.user_last_login")}}</th>
                                <th>{{__("labels.status")}}</th>
                                <th>{{__("labels.action")}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">{{__("labels.user.invited_users_list")}}</h4>
                    </div>

                    <div class="card-datatable">
                        <table class="table table-striped table-bordered" id="invited_users">
                            <thead>
                            <tr>
                                <th>{{__("labels.no")}}</th>
                                <th>{{__("labels.user.user_email")}}</th>
                                <th>{{__("labels.user.user_role")}}</th>
                                <th>{{__("labels.team.team")}}</th>
                                @if(Session::get('USER_TYPE') == 'A' || Session::get('USER_TYPE') == 'SA')
                                <th>{{__("labels.organization.org_name")}}</th>
                                @endif
                                <th>{{__("labels.user.send_by")}}</th>
                                <th>{{__("labels.user.invited_date_time")}}</th>
                                <th>{{__("labels.action")}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Responsive Datatable -->
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
    <script>
        $(document).ready(function () {
            var table = $('#table_users').DataTable({
                processing: true,
                serverSide: true,
                dom:
                    '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12  col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                ajax: {
                    url : "{{ url('users') }}",
                    data: function ( data ) {
                        data.timeZone   = $("input[name='timeZone']").val();
                    }
                },

                "columns": [
                    {data: 'DT_RowIndex'},
                    {data: 'status',visible:false},
                    {data: 'user_fname'},
                    {data: 'user_lname'},
                    {data: 'email'},
                    <?php if(Session::get('USER_TYPE') != 'O') { ?>
                    // {data: 'get_org_name.insurance_agency_name'},
                    <?php }else{?>
                    {data: 'user_type'},
                    <?php } ?>
                    {data: 'userOnlyName'},
                    {data: 'created_at'},
                    {data: 'user_last_login'},
                    {data: 'user_status', orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    // {data: "get_org_name.insurance_agency_id",   visible: false},
                    // {data: "insurance_team.team_id",   visible: false},
                ],
                initComplete: function () {
                     this.api().columns(1).every( function () {
                        var column = this;
                        $('#status').on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search( val ? '^'+val+'$' : '', true, false ).draw();
                        });
                    });
                    this.api().columns(11).every( function () {
                      var column = this;
                        $('#agency').on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search( val ? '^'+val+'$' : '', true, false ).draw();
                        });
                    });
                    this.api().columns(12).every( function () {
                      var column = this;
                        $('#teams').on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search( val ? '^'+val+'$' : '', true, false ).draw();
                        });
                    });

                },
            });

            var table1 = $('#invited_users').DataTable({
                processing: true,
                serverSide: false,
                dom:
                    '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12  col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                ajax: {
                    url : "{{ url('invited_users') }}",
                    data: function ( data ) {
                        data.timeZone   = $("input[name='timeZone']").val();
                    }
                },
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "columns": [
                    {data: 'DT_RowIndex'},
                    {data: 'email'},
                    {data: 'role'},
                    {data: 'team'},
                    <?php if(Session::get('USER_TYPE') == 'A' || Session::get('USER_TYPE') == 'SA') { ?>
                    {data: 'insurance_agency_name'},
                    <?php } ?>
                    {data: 'send_by'},
                    {data: 'created_at'},
                    {data: 'action', orderable: false}
                ],
                
            });


        });
        @php if(Session::get('USER_TYPE') == 'O'){ @endphp
          $(document).ready(function(){
            @php if(Auth::user()->is_role_assign == "Y"){ @endphp
                var agencyId ="{{ Auth::user()->insurance_agency_id }}";
            @php    }else{ @endphp
                var agencyId = "{{ Auth::user()->insurance_agencies->insurance_agency_id }}"
            @php  } @endphp

            var base_url = "{{ url('') }}";
            $.post(base_url + '/users/geTeamsbyInsuranceAgency', { insurance_agency_id: agencyId, _token: "{{ csrf_token() }}"}, function (result) {
                $('#teams').html(result);
            });
          });
        @php } @endphp

          $(document).on('change', '#agency', function(e) {
            var base_url = "{{ url('') }}";
            $.post(base_url + '/users/geTeamsbyInsuranceAgency', { insurance_agency_id: $(this).val(), _token: "{{ csrf_token() }}"}, function (result) {
                $('#teams').html(result);
            });
        });


        $(document).on('click', '.viewTeam', function (e ) {
        var id = $(this).data('id');
        var token = jQuery("input[name='_token']").val();
        // alert(id);
            $.ajax({
                url:  "{{url('users/getTeamByUser')}}",
                method: 'POST',
                data: {
                    '_token': token,
                    'id': id,
                },
                dataType: "json",
                success: function (result) {
                    var msg = '';
                    if(result.data!=''){
                        msg += "<br/>"+result.data;
                    }else{
                        var msg = "No Team Found";
                    }
                    bootbox.confirm({
                        message: msg,
                        title: "Team Name",
                        buttons: {
                            confirm: {
                                label: 'Ok',
                                className: 'btn-primary'
                            },
                            cancel: {
                                label: 'Close',
                                className: 'btn-danger'
                            }
                        },
                        callback: function (res) {

                        }
                    });
                }
            })
        });

         $(document).on('click', '.org_login', function (e ) {
            var message = "Your current session will expire, Please click below to continue.";
            var id = $(this).data('id');
            bootbox.confirm({
                title: "Login as User",
                message: message,
                buttons: {
                    confirm: {
                        label: 'Continue',
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: 'Close',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result == true){
                        var urls="{{url('users/loginUser/')}}/"+id;
                        window.location.href=urls;
                    }
                }
            });
        });
    </script>

@endsection
