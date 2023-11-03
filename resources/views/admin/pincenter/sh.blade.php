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
    .footer{padding:0}
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
                    <div class="table-responsive">
                <table  class="table table-hover responsive nowrap" style="width:100%" id="table_user">
                    <thead>
                        <tr>
                            <th>{{__("labels.no")}}</th>
                            <th>User ID</th>
                            <th>Timestamp</th>
                            <th>Status</th>
                            <!-- <th>{{__("labels.action")}}</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($sendHelpData))
                            @foreach($sendHelpData AS $key => $sh)
                            <tr>
                                <td>{{($key+1)}}</td>
                                <td>
                                    <div class="">
                                        <a href="#update" data-target="#update" data-toggle="modal" class="link">{{$sh->user_fname}} {{$sh->user_lname}}
                                        <p class="text-muted mb-0">{{$sh->mobile_number}}</p></a>
                                    </div>
                                </td>
                                <?php  
                                $tr = 'INRB'.date("dmYHis").($key+1);
                                $url = 'upi://pay?pa='.$sh->upi.'&pn='.$sh->user_fname.$sh->user_lname.'&cu=INR&am=1.00&tn=INRB'.$tr;
                                ?>
                                @php $from = [255, 0, 0];$to = [0, 0, 255];@endphp
                                <td id="inr{{($key+1)}}">{!! QrCode::size(100)->mergeString($tr)->style('dot')->eye('circle')->gradient($from[0], $from[1], $from[2], $to[0], $to[1], $to[2], 'diagonal')->margin(1)->generate('{{$url}}') !!}<br> {{$tr}}</td>
                                <td><a href="javascript:void(0)" class="btn btn-warning btn-sm" onClick="svgdown({{($key+1)}},'{{$tr}}')">Pay Now</a></td>
                                <!-- <td><a href="#update" data-target="#update" data-toggle="modal" class="btn btn-warning btn-sm">Pending</a></td> -->
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                </div></div>
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
      <form>
        <div class="modal-body py-0">
            <div class="alert alert-success note" role="alert">
                <h4 class="alert-heading">Note</h4>
                <p>Kindly send ₹500 to below user and share payment screenshot with the user directly.</p>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="fname" aria-describedby="fname" placeholder="Firat Name" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="lname" aria-describedby="lname" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <input type="number" class="form-control" id="mnumber" aria-describedby="mnumber" placeholder="Mobile Number" required>
                        <a href="#" class="copy-btn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Copy</a>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                    <input type="email" class="form-control" id="email1" aria-describedby="emailHelp" placeholder="Email">
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
                        <input type="radio" name="payment" id="Gpay" />
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
                        <input type="radio" name="payment" id="PhonePe" />
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
                        <input type="radio" name="payment" id="PayTM" />
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="utrnumber" aria-describedby="utrnumber" placeholder="Transaction ID / UTR No." required>
                        <a href="#" class="copy-btn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Copy</a>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="Comments" aria-describedby="Comments" placeholder="Comments" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-start">
          <button type="submit" class="btn btn-secondary w-50 m-0 b-r-r-0" data-dismiss="modal" aria-label="Close">Cancel</button>
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

    function svgdown(id,imname){
        var svg = document.querySelector('#inr'+id+' svg');
        var data = (new XMLSerializer()).serializeToString(svg);
        // We can just create a canvas element inline so you don't even need one on the DOM. Cool!
        var canvas = document.createElement('canvas');
        canvg(canvas, data, {
        renderCallback: function() {
            canvas.toBlob(function(blob) {
                download(imname+'.png', blob);
            });
        }
        });
    }
    $(document).ready(function() {

        // DataTable for organization
        if (document.getElementById("table_user")) {
            var table = $('#table_user').DataTable({
                processing: true,
                bLengthChange: false,
                responsive: true,
                order: [],
                responsive: true,

                columnDefs: [
                {
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
</script>
@endsection