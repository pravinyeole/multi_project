@extends('layouts/common_template')
@php
use Illuminate\Support\Facades\Crypt;
@endphp
@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="dt-action-buttons text-right">
                </div>
                <div class="col-sm-12 dt-buttons d-inline-flex">
                    <div class="col-sm-6">
                        <a href="{{url('superadmin/admin_create_form')}}">
                            <button class="btn btn-primary btn-fw m-2" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-toggle="modal" data-target="#modals-slide-in"><span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus mr-50 font-small-4">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>{{__("labels.department.addnew")}}</span>
                            </button>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <h2 class="card-title" style="text-transform: uppercase;">Admin List</h2>
                    </div>
                </div>
                @if(count($admin_data))
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped common-table">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Refferal Id</th>
                                    <th>Refferal Link</th>
                                    <th>{{__("labels.action")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admin_data AS $key => $au)
                                @php
                                    $myadminSlug = ($au->user_role == 'U') ? $au->admin_slug : $au->user_slug;
                                    $cryptmobile= Crypt::encryptString($au->mobile_number);
                                    $cryptSlug= Crypt::encryptString($myadminSlug);
                                    $cryptUrl= url('/register/').'/'.$cryptmobile.'/'.$cryptSlug;
                                    $badgeSt = ($au->user_status == 'Inactive') ? 'danger':'success';
                                    $cryptID= Crypt::encryptString($au->id);
                                @endphp
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>{{$au->user_fname.' '.$au->user_lname}}</td>
                                    <td>{{$au->email}}</td>
                                    <td>
                                    <button type="button" class="badge badge-success">{{$au->user_status}}</button>    
                                    </td>
                                    <td>{{$au->user_slug}}</td>
                                    <td><button type="button" id="copyBtn" onclick="copyText('{{$cryptUrl}}')" class="btn btn-{{$badgeSt}} btn-fw p-2">Copy Refferal URL</button></td>
                                    <td><a href="{{url('/superadmin/admin/edit/'.$cryptID)}}" class='item-edit text-blue' title='Edit'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit font-small-4'>
                                                <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                                                <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                                            </svg></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        $('.addDepartment').on('submit', function(e) {
            if ($(".addDepartment").valid()) {
                $('#loader').show();
                return true;
            }
        });
        $('.editDepartment').on('submit', function(e) {
            if ($(".editDepartment").valid()) {
                $('#loader').show();
                return true;
            }
        });
    });
</script>
@endsection