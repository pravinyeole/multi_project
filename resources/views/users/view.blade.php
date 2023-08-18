
@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
{{-- Vendor Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection

@section('content')
<!-- Validation -->
<section class="bs-validation">
    <div class="row">
        <!-- jQuery Validation -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> {{__("labels.user.view")}}</h4>
                    <div class="dt-action-buttons text-right">
                        <div class="dt-buttons d-inline-flex">
                            <a href="{{url('users')}}"> <button class="btn btn-danger" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-toggle="modal" data-target="#modals-slide-in"><span>{{__("labels.back")}}</span></button></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="jquery-val-form" method="POST" action="{{url('users/update')}}">
                        @csrf
                        <div class="row">
                            {{-- @if(Auth::user()->user_type == 'A' || Auth::user()->user_type == 'SA' )
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-default-name">{{__("labels.user.org_name")}}<span class="text-danger">*</span></label>
                            <select class="form-control" id="organization_id" readonly name="organization_id" required>
                                <option value="">Select Insurance Agency</option>
                                @if(isset($organizations) && count($organizations)>0)
                                @foreach($organizations as $organization)
                                @php $sel = ''; @endphp
                                @if(isset($user->insurance_agency_id) && $user->insurance_agency_id==$organization->insurance_agency_id)
                                @php $sel = 'selected="selected"'; @endphp
                                @endif
                                <option value="{{$organization->insurance_agency_id }}" {{$sel}}>{{$organization->insurance_agency_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                </div>
                @else
                <input type="hidden" name="insurance_agency_id" value="{{Auth::user()->insurance_agencies->insurance_agency_id}}">
                @endif --}}
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-name">{{__("labels.user.user_fname")}}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" readonly id="user_fname" name="user_fname" placeholder="First Name" value="{{$user->user_fname}}">
                        <input type="hidden" class="form-control" readonly id="user_id" name="user_id" value="{{$user->id}}">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-name">{{__("labels.user.user_lname")}}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" readonly id="user_lname" name="user_lname" placeholder="Last Name" value="{{$user->user_lname}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-email">{{__("labels.user.user_email")}}<span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" readonly class="form-control" placeholder="Email" value="{{$user->email}}">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="row">
                        <div class="col-md-4 pr-0">
                            <div class="form-group">
                                <label class="form-label" for="user_country_code">Country Code<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" maxlength="4" id="user_country_code" name="user_country_code" value="{{ $user->user_country_code }}" readonly />
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label" for="user_phone_no">Phone No<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" maxlength="14" minlength="14" id="user_phone_no" name="user_phone_no" value="{{ $user->user_phone_no }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!in_array(Session('USER_TYPE'),['SA','A']))
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-email">{{__("labels.user.user_role")}}<span class="text-danger">*</span></label>
                        @php
                        $userRole = $user->getRole();
                        // $role = ($userRole == 'U') ? 'User' :  (($userRole == 'T') ? 'Team Admin' : (($userRole == 'OA') ? 'Insurance Agency Admin' : 'N/A') )
                        if($userRole == 'U'){
                            $role = 'User';
                        }elseif($userRole == 'T'){
                            $role = 'Team Admin';
                        }
                        elseif($userRole == 'OA'){
                            $role = 'Insurance Agency Admin';
                        }
                        elseif($userRole == 'O'){
                            $role = 'Primary Agency Admin';
                        }
                        else{
                            $role = 'N/A';
                        }

                        @endphp
                        <input type="email" id="email" name="email" readonly class="form-control" placeholder="Email" value="{{$role}}">
                    </div>
                </div>
                @endif
                {{-- <div class="col-sm-6">--}}
                {{-- <div class="form-group">--}}
                {{-- <label class="form-label" for="basic-default-password">{{__("labels.user.user_password")}}</label>--}}
                {{-- <div class="input-group form-password-toggle input-group-merge">--}}
                {{-- <input type="password" id="password" name="password" minlength="8" class="form-control"   placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>--}}
                {{-- <div class="input-group-append">--}}
                {{-- <div class="input-group-text cursor-pointer">--}}
                {{-- <i data-feather="eye"></i>--}}
                {{-- </div>--}}
                {{-- </div>--}}
                {{-- </div>--}}
                {{-- </div>--}}
                {{-- </div>--}}
                <!-- @if(Auth::user()->is_role_assign == "Y" || Auth::user()->user_type == 'U')
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-name">Teams<span class="text-danger">*</span></label>
                        <select class="form-control select2 " disabled id="teamid" name="teamid[]" multiple required>
                            <option value="all">all</option>
                            @if(isset($teams) && count($teams)>0)
                            @foreach($teams as $team)
                            @php $sel = ''; @endphp
                            @if(in_array($team->team_id, $cids))
                            @php $sel = 'selected="selected"'; @endphp
                            @endif
                            <option value="{{$team->team_id }}" {{ $sel }}>{{$team->team_name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                @endif -->
            </div>

            </form>
        </div>
    </div>
    </div>
    <!-- /jQuery Validation -->
    </div>
    @if(Auth::user()->user_type == 'A' || Auth::user()->user_type == 'SA' )
    <div class="row">
        <div class="col-sm-12" id="permission-block">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="title">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="32" height="32" viewBox="0 0 32 32" style=" fill:#808080;">
                            <path d="M 16 3 C 12.15625 3 9 6.15625 9 10 L 9 13 L 6 13 L 6 29 L 26 29 L 26 13 L 23 13 L 23 10 C 23 6.15625 19.84375 3 16 3 Z M 16 5 C 18.753906 5 21 7.246094 21 10 L 21 13 L 11 13 L 11 10 C 11 7.246094 13.246094 5 16 5 Z M 8 15 L 24 15 L 24 27 L 8 27 Z"></path>
                        </svg> Permissions <span class="text-danger"></span> <small class="text-danger permission-error" style="display:none;">(Atleast one checkbox is checked)</small>
                    </h4>
                </div>
                <div class="card-datatable">
                    <table class="table table-striped table-bordered" id="permissionTable">
                        <thead>
                            <tr>
                                <th>Insurance Agency Name</th>
                                <th>Role</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    @elseif((in_array(Session('USER_TYPE'), ['O','OA','T'])) && $user->getRole() != 'OA' && $user->getRole() != 'O')
    <div class="row" id="responsive-datatable">
        <div class="col-sm-12" id="permission-block">
            <div class="card">
                <div class="card-header">
                    <h4 class="title">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="32" height="32" viewBox="0 0 32 32" style=" fill:#808080;">
                            <path d="M 16 3 C 12.15625 3 9 6.15625 9 10 L 9 13 L 6 13 L 6 29 L 26 29 L 26 13 L 23 13 L 23 10 C 23 6.15625 19.84375 3 16 3 Z M 16 5 C 18.753906 5 21 7.246094 21 10 L 21 13 L 11 13 L 11 10 C 11 7.246094 13.246094 5 16 5 Z M 8 15 L 24 15 L 24 27 L 8 27 Z"></path>
                        </svg> Permissions <small class="text-danger permission-error" style="display:none;">(Atleast one checkbox is checked)</small>
                    </h4>
                </div>
                <div class="card-datatable">
                    <table class="table table-striped table-bordered" id="permissionTable">
                        <thead>
                            <tr>
                                <th scope="col">Team Name</th>
                                <th scope="col">No Access</th>
                                <th scope="col">User/Member</th>
                                <th scope="col">Team Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                            <tr>
                                <td scope="row">{{$team->team_name}}</td>
                                <td>{{ Form::checkbox('no_access['.$team->team_id.']', '1', (!in_array($team->team_id, $cids) && !in_array($team->team_id, $adminAccess) ) ? true : false, ['data-team-id' =>$team->team_id, 'class' => 'user-'.$team->team_id. ' permissionCheckbox', 'disabled']) }}</td>
                                <td>{{ Form::checkbox('team_access['.$team->team_id.']', '1',  (in_array($team->team_id, $cids)) ? true : false, ['data-team-id' =>$team->team_id, 'class' => 'teamuser-'.$team->team_id. ' permissionCheckbox','disabled'])  }}</td>
                                <td>{{ Form::checkbox('team_admin['.$team->team_id.']', '1', (in_array($team->team_id, $adminAccess)) ? true : false, ['data-team-id' =>$team->team_id, 'class' => 'teamadmin-'.$team->team_id. ' permissionCheckbox', 'disabled']) }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    @endif
</section>
<!-- /Validation -->
@endsection

@section('vendor-script')
<!-- vendor files -->
<script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
@endsection
@section('page-script')
<script>
    $(function() {
        const windowURL =  window.location.href;
        const n = windowURL.lastIndexOf('/');
        const userKey = windowURL.substring(n + 1);
        const sessionRole = '<?php echo Session('USER_TYPE') ?>';
        if(sessionRole == 'A' || sessionRole == 'SA'){
            $("#permissionTable").DataTable({
            processing: true,
            serverSide: true,
            dom:
                    '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12  col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            ajax: {
                    url : "{{ url('users/view') }}"+'/'+userKey,
                    data: function ( data ) {
                        data.timeZone   = $("input[name='timeZone']").val();
                    }
                },
            'columns' : [
                {data: 'insurance_agency_id'},
                {data: 'role'},
                {data: 'created_at'}
            ]
        })
        }
        
    });
</script>
@endsection