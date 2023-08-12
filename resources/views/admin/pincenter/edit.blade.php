@extends('layouts/contentLayoutMaster')

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
            <form id="editState" method="POST" class="editState" action="{{url('pin_center/update')}}/{{encrypt($user->id)}}" autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{$user->id}}"/>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">View User<span style="font-size: 13px"></span></h4>
                    </div>
                   <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="country_id">First Name<span class="text-danger">*</span></label>
                                     <input type="text" class="form-control" id="user_fname" name="user_fname" placeholder='{{__("labels.department.title")}}' value="{{$user->user_fname}}" maxlength="100" disabled >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="country_id">Last Name<span class="text-danger">*</span></label>
                                     <input type="text" class="form-control" id="user_lname" name="user_lname" placeholder='{{__("labels.department.title")}}' value="{{$user->user_lname}}" maxlength="100" disabled >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="country_id">Mobile No<span class="text-danger">*</span></label>
                                     <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder='{{__("labels.department.title")}}' value="{{$user->mobile_number}}" maxlength="100" disabled >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="country_id">Email Id<span class="text-danger">*</span></label>
                                     <input type="text" class="form-control" id="email" name="email" placeholder='{{__("labels.department.title")}}' value="{{$user->email}}" maxlength="100" disabled >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label" for="country_id">Enter No of Pin's<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="no_of_pins" name="no_of_pins" placeholder="Enter Number of Pin's" value="" maxlength="100" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success">Approve</button>
                                <a href="{{ url('pin_center') }}"> <button type="button" class="btn btn-danger">{{__("labels.cancel")}}</button></a>
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
   <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endsection

@section('page-script')
 <script src="{{ asset('js/custom/class.js') }}"></script>
    <script>
        $('.select2').select2();
    </script>
@endsection