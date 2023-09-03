@extends('layouts/common_template')

@section('title', $title)

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
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
    </style>
@endsection
 
@section('content')
<section class="bs-validation">
    <div class="row">
        
        <div class="col-12">
            <form id="editState" method="POST" class="editState" action="{{url('city/update')}}" autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{$class->class_id}}"/>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{__("labels.class.view")}}<span style="font-size: 13px"></span></h4>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="code">{{__("labels.class.class_name")}}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="sortname" name="sortname" placeholder='{{__("labels.groupcode.code")}}' value="{{$class->class_name}}" maxlength="100" required="" minlength="2" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' readonly>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="selected_state">{{__("labels.status")}}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="sortname" name="sortname" placeholder='{{__("labels.groupcode.code")}}' value="{{$class->class_status}}" maxlength="100" required="" minlength="2" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                {{-- <button type="submit" class="btn btn-success">{{__("labels.submit")}}</button> --}}
                                <a href="{{ url('class') }}"> <button type="button" class="btn btn-danger">{{__("labels.back")}}</button></a>
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
    <script src="{{ asset('js/custom/country.js') }}"></script>
@endsection