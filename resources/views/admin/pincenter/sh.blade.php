@extends('layouts/common_template')

@section('content')
<style>
    /* .page-title {
        font-weight: 800;
        font-size: 1.3rem;
        padding: 0.8rem 0rem;
        BACKGROUND: transparent;
        border-radius: 0;
    }
    .page-title h4{color:#072664;font-size:1.6rem;font-weight: 800;}
    .page-body{
        background:#fff;padding:1rem;border-radius:15px 15px 0 0;box-shadow:0 0 6px rgba(0,0,0,0.1);border-top:1px solid #072664;
    } */
    .footer {
        padding: 0
    }
</style>
<div class="content-wrapper">
    <div class="row">
    @if(count($mycreatedids))
        <div class="col-12">
            <div class="card">
                <div class="page-title">
                    <h4>
                        My Affilate ID
                    </h4>
                </div>
                <div class="card-body gray-bg">
                    @if (Session::has('create_id_success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <strong>Success !</strong> {{ session('create_id_success') }}
                    </div>
                    @endif
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
                        <table class="table table-hover responsive nowrap" style="width:100%" id="createid_table_user">
                            <thead>
                                <tr>
                                    <th>{{__("labels.no")}}</th>
                                    <th>Created ID</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mycreatedids AS $key => $cr)
                                
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>{{$cr->mobile_id}}</td>
                                    <td style="color:{{$cr->status}}">{{ucfirst($cr->status)}}</td>
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
        <div class="col-12">
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
                                    <th>User ID</th>
                                    <th>QR</th>
                                    <th>Action</th>
                                    <!-- <th>{{__("labels.action")}}</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($sendHelpData))
                                @foreach($sendHelpData AS $key => $sh)
                                <?php
                                $tr = 'INRB' . substr($sh->user_mobile_id, 2);
                                // $tr = 'INRB' . date("d") . substr($sh->user_mobile_id, 2);
                                if (isset($sh->upi) && $sh->upi != '') {
                                    $url = 'upi://pay?pa=' . $sh->upi . '&pn=' . $sh->user_fname . $sh->user_lname . '&cu=INR&am='.config('custom.custom.upi_pay_amount').'.00&tn=' . $tr;
                                    //$url = 'upi://pay?pa=sureshkalda@ybl&pn=SureshKalda&cu=INR&am=1.00&tn=INRB'.$tr;
                                }
                                ?>
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>
                                        <div class="">
                                            <a href="#update" data-target="#update" data-toggle="modal" class="link">{{$sh->user_fname}} {{$sh->user_lname}}
                                                <p class="text-muted mb-0">{{$tr}}</p>
                                            </a>
                                        </div>
                                    </td>
                                    <!-- $qrhtml = QrCode::size(100)->mergeString('$tr')->style('dot')->eye('circle')->gradient($from[0], $from[1], $from[2], $to[0], $to[1], $to[2], 'diagonal')->margin(6)->generate($url); -->
                                    @php
                                    if (isset($sh->upi) && $sh->upi != ''){
                                    $from = [255, 0, 0];$to = [0, 0, 255];
                                    $qrhtml = QrCode::size(110)->mergeString('$tr')->gradient($from[0], $from[1], $from[2], $to[0], $to[1], $to[2], 'diagonal')->margin(8)->generate($url);
                                    $qrhtml = str_replace('</svg>','<text fill="#a3a3a3" font-size="9" font-family="FranklinGothic-Heavy, Franklin Gothic">
                                        <tspan x="5" y="110" id="LA">'.$tr.'</tspan>
                                    </text></svg>',$qrhtml);
                                    }
                                    @endphp
                                    <input type="hidden" id="tran_inr{{$sh->id}}" value="{{$tr}}">
                                    <input type="hidden" id="tran_mobile{{$sh->id}}" value="{{$sh->user_mobile_id}}">
                                    @if(isset($sh->upi) && $sh->upi != '')
                                    <td><div id="inr{{$sh->id}}">{!! $qrhtml !!}</div></td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-warning btn-sm" onClick="svgdown({{$sh->id}},'{{$tr}}')">Download QR</a>
                                        <a href="#update" data-target="#update" data-toggle="modal" class="btn btn-primary btn-sm" onClick="getUserById({{$sh->id}})" data-backdrop="static" data-keyboard="false">Pay Now</a>
                                    </td>
                                    @else
                                    <td></td>
                                    <td>
                                        <a href="javascript:void()" class="btn btn-danger btn-sm">UPI Not Updated By User</a>
                                    </td>
                                    @endif
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
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Update Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('/payment/requestsave')}}">
                @csrf
                <div class="modal-body py-0">
                    <div class="alert alert-success note" role="alert">
                        <div class="row align-items-start">
                            <!-- <div class="col model_qr">
                            </div>
                            <div class="col"> -->
                            <h4 class="alert-heading">Note</h4>
                            <p>Kindly send ₹{{config('custom.custom.upi_pay_amount')}} to below user and share payment screenshot with the user directly.</p>
                            <!-- </div>  -->
                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="uid" name="uid">
                    <input type="hidden" class="form-control" id="user_mobile_id" name="user_mobile_id">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" class="form-control" id="fname" aria-describedby="fname" placeholder="Firat Name" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" class="form-control" id="lname" aria-describedby="lname" placeholder="Last Name" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="d-block font-weight-bold mb-2">Payment Method</label>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="d-block" for="Gpay"><img src="{{asset('images/Google-Pay-logo.png')}}" alt="" class="img-fuild" style="max-height:25px;" /></label>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <label class="switch" for="Gpay">
                                <input type="radio" name="payment" id="Gpay" value="google_pay" required />
                                <div class="slider round"></div>
                            </label>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="d-block" for="PhonePe"><img src="{{asset('images/phonePe.png')}}" alt="" class="img-fuild" style="max-height:28px;" /></label>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <label class="switch" for="PhonePe">
                                <input type="radio" name="payment" id="PhonePe" value="phone_pay" checked required />
                                <div class="slider round"></div>
                            </label>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="d-block" for="PayTM"><img src="{{asset('images/paytm_logo.png')}}" alt="" class="img-fuild" style="max-height:22px;" /></label>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <label class="switch" for="PayTM">
                                <input type="radio" name="payment" id="PayTM" value="paytm" required />
                                <div class="slider round"></div>
                            </label>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="utrnumber" name="utrnumber" aria-describedby="utrnumber" placeholder="Transaction ID / UTR No." readonly>
                                <a href="#" class="copy-btn copyBtn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg> Copy</a>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="comments" name="comments" aria-describedby="Comments" placeholder="Comments" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-start">
                    <button type="button" class="btn btn-secondary w-50 m-0 b-r-r-0" data-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-success w-50 m-0 b-l-r-0">Submit</button>
                </div>
            </form>
        </div>
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
                responsive: true});
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
                if (data == null) {
                }else{
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