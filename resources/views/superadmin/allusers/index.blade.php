@extends('layouts/common_template')

@section('title', $title)

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
                        <table class="table table-striped" id="table_user">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>Role</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>{{__("labels.action")}}</th>
                                </tr>
                            </thead>
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
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // DataTable for organization
        if (document.getElementById("table_user")) {
            var table = $('#table_user').DataTable({
                processing: true,
                serverSide: true,
                order: [1, 'ASC'],
                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12 col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                ajax: {
                    url: base_url + "/superadmin/allusers",
                    data: function(data) {}
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_role',
                        name: 'user_role'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile_number',
                        name: 'mobile_number'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        }
    });

    function openModel(user_id,type) {
        if(user_id <= 0 || user_id == null){
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