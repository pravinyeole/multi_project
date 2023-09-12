@extends('layouts/common_template')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                    <div class="page-title">
                        <h4 class="card-title d-flex align-items-center justify-content-between" style="text-transform: uppercase;">Transfer Pin<span style="margin-left: 100px"><button class="btn-sm create-new btn btn-info mt-1">Pins: {{Session::get('myPinBalance')}}</button></span></h4>
                    </div>
                <div class="card-body">
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <strong>Success !</strong> {{ session('success') }}
                    </div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <strong>Error !</strong> {{ session('error') }}
                    </div>
                    @endif
                    <form action="{{url('/transferpin/transsubmit')}}" class="form-group" method="POST">
                        <div class="row">
                            @csrf
                            <div class="col-sm-4">
                                <label class="form-label" for="">Select Users To Transfer Pin</label>
                                <select class="form-control" id="trans_id" name="trans_id" required>
                                    <option selected disabled>Select User</option>
                                    @foreach($normal_udata AS $key => $nu)
                                    <option value="{{$nu->id}}">{{$nu->user_fname.' '.$nu->user_lname}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label" for="">Number of Pin</label>
                                <input type="number" class="form-control" id="trans_number" name="trans_number" required min="0">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="">Comments</label>
                                <input type="text" class="form-control" id="trans_reason" name="trans_reason">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary mt-4">Submit</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-striped common-table m-2">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>Thansfer To</th>
                                <th>Number of Pins</th>
                                <th>Reason</th>
                                <th>Thansfer Date</th>
                            </tr>
                        </thead>
                        @if(count($tarnsferHistory))
                        <tbody>
                            @foreach($tarnsferHistory AS $key => $tu)
                            <tr>
                                <td>{{($key+1)}}</td>
                                <td>{{$tu->user_fname.' '.$tu->user_lname}}</td>
                                <td>{{$tu->trans_count}}</td>
                                <td>{{$tu->trans_reason}}</td>
                                <td>{{date('d F Y',strtotime($tu->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script></script>
@endsection