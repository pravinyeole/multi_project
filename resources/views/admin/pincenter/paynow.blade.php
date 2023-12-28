@extends('layouts/common_template')

@section('content')
<style>
    .footer {
        padding: 0
    }
</style>
<div class="content-wrapper">
    <div class="row">
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
                    <form method="post" action="{{url('/payment/requestsave')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body py-0">
                            <div class="alert alert-success note" role="alert">
                                <div class="row align-items-start">
                                    <div class="col-5 model_qr">
                                        @php
                                        $tr = $result_data->tran_inr;
                                        if (isset($result_data->upi) && $result_data->upi != '') {
                                        $url = 'upi://pay?pa=' . $result_data->upi . '&pn=' . $result_data->user_fname . $result_data->user_lname . '&cu=INR&am=' . config('custom.custom.upi_pay_amount') . '.00&tn=' . $tr;
                                        //$url = 'upi://pay?pa=sureshkalda@ybl&pn=SureshKalda&cu=INR&am=1.00&tn=INRB'.$tr;
                                        }
                                        $from = [255, 0, 0];$to = [0, 0, 255];
                                        $qrhtml = QrCode::size(210)->mergeString('$tr')->gradient($from[0], $from[1], $from[2], $to[0], $to[1], $to[2], 'diagonal')->margin(5)->generate($url);
                                        $qrhtml = str_replace('</svg>','<text fill="#a3a3a3" font-size="15" font-family="FranklinGothic-Heavy, Franklin Gothic">
                                            <tspan x="50" y="15" id="LA">'.$tr.'</tspan>
                                        </text></svg>',$qrhtml);
                                        @endphp
                                        <!--$qrhtml = QrCode::size(100)->mergeString('$tr')->style('dot')->eye('circle')->gradient($from[0], $from[1], $from[2], $to[0], $to[1], $to[2], 'diagonal')->margin(6)->generate($url);-->
                                        <div id="inr{{$result_data->id}}">{!! $qrhtml !!}</div>
                                    </div>
                                    <div class="col">
                                        <h4 class="alert-heading">Note</h4>
                                        <p>Kindly send ₹{{config('custom.custom.upi_pay_amount')}} to below user and share payment screenshot with the user directly.</p>
                                        <button type="button" class="btn btn-warning btn-sm waves-effect waves-float waves-light m-2" onclick="svgdown(3,'INRB9128122023')">Download QR</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" id="uid" name="uid" value="{{$result_data->id}}">
                            <input type="hidden" class="form-control" id="user_mobile_id" name="user_mobile_id" value="{{$result_data->tran_mobile}}">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="fname" aria-describedby="fname" placeholder="Firat Name" value="{{$result_data->user_fname.' '.$result_data->user_lname}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="mobilenum" aria-describedby="mobilenum" placeholder="Recivers Mobile" value="{{$result_data->mobile_number}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="utrnumber" name="utrnumber" aria-describedby="utrnumber" placeholder="Transaction ID / UTR No." value="{{$tr}}" readonly>
                                        <!--<a href="#" class="copy-btn copyBtn">-->
                                        <!--    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy">-->
                                        <!--        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>-->
                                        <!--        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>-->
                                        <!--    </svg> Copy</a>-->
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="comments" name="comments" aria-describedby="Comments" placeholder="Comments">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="file" class="form-control" id="ss_payment" name="ss_payment" placeholder="Browse" required>
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
</script>
@endsection