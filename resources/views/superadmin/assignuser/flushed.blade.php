<!-- common_template/contentLayoutMaster -->
@extends('layouts/common_template')

@section('title', $title)
@section('vendor-style')
@endsection

@section('content')
<style>
    .checkAll,
    .subBtn {
        position: relative;
        left: 300px;
    }

    .subBtn,
    .noshow {
        display: none;
    }
</style>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                @if(isset($flushedList))
                <div class="card-body">
                    <div class="card-body">
                        <div id="message_div">
                            <div class="alert alert-danger alert-dismissible noshow" role="alert">
                                <strong class="dangertext"></strong>
                            </div>
                            <div class="alert alert-success alert-dismissible noshow" role="alert">
                                <strong class="successtext"></strong>
                            </div>
                        </div>
                        <div class="col-sm-12 row">
                            <div class="scrolldiv">
                                <div class="row col-sm-12">
                                    <div class="row col-sm-3">
                                        <button class="btn btn-primary btn-sm checkAll">Check All</button>
                                    </div>
                                    <div class="row col-sm-3">
                                        <button class="btn btn-success btn-sm subBtn">Submit</button>
                                    </div>
                                </div>
                                <table class="table table-striped common-table">
                                    <thead>
                                        <tr>
                                            <th>Check Box</th>
                                            <th>Created By</th>
                                            <th>Mobile ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($flushedList as $key => $flid)
                                        <tr>
                                            <td><input type="checkbox" class="commonCheck" name="flushedIds[]" data-mobileid="{{$flid->mobile_id}}"></td>
                                            <td>{{$flid->user_fname.' '.$flid->user_lname}}</td>
                                            <td>{{$flid->mobile_id}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        var checkedValues = [];
        $('.checkAll').click(function() {
            if ($('.commonCheck:checked').length) {
                $('.checkAll').text('Check All');
                $('.subBtn').css('display', 'none');
                $('.commonCheck').prop('checked', false);
            } else {
                $('.checkAll').text('Uncheck All');
                $('.subBtn').css('display', 'block');
                $('.commonCheck').prop('checked', true);
            }
        });
        $('.commonCheck').change(function() {
            if ($(this).is(':checked')) {
                $('.subBtn').css('display', 'block');
            } else {
                if ($('.commonCheck:checked').length == 0) {
                    $('.subBtn').css('display', 'none');
                }
            }
        });
        $('.subBtn').click(function() {
            checkedValues = [];
            if ($('.commonCheck:checked').length > 0) {
                $('.commonCheck:checked').each(function() {
                    var mobileId = $(this).attr('data-mobileid');
                    if (checkedValues.indexOf(mobileId) === -1) {
                        checkedValues.push(mobileId);
                    }
                });
                if (confirm('Are you Sure to flushed ?')) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    jQuery.ajax({
                        type: "POST",
                        url: base_url + "/superadmin/saveflush",
                        data: {
                            _token: CSRF_TOKEN,
                            'checkedValues': checkedValues
                        },
                        success: function(data) {
                            var obj = data;
                            if (obj.status == 'success') {
                                $('.alert-success').removeClass('noshow');
                                $('.successtext').text(obj.message);
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                $('.alert-danger').removeClass('noshow');
                                $('.dangertext').text('Somthing went wrong, Try Again');
                            }
                        }
                    });
                }
            }
        })
    });
</script>
@endsection