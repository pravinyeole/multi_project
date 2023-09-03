@extends('layouts/common_template')
@section('title', $title)

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  {{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}"> --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/base/plugins/forms/pickers/form-flat-pickr.css') }}">
    <style type="text/css">
        .table td, .table th{
            padding: 0.72rem 0.5rem !important;
        }
        #termTable td {
            vertical-align: top;
        }
        .error {
        color: red;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }
    </style>
@endsection
 
@section('content')
<section class="bs-validation">
    <div class="row">
        
        <div class="col-12">
            <form id="editState" method="POST" class="editState" action="{{url('department/update')}}" autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{$department->department_id}}"/>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{__("labels.department.edit")}}<span style="font-size: 13px"></span></h4>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="country_id">{{__("labels.department.department_name")}}<span class="text-danger">*</span></label>
                                     <input type="text" class="form-control" id="department_name" name="department_name" placeholder='{{__("labels.department.title")}}' value="{{$department->department_name}}" maxlength="100" required="" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))'>
                                </div>
                            </div>

                             <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="name">{{__("labels.status")}}<span class="text-danger">*</span></label>
                                    <select class="form-control" id="department_status" name="department_status" required>
                                        <option value="">{{__("labels.status")}}</option>
                                        <option value="active"  @if($department->department_status == 'active') selected @endif >Active</option>
                                        <option value="inactive" @if($department->department_status == 'inactive') selected @endif>Inactive</option>
                                </select> 
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success">{{__("labels.update")}}</button>
                                <a href="{{ url('department') }}"> <button type="button" class="btn btn-danger">{{__("labels.cancel")}}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
             </form>
        </div>
    </div>
</section>
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('js/custom/city.js') }}"></script>
@endsection