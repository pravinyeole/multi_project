@extends('layouts/common_template')

@section('title', $title)


@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card" style="padding: 2%;">
            <div class="page-title">
                    <h4>
                        PIN Request
                    </h4>
                </div>
                <div class="card-body">
                <div class="row">
                    <form id="requestPinForm" action="{{ route('request-pin.send-request') }}" method="POST">
                        @csrf
                        <div class="col-sm-12 row">
                            <input type="hidden" name="admin_slug" value="{{$adminAssingToLoginUser->admin_slug ?? ''}}">
                            <div class="col-xs-7 col-sm-6">
                                <label class="form-label" for="org_name">Enter Pins<span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control" id="no_of_pin_requested" name="no_of_pin_requested" placeholder='Enter no of pins required' required="">
                            </div>
                            <div class="col-xs-5 col-sm-5 mt-3">
                                <button type="submit" class="btn btn-success mt-2">Send Request</button>
                            </div>
                        </div>
                    </form>
                </div>
                @if(isset($requestedPins))
                    <div class="table-responsive">

                <table class="table table-striped common-table">
                    <thead>
                        <tr>
                            <th>Pin Request ID</th>
                            <th>SAC</th>
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
                @endif
            </div>
            </div>
        </div>
    </div>
</div>

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