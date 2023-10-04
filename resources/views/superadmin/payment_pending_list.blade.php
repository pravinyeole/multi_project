@extends('layouts/common_template')

@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                    <div class="page-title" style="text-align: center;">
                        <h4><b>Pending Payment List</b></h4>
                    </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped display common-table" id="table_user">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>Username</th>
                                    <th>Mobile Number</th>
                                    <th>Mobile Id</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($data as $key => $val)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$val->user_fname}} {{$val->user_lname}}</td>
                                    <td>{{$val->mobile_number}}</td>
                                    <td>{{$val->mobile_id}}</td>
                                    <td>{{date('d-m-Y',strtotime($val->date))}}</td>
                                    
                                </tr>
                                @php $i++; @endphp
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
