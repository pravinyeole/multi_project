@extends('layouts/common_template')

@section('content')
<style>
    .footer {
        padding: 0
    }
    .shstatus{
    font-size: medium;
    font-weight: bold;
    }
</style>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            @if(count($sendHelpData))
            <div class="card">
                <div class="page-title">
                    <h4>
                        Send Help (SH)
                    </h4>
                </div>
                <div class="card-body gray-bg">
                    @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <strong>Error !</strong> {{ session('error') }}
                    </div>
                    @endif
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <strong>Success !</strong> {{ session('success') }}
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover responsive nowrap" style="width:100%" id="table_user">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sendHelpData AS $key => $sh)
                                <?php
                                $tr = 'INRB' . substr($sh->user_mobile_id, 2);
                                if (isset($sh->upi) && $sh->upi != '') {
                                    $url = 'upi://pay?pa=' . $sh->upi . '&pn=' . $sh->user_fname . $sh->user_lname . '&cu=INR&am=' . config('custom.custom.upi_pay_amount') . '.00&tn=' . $tr;
                                }
                                ?>
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>{{date('d-M-Y',strtotime($sh->assigndate))}}</td>
                                    <td>
                                        <div class="">
                                            <a href="#update" data-target="#update" data-toggle="modal" class="link">{{$sh->user_fname}} {{$sh->user_lname}}
                                                <p class="text-muted mb-0">{{$tr}}</p>
                                            </a>
                                        </div>
                                    </td>

                                    @if(isset($sh->upi) && $sh->upi != '')
                                    <td>
                                        <form id="paynow_{{$sh->id}}" method="POST" action="{{url('help/sh_paynow')}}">
                                            @csrf
                                            <input type="hidden" name="user_id" id="user_id{{$sh->id}}" value="{{$sh->id}}">
                                            <input type="hidden" name="tran_inr" id="tran_inr{{$sh->id}}" value="{{$tr}}">
                                            <input type="hidden" name="tran_mobile" id="tran_mobile{{$sh->id}}" value="{{$sh->user_mobile_id}}">
                                            <button type="submit" class="btn btn-primary btn-sm">Pay Now</button>
                                        </form>
                                    </td>
                                    @else
                                    <td>
                                        <a href="javascript:void()" class="btn btn-danger btn-sm">UPI Not Updated By User</a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        @if(count($mycreatedids))
        <div class="col-12">
            <div class="card">
                <div class="page-title">
                    <h4>
                        Completed SH
                    </h4>
                </div>
                <div class="card-body gray-bg">
                    <div class="table-responsive">
                        <table class="table table-hover responsive nowrap" style="width:100%" id="createid_table_user">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>Created ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mycreatedids AS $key => $cr)

                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>{{$cr->mobile_id}}</td>
                                    <td>
                                        <div class="">
                                            <a href="#update" data-target="#update" data-toggle="modal" class="link">{{$cr->user_fname}} {{$cr->user_lname}}
                                                <p class="text-muted mb-0">{{$cr->mobile_number}}</p>
                                            </a>
                                        </div>
                                    </td>
                                    @php $bg = ($cr->status=='red') ? 'bisque' : (($cr->status=='yellow') ? 'blueviolet': (($cr->status=='green') ? 'chartreuse':'cadetblue')) @endphp
                                    <td><button type="button" class="btn btn-sm shstatus" style="color:{{$cr->status}};background: {{$bg}};">{{ucfirst($cr->status)}}</button></td>
                                    <td>{{date('d-M-Y',strtotime($cr->created_at))}}</td>
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

@endsection
@section('page-script')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvg/dist/browser/canvg.min.js"></script>
<script>
    // Initiate download of blob
    function download(
        filename, // string
        blob // Blob
    ) {
        if (window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveBlob(blob, filename);
        } else {
            const elem = window.document.createElement('a');
            elem.href = window.URL.createObjectURL(blob);
            elem.download = filename;
            document.body.appendChild(elem);
            elem.click();
            document.body.removeChild(elem);
        }
    }

    function svgdown(id, imname) {
        var svg = document.querySelector('#inr' + id + ' svg');
        var data = (new XMLSerializer()).serializeToString(svg);
        // We can just create a canvas element inline so you don't even need one on the DOM. Cool!
        var canvas = document.createElement('canvas');
        canvg(canvas, data, {
            renderCallback: function() {
                canvas.toBlob(function(blob) {
                    download(imname + '.png', blob);
                });
            }
        });
    }
    $(document).ready(function() {
        $('#createid_table_user').DataTable({
            processing: true,
            bLengthChange: false,
            responsive: true,
            order: [],
            responsive: true
        });
        // DataTable for organization
        if (document.getElementById("table_user")) {
            var table = $('#table_user').DataTable({
                processing: true,
                bLengthChange: false,
                responsive: true,
                order: [],
                responsive: true,

                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ]
            });

            $(".dataTables_filter input")
                .attr("placeholder", "Search here...")
                .css({
                    width: "300px",
                    display: "inline-block"
                });

            $('[data-toggle="tooltip"]').tooltip();
        };
        $("#table_user_processing").hide();
        // Enable/disable pins input and event checkbox based on checkbox click
        $(document).on('change', '.event-checkbox', function() {
            var isChecked = $(this).prop('checked');
            if ($(this).closest('tr').find('.pins-input').val().length === 0) {
                isChecked = false;
            }
            $(this).closest('tr').find('.pins-input').prop('disabled', !isChecked);
        });

        // Enable/disable event checkboxes based on pins input
        $(document).on('input', '.pins-input', function() {
            var pinsInput = $(this);
            var isChecked = pinsInput.val().length > 0;
            pinsInput.closest('tr').find('.event-checkbox').prop('disabled', !isChecked);
        });

        // Enable/disable pins input and event checkboxes for all rows
        function enableDisableInputs(enable) {
            var rows = table.rows().nodes().to$();
            rows.each(function() {
                var pinsInput = $(this).find('.pins-input');
                var eventCheckbox = $(this).find('.event-checkbox');
                var isChecked = pinsInput.val().length > 0;
                eventCheckbox.prop('disabled', !isChecked);
                pinsInput.prop('disabled', !enable || isChecked);
            });
        }

        $('.addDepartment').on('submit', function(e) {
            if ($(".addDepartment").valid()) {
                $('#loader').show();
                return true;
            }
        });
        $('.editDepartment').on('submit', function(e) {
            if ($(".editDepartment").valid()) {
                $('#loader').show();
                return true;
            }
        });
    });

    function getUserById(uid) {
        var tran_inr = $('#tran_inr' + uid).val();
        var tran_mobile = $('#tran_mobile' + uid).val();
        // var svgdata = document.querySelector('#inr' + uid + ' svg');
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        jQuery.ajax({
            type: "POST",
            url: base_url + "/users/userbyid",
            data: {
                _token: CSRF_TOKEN,
                'user_id': uid
            },
            success: function(data) {
                if (data == null) {} else {
                    var obj = jQuery.parseJSON(data);
                    $('#uid').val(obj.id);
                    $('#fname').val(obj.user_fname);
                    $('#lname').val(obj.user_lname);
                    $('#utrnumber').val(tran_inr);
                    $('#user_mobile_id').val(tran_mobile);
                    // $('.model_qr').html(svgdata);
                }
            }
        });
    }
</script>
@endsection