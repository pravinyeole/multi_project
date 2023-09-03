 @extends('layouts/common_template')

@section('title', $title)

@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <center>
                        <h4 class="card-title">Direct Referance Users List</h4>
                    </center>
                    <div class="table-responsive">
                        <table class="table table-striped" id="table_user">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    {{-- <th>Username</th> --}}
                                    <th>Refferal Id</th>
                                    <th>Admin Slug</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($data as $key => $val)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$val->referral_id}}</td>
                                    <td>{{$val->admin_slug}}</td>
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
