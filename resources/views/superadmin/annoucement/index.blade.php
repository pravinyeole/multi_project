<!DOCTYPE html>
@extends('layouts/common_template')

@section('title', $title)

@section('page-style')
{{-- Page Css files --}}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endsection

@section('content')

<div class="content-wrapper">
    <div class="col-12">
        <form id="addDepartment" method="POST" class="addDepartment" action="{{url('superadmin/announce_create')}}" autocomplete="off">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Announcement<span style="font-size: 13px"></span></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="row col-sm-12">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">Type<span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" aria-label="Default select All">
                                        <option value="All">All</option>
                                        <option value="Admin">Admin</option>
                                        <option value="User">User</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="first_name">Start Date<span class="text-danger">*</span></label>
                                    <div id="datepicker-popup" class="start_date input-group date datepicker navbar-date-picker datepicker-popup">
                                        <span class="input-group-addon input-group-prepend border-right">
                                            <span class="icon-calendar input-group-text calendar-icon"></span>
                                        </span>
                                        <input type="text" name="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="first_name">End Date<span class="text-danger">*</span></label>
                                    <div id="datepicker-popup" class="end_date input-group date datepicker navbar-date-picker datepicker-popup">
                                        <span class="input-group-addon input-group-prepend border-right">
                                            <span class="icon-calendar input-group-text calendar-icon"></span>
                                        </span>
                                        <input type="text" name="end_date" class="form-control">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">{{__("labels.submit")}}</button>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Announcement Desc:<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="editor" name="anno" rows="10" placeholder="Please Enter Announcement for Users and admin" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="content-wrapper">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <center>
                    <h4 class="card-title" style="text-transform: uppercase;">All Annoucement </h4>
                </center>
                <div class="table-responsive">
                    <table class="table table-striped" id="table_user">
                        <thead>
                            <tr>
                                <th>{{__("labels.no")}}</th>
                                <th>Type</th>
                                <th>Announcemnet Desc</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js" integrity="sha512-BmM0/BQlqh02wuK5Gz9yrbe7VyIVwOzD1o40yi1IsTjriX/NGF37NyXHfmFzIlMmoSIBXgqDiG1VNU6kB5dBbA==" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        tinymce.init({
            selector: '#editor',
            height: "350px",
            width: "700px"
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // DataTable for organization
        if (document.getElementById("table_user")) {
            var table = $('#table_user').DataTable({
                processing: true,
                serverSide: true,
                order: [0, 'DESC'],
                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12 col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                ajax: {
                    url: base_url + "/superadmin/announcement",
                    data: function(data) {}
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'announce',
                        name: 'announce'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        }
    });
</script>
@endsection