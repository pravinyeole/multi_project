@extends('layouts/contentLayoutMaster')

@section('title', $title)

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/base/plugins/forms/pickers/form-flat-pickr.css') }}">
@endsection

@section('content')
    <section id="responsive-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Pin Request</h4>
                        <div class="dt-action-buttons text-right">
                        </div>
                    </div>
                    <form id="requestPinForm" action="{{ route('request-pin.send-request') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="hidden" name="admin_slug" value="{{$adminAssingToLoginUser->admin_slug ?? ''}}"> 
                                        <label class="form-label" for="org_name">Enter Pins<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="no_of_pin_requested" name="no_of_pin_requested" placeholder='Enter no of pins required'  maxlength="50" required="">
                                    </div>
                                </div>
                            </div>   
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-success" >Send Request</button>
                                </div>
                            </div> 
                            <br>
                            @if(isset($requestedPins))
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Pin Request ID</th>
                                                    <th>Admin Slug</th>
                                                    <th>No of Pins</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($requestedPins as $request)
                                                    <tr>
                                                        <td>{{ $request->pin_request_id }}</td>
                                                        <td>{{ $request->admin_slug }}</td>
                                                        <td>{{ $request->no_of_pin }}</td>
                                                        <td>
                                                            @if ($request->status == 'pending')
                                                                <span class="badge badge-warning">{{ $request->status }}</span>
                                                            @elseif ($request->status == 'completed')
                                                                <span class="badge badge-success">{{ $request->status }}</span>
                                                            @else
                                                                {{ $request->status }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $request->req_created_at }}</td>
                                                        <td>{{ $request->updated_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('#ref_no').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
@endsection
