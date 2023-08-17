@extends('layouts/common_template')

@section('title', $title)

@section('vendor-style')
{{-- Vendor Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
<!-- <style>
       .select2-container--default .select2-selection--multiple:before {
    content: ' ';
    display: block;
    position: absolute;
    /* border-color: #888 transparent transparent transparent; */
    border-color: #88888869 transparent transparent transparent;
    border-style: solid;
    border-width: 5px 4px 0 4px;
    height: 0;
    right: 6px;
    margin-left: -4px;
    margin-top: -2px;top: 50%;
    width: 0;cursor: pointer
}

.select2-container--open .select2-selection--multiple:before {
    content: ' ';
    display: block;
    position: absolute;
    border-color: transparent transparent #888 transparent;
    border-width: 0 4px 5px 4px;
    height: 0;
    right: 6px;
    margin-left: -4px;
    margin-top: -2px;top: 50%;
    width: 0;cursor: pointer
}
    </style> -->
@endsection

@section('content')
<!-- Validation -->
<section class="bs-validation">
    <div class="row">
        <!-- jQuery Validation -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> {{__("labels.user.addnew")}} <span style="font-size: 13px">*(User has to
                            add their country code and phone number during 1st login.)</span></h4>
                </div>
                <div class="card-body">
                    <form id="jquery-val-form" method="POST" action="{{url('users/save')}}" autocomplete="off">
                        @csrf
                        <div class="row">
                            {{-- @if(Session::get('USER_TYPE') == 'A' || Session::get('USER_TYPE') == 'SA' )
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label" for="basic-default-name">{{__("labels.user.org_name")}}<span class="text-danger">*</span></label>
                            <select class="form-control" id="insurance_agency_id" name="insurance_agency_id" required>
                                <option value="" disabled selected>Select Insurance Agency</option>
                                @if(isset($organizations) && count($organizations)>0)
                                @foreach($organizations as $organization)
                                <option value="{{$organization->insurance_agency_id }}">{{$organization->insurance_agency_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                </div>
                {{-- @else --}}
                {{-- <input type="hidden" name="insurance_agency_id" value="{{Auth::user()->insurance_agencies->insurance_agency_id}}"> --}}
                {{-- @endif --}}
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-name">{{__("labels.user.user_fname")}}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="user_fname" name="user_fname" placeholder="First Name" value="{{old('user_fname')}}" maxlength="50">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-name">{{__("labels.user.user_lname")}}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="user_lname" name="user_lname" placeholder="Last Name" value="{{old('user_lname')}}" maxlength="50">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-email">{{__("labels.user.user_email")}}<span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{old('email')}}" autocomplete="new-email" maxlength="50">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-password">{{__("labels.user.user_password")}}<span class="text-danger">*</span></label>
                        <div class="input-group form-password-toggle input-group-merge">

                            <input type="password" id="password" name="password" minlength="8" class="form-control" value="{{old('password')}}" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" autocomplete="new-password" />
                            <div class="input-group-append">
                                <div class="input-group-text cursor-pointer">
                                    <i data-feather="eye"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->is_role_assign == "Y" || Session::get('USER_TYPE') == 'U' || Session::get('USER_TYPE') == 'O' || Session::get('USER_TYPE') == 'OA' || Session::get('USER_TYPE') == 'T' || Session::get('USER_TYPE') == 'SA' || Session::get('USER_TYPE') == 'A')
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-name">Role<span class="text-danger">*</span></label>
                        <select class="form-control select2 " id="role" name="role" required>
                            <option value="">Select</option>
                            <option value="U">User</option>
                            <option value="T">Team Admin</option>
                            @if(Session('USER_TYPE') != 'T')
                                        <option value="OA">Insurance Agency Admin</option>
                            @endif

                        </select>
                    </div>
                </div>
                <!-- <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label" for="basic-default-name">Teams<span class="text-danger">*</span></label>
                        <select class="form-control select2 " id="teamid" name="teamid[]" multiple required>
                            <option value="all">all</option>
                            @if(isset($teams) && count($teams)>0)
                            @foreach($teams as $team)
                            <option value="{{$team->team_id }}">{{$team->team_name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div> -->
                <!-- <div class="col-sm-12" id="permission-block" style="display: none;">
                <div class="card border">
                    <div class="card-header">
                        <h4 class="title">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="32" height="32" viewBox="0 0 32 32" style=" fill:#808080;">
                            <path d="M 16 3 C 12.15625 3 9 6.15625 9 10 L 9 13 L 6 13 L 6 29 L 26 29 L 26 13 L 23 13 L 23 10 C 23 6.15625 19.84375 3 16 3 Z M 16 5 C 18.753906 5 21 7.246094 21 10 L 21 13 L 11 13 L 11 10 C 11 7.246094 13.246094 5 16 5 Z M 8 15 L 24 15 L 24 27 L 8 27 Z"></path>
                        </svg> Permissions
                        </h4>
                    </div>
                    <div class="">
                            <table class="table table-striped">
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
                                        <th scope="row">{{$team->team_name}}</th>
                                        <td>{{ Form::checkbox('no_acccess['.$team->team_id.']', '1', false) }}</td>
                                        <td>{{ Form::checkbox('team_access['.$team->team_id.']', '1', false) }}</td>
                                        <td>{{ Form::checkbox('team_admin['.$team->team_id.']', '1', false) }}</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                    </div>

                </div>
                </div> -->

                @endif

            </div>

            

        </div>
    </div>
    </div>
    <!-- /jQuery Validation -->
    </div>
    <div class="row">
        <div class="col-sm-12" id="permission-block"  @if(Session('USER_TYPE') != 'T') style="display: none;" @endif>
            <div class="card border">
                <div class="card-header">
                    <h4 class="title">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="32" height="32" viewBox="0 0 32 32" style=" fill:#808080;">
                            <path d="M 16 3 C 12.15625 3 9 6.15625 9 10 L 9 13 L 6 13 L 6 29 L 26 29 L 26 13 L 23 13 L 23 10 C 23 6.15625 19.84375 3 16 3 Z M 16 5 C 18.753906 5 21 7.246094 21 10 L 21 13 L 11 13 L 11 10 C 11 7.246094 13.246094 5 16 5 Z M 8 15 L 24 15 L 24 27 L 8 27 Z"></path>
                        </svg> Permissions <span class="text-danger">*</span> <small class="text-danger permission-error" style="display:none;">(Atleast one checkbox is checked)</small>
                    </h4>
                </div>
                <div class="">
                    <table class="table table-striped" id="permissionTable">
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
                                <td>{{ Form::checkbox('no_access['.$team->team_id.']', '1', false,['data-team-id' =>$team->team_id, 'class' => 'user-'.$team->team_id. ' permissionCheckbox']) }}</td>
                                <td>{{ Form::checkbox('team_access['.$team->team_id.']', '1', false,['data-team-id' =>$team->team_id,'class' => 'teamuser-'.$team->team_id.' permissionCheckbox']) }}</td>
                                <td>{{ Form::checkbox('team_admin['.$team->team_id.']', '1', false, ['data-team-id' =>$team->team_id,'class' => 'teamadmin-'.$team->team_id.' permissionCheckbox', $teamPermission]) }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary">{{__("labels.submit")}}</button>
                    <a href="{{url('users')}}"> <button type="button" class="btn btn-danger">{{__("labels.cancel")}}</button></a>
                </div>
            </div>
    </form>
</section>
<!-- /Validation -->
@endsection

@section('vendor-script')
<!-- vendor files -->
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endsection
@section('page-script')
<script>
    $(function() {
        var userRole = '<?php echo Session('USER_TYPE') ?>';
        var teamAdmin = (userRole == 'T') ? true : false;
        $(".permissionCheckbox").on('click', function(e) {
            $(".permission-error").hide();
            let checkBoxName = e.target.name;
            checkBoxName = checkBoxName.split('[')[0];
            if (checkBoxName == 'no_access') {
                if (e.target.checked) {
                    $(this).attr('checked', true);
                    $(`.teamuser-${$(this).attr('data-team-id')}`).prop('checked', false).attr('disabled', true);
                    $(`.teamadmin-${$(this).attr('data-team-id')}`).prop('checked', false).attr('disabled', true);
                } else {
                    $(`.teamuser-${$(this).attr('data-team-id')}`).attr('disabled', false);
                   if(!teamAdmin){
                        $(`.teamadmin-${$(this).attr('data-team-id')}`).attr('disabled', false);
                    }
                }
            } else if (checkBoxName == 'team_access') {
                if (e.target.checked) {
                    $(this).attr('checked', true);
                    $(`.user-${$(this).attr('data-team-id')}`).prop('checked', false).attr('disabled', true);
                    $(`.teamadmin-${$(this).attr('data-team-id')}`).prop('checked', false).attr('disabled', true);
                } else {
                    $(`.user-${$(this).attr('data-team-id')}`).attr('disabled', false);
                   if(!teamAdmin){
                        $(`.teamadmin-${$(this).attr('data-team-id')}`).attr('disabled', false);
                    }
                }
            } else if (checkBoxName == 'team_admin') {
                if (e.target.checked) {
                    $(this).attr('checked', true);
                    $(`.user-${$(this).attr('data-team-id')}`).prop('checked', false).attr('disabled', true);
                    $(`.teamuser-${$(this).attr('data-team-id')}`).prop('checked', false).attr('disabled', true);
                } else {
                    $(`.user-${$(this).attr('data-team-id')}`).attr('disabled', false);
                    $(`.teamuser-${$(this).attr('data-team-id')}`).attr('disabled', false);
                }
            }
        })
        var jqForm = $('#jquery-val-form');
        jqForm.on('submit', function(e){
            if( $("#role").length != 0 || $("#permissionTable").length != 0 ){
                if($("#role").val() != 'OA'){
                let checkbox = false;
                $("#permissionTable > tbody > tr > td").each(function(index, tr){
                    let checked = $(tr).find('input:checkbox').is(':checked');
                    if(checked){
                        checkbox = true;
                        return false;
                    }
                    
                })
                if(!checkbox){
                    $(".permission-error").show();
                }
                return checkbox;
            }
            }
            
               
        });
        if (jqForm.length) {
            jQuery.validator.addMethod("validate_email", function(value, element) {
                if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
                    return true;
                } else {
                    return false;
                }
            }, "Please enter valid email");
            jQuery.validator.addMethod("require_field", function(value, element) {
                if (value.trim() == '') {
                    return false;
                }
                return true;
            }, "This field is required.");
            jQuery.validator.addMethod("strongPassword", function(value) {
                return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
            }, "The password must contain at least 1 number, 1 lower case letter,1 upper case letter and 1 special symbol");
            jqForm.validate({
                rules: {
                    'user_fname': {
                        required: true,
                        maxlength: 50,
                        require_field: true,
                    },
                    'user_lname': {
                        required: true,
                        maxlength: 50,
                        require_field: true,
                    },
                    'email': {
                        required: true,
                        maxlength: 50,
                        email: true,
                        validate_email: true,
                        require_field: true,
                    },
                    'password': {
                        minlength: 8,
                        required: true,
                        require_field: true,
                        strongPassword: true,
                    },
                    'team_id': {
                        required: true,
                    }
                }
            });
        }
        $("#role").on('change', function() {
            if (this.value == 'U' || this.value == 'T') {
                $("#permission-block").show();
            } else {
                $(".permissionCheckbox:checkbox:checked").each(function(i,checkbox){
                    $(checkbox).prop('checked',false);
                })
                $("#permission-block").hide();
            }
        })
    });
</script>
@endsection