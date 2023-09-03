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
                <input type="hidden" name="id" value="{{$subject->subject_id}}"/>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> View Subject<span style="font-size: 13px"></span></h4>
                    </div>
                    
                     <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="name">{{__("labels.department.department_name")}}<span class="text-danger">*</span></label>
                                    <select class="form-control" id="department_id" name="department_id" required disabled>
                                    <option value="">--Please Select--</option>
                                   @foreach ($departments as $dep)
                                        <option value="{{$dep->department_id}} "@if($dep->department_id == $subject->department_id) selected @endif>{{$dep->department_name}}</option>
                                   @endforeach
                                </select> 
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="name">{{__("labels.class.class_name")}}<span class="text-danger">*</span></label>
                                    <select class="form-control" id="class_id" name="class_id" required disabled>
                                    <option value="">--Please Select--</option>
                                   @foreach ($classes as $class)
                                        <option value="{{$class->class_id}}" @if($class->class_id == $subject->class_id) selected @endif>{{$class->class_name}}</option>
                                   @endforeach
                                </select> 
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="name">{{__("labels.subject.subject_code")}}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder='{{__("labels.department.title")}}' value="{{$subject->subject_code}}" maxlength="100" required="" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="name">{{__("labels.subject.term")}}<span class="text-danger">*</span></label>
                                    <select class="form-control" id="subject_pattern" name="subject_pattern" required disabled>
                                    <option value="1" @if($subject->subject_cod == "1") selected @endif>Term I</option>
                                    <option value="2" @if($subject->subject_cod == "2") selected @endif>Term II</option>
                                    <option value="3" @if($subject->subject_cod == "3") selected @endif>Term III</option>
                                    <option value="4" @if($subject->subject_cod == "4") selected @endif>Term IV</option>
                                    <option value="5" @if($subject->subject_cod == "5") selected @endif>Term V</option>
                                    <option value="6" @if($subject->subject_cod == "6") selected @endif>Term VI</option>
                                    <option value="7" @if($subject->subject_cod == "7") selected @endif >Term VII</option>
                                    <option value="8" @if($subject->subject_cod == "8") selected @endif>Term VIII</option>
                                </select> 
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="name">{{__("labels.subject.subject_name")}}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder='{{__("labels.department.title")}}' value="{{$subject->subject_name}}" maxlength="100" required="" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' disabled>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="name">{{__("labels.subject.pattern")}}<span class="text-danger">*</span></label>
                                    <select class="form-control" id="subject_pattern" name="subject_pattern" required disabled>
                                    @for ($year = date('Y'); $year >= date('Y')-5; $year--)
                                        <option value="{{ $year }}" @if($year == $subject->subject_pattern) selected @endif>{{ $year }}</option>
                                    @endfor
                                </select>

                                </select> 
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                                <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label" for="name">{{__("labels.status")}}<span class="text-danger">*</span></label>
                                    <select class="form-control" id="subject_status" name="subject_status" required disabled>
                                    <option value="">{{__("labels.status")}}</option>
                                    <option value="active"  @if($subject->subject_status == 'acitve') selected @endif>Active</option>
                                    <option value="inactive" @if($subject->subject_status == 'inactive') selected @endif>Inactive</option>
                                </select> 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                {{-- <button type="submit" class="btn btn-success">{{__("labels.submit")}}</button> --}}
                                <a href="{{ url('subject') }}"> <button type="button" class="btn btn-danger">{{__("labels.cancel")}}</button></a>
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