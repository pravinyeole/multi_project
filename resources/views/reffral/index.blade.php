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
                                    <th>Refferal Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($data as $key => $val)
                                <tr>
                                    @php
                                    $myadminSlug = ($val->user_role == 'U') ? $val->admin_slug : $val->user_slug;
                                    $cryptmobile= base64_encode($val->mobile_number);
                                    $cryptSlug= base64_encode($myadminSlug);
                                    $cryptUrl= url('/register/').'/'.$cryptmobile.'/'.$cryptSlug;
                                    @endphp
                                    <td>{{$i}}</td>
                                    <td>{{$val->user_fname}} {{$val->user_lname}}</td>
                                    <td>{{$val->referral_id}}</td>
                                    <td> 
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
