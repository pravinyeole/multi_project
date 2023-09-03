@extends('layouts/common_template')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <center>
                        <h4 class="card-title" style="text-transform: uppercase;">All Admin & User List</h4>
                    </center>
                    <div class="table-responsive">
                        <table class="table table-striped common-table">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>Role</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>Refferal Id</th>
                                    <th>{{__("labels.action")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($all_users))
                                @foreach($all_users AS $key => $au)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>Role</td>
                                    <td>Username</td>
                                    <td>Email</td>
                                    <td>Mobile No</td>
                                    <td>Refferal Id</td>
                                    <td>{{__("labels.action")}}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    function openModel(user_id, type) {
        if (user_id <= 0 || user_id == null) {
            alert('pppppppppp');
            return false;
        }
        // var endpoint = base_url+'/'+$(this).data('url')+'/status';
        var endpoint = '{{ route("users.update-status") }}';
        var token = $("input[name='_token']").val();
        var message = "Are you sure you want to change the status?";
        var id = user_id;
        var type = type;
        bootbox.confirm({
            title: "Status",
            message: message,
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-secondary'
                }
            },
            callback: function(result) {
                if (result == true) {
                    $('#loader').show();
                    $.ajax({
                        url: endpoint,
                        method: 'POST',
                        data: {
                            '_token': token,
                            'id': id,
                            'type': type,
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            if (data.title == 'Error') {
                                $('#loader').hide();
                                toastr.error(data.message, data.title);
                            } else {
                                toastr.success(data.message, data.title);
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        }
                    })
                }
            }
        });
    }
</script>
@endsection