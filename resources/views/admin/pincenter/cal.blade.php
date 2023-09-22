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
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
