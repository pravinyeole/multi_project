@extends('layouts/common_template')

@section('title', $title)

@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                    <div class="page-title">
                        <h4>Direct Referance Users List</h4>
                    </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped display common-table" id="table_user">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>Username</th>
                                    <th>Refferal Id</th>
                                    <th>SAC</th>
                                    <th>Refferal Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($data as $key => $val)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$val->user_fname}} {{$val->user_lname}}</td>
                                    <td>{{$val->referral_id}}</td>
                                    <td>{{$val->admin_slug}}</td>
                                    <td> @php $cryptStr= Crypt::encryptString($val->admin_slug);
                                                $cryptUrl= url('/register/').'/'.$cryptStr;
                                            @endphp
                                        <button type="button" id="copyBtn" onclick="copyText('{{$cryptUrl}}')" class="btn btn-success btn-fw p-2 copyBtn">Copy Refferal URL</button></td>
                                    
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
