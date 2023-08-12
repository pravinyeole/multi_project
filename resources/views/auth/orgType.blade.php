@extends('layouts/fullLayoutMaster')

@section('title', 'Login Page')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-v1 px-2">
  <div class="auth-inner py-2">
    <!-- Login v1 -->
    <div class="card mb-0">
      <div class="card-body">
        <a href="javascript:void(0);" class="brand-logo">
         <img src="{{asset('images/logo/logo.svg')}}" width="100%" />
          <!-- <h2 class="brand-text text-primary ml-1">Coverage Wizard</h2> -->
        </a>

        <h4 class="card-title mb-1">Welcome to Coverage Wizard! 👋</h4>
        <p class="card-text mb-2">Please choose Insurance Agency </p>
        @if (session('status'))
            <div class="alert alert-success" role="alert" style="padding: 2%">
                {{ session('status') }}
            </div>
        @endif
        <div class="card-datatable">
          <table class="table" width="100%">
            <thead>
              <tr>
                <th>Insurance Agency Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($userRoleData as $userRole)
                <tr>
                    <td>{{$userRole->insurance_agency_name}}</td>
                    <td><a href="{{url('saveUserRoleConfig')}}/{{encrypt($userRole->user_role_id)}}" class="" title="Login">
                      <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-log-in font-small-4'><path d='M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4'></path><polyline points='10 17 15 12 10 7'></polyline><line x1='15' y1='12' x2='3' y2='12'></line></svg>
                      </a>
                    </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <p class="text-center mt-2"><a href="{{url('logout')}}" class="btn btn-danger">Logout</a>
        </p>
      </div>
    </div>
    <!-- /Login v1 -->
  </div>
</div>
@endsection
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')

@endsection