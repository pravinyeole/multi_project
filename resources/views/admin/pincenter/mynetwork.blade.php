@extends('layouts/common_template')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <h2 class="page-heading">My Network</h2>
            <div class="pinBal mb-3 d-flex align-items-center justify-content-space-between">
                    <h5>Total Affiliate IDs</h5>
                  <div class="info">
                    <p>No. of IDs</p>
                    <h3>{{$myReferalUser}}</h3>
                  </div>
                </div>
            <div class="card mb-4">
                <div class="page-title">
                    <h4>
                        Pending for Approvals
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                <table class="table" id="table_user">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>{{__("labels.action")}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($data as $k=>$v)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$v->user_fname}} {{$v->user_lname}}</td>
                            <td class="mobile">{{$v->mobile_number}}</td>
                            <!-- <td><a href="{{url('/normal_user/active_user/'.$v->id)}}" class="btn btn-sm btn-outline-dark p-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></a></td> -->
                            @if($myPinBalance_a > 0)
                                <td><a href="#createpin" id="fetch" data-toggle="modal" data-target="#createpin" class="btn btn-sm btn-outline-dark p-2" data-mobile="{{$v->mobile_number}}" data-userid="{{$v->id}}" data-mpin="{{$myPinBalance_a}}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></a></td>
                            @else
                                <td><span style="color:red;">Your bPin Balance is Not Sufficient, Contact To Admin</span></td>
                            @endif    
                            @php $i++; @endphp
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                </div>
            </div>
            <div class="card">
                <div class="page-title">
                    <h4>
                        Affiliate Users
                    </h4>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                <table class="table dataTable no-footer" id="affilate_user">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Lavel</th>
                            <th>No of ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php print_r($myLveledata['level_id_1']); @endphp
                        @php $i =1; @endphp
                        @foreach($myLveledata as $key => $v)
                            
                            @if(!is_array($v) && $v > 0)
                                <tr>
                                    
                                <td>{{$i}}</td>
                                <td>{{$i}}</td>
                                
                                <td><a href="#idlist" data-toggle="modal" data-target="#idlist" class="link" data-level="{{$myLveledata['level_id_1']}}" >{{$v}}</a></td>
                                
                                </tr>
                            @endif
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

@section('page-script')
<div class="modal fade" id="idlist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Affiliate Level</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form>
        <div class="modal-body py-0">
            <div class="alert alert-success note" role="alert">
                <h4 class="alert-heading">05</h4>
                <p>Total No of ID</p>
            </div>
            <div class="table-responsive">
                <table class="table dataTable no-footer" id="affilate_user">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Date</th>
                            <th>ID Count</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>10/10/2023</td>
                            <td>03</td>
                            <td><span class="badge badge-outline-primary badge-pill">2 Active</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>10/10/2023</td>
                            <td>01</td>
                            <td><span class="badge badge-outline-primary badge-pill">Active</span></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>10/10/2023</td>
                            <td>01</td>
                            <td><span class="badge badge-outline-danger badge-pill">Inactive</span></td>
                        </tr>
                    </tbody>
                </table>
                </div>
        </div>
        <!-- <div class="modal-footer border-top-0 d-flex justify-content-start">
          <button type="submit" class="btn btn-secondary w-50 m-0 b-r-r-0 waves-effect waves-float waves-light" data-dismiss="modal" aria-label="Close">Cancel</button>
          <button type="submit" class="btn btn-success w-50 m-0 b-l-r-0 waves-effect waves-float waves-light">Submit</button>
        </div> -->
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="createpin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Send bPin </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form>
        <div class="modal-body py-0">
            <div class="table-responsive">
                <form method="POST" action="{{url('/transferpin/transsubmit')}}">
                @csrf
                    <table class="table dataTable no-footer" id="affilate_user">
                        <thead>
                            <tr>
                                <th>Enter Mobile Number</th>
                                <th>Enter Number of bPin</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr>
                                <input type="hidden" id="user_id" class="form-control user_id" value=""  readonly>
                                <input type="hidden" id="mpin_value" class="form-control mpin_value" value=""  readonly>
                                <td><input type="text" id="mobile_number_id" class="form-control mobilenumber" value=""  readonly></td>
                                <td><input type="text" id="bpin_input" class="form-control" placeholder="Enter No. bPin" required></td>
                                <td><button type="submit" class="btn btn-success">
                                        Submit
                                </button></td>
                                
                                
                            </tr>
                            <script>
                                    
                                $(Document).on('click',"#fetch",function(e){
                                    mobilenumber = $(this).data('mobile');
                                    userids = $(this).data('userid');
                                    mpin_data = $(this).data('mpin');
                                    $('.mobilenumber').val(mobilenumber);
                                    $('.user_id').val(userids);
                                    $('.mpin_value').val(mpin_data);
                                });
                                
                            </script>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <!-- <div class="modal-footer border-top-0 d-flex justify-content-start">
          <button type="submit" class="btn btn-secondary w-50 m-0 b-r-r-0 waves-effect waves-float waves-light" data-dismiss="modal" aria-label="Close">Cancel</button>
          <button type="submit" class="btn btn-success w-50 m-0 b-l-r-0 waves-effect waves-float waves-light">Submit</button>
        </div> -->
      </form>
    </div>
  </div>
</div>
<script>
    
    $(document).ready(function() {
        // DataTable for organization
        if (document.getElementById("table_user")) {
            var table = $('#table_user').DataTable({
                processing: true,
                //serverSide: true,
                bLengthChange: false,
                responsive: true,
                /* order: [1, 'ASC'],
                ajax: {
                    url: base_url + "/pin_center",
                    data: function(data) {}
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                    // {data: 'pins', name: 'pins', orderable: false, searchable: false},
                    // {data: 'event', name: 'event', orderable: false, searchable: false},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function(settings) {
                    // Enable/disable pins input and event checkboxes for all rows
                    enableDisableInputs(true);
                } */
            });

            $("form").submit(function (event) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var formData = {
                mobile: $("#mobile_number_id").val(),
                trans_number: $("#bpin_input").val(),
                trans_id: $("#user_id").val(),
                };

                if($('#mpin_value').val() >= $("#bpin_input").val())
                {
                    $.ajax({
                        type: "POST",
                        url: base_url + "/transferpin/usertranssubmit",
                        data: formData,
                        dataType: "json",
                        encode  : true
                        }).done(function (data) {
                            event.preventDefault();
                            if(data.data == success)
                            {
                                alert(data.res);
                                location.reload();
                                $('#createpin').modal('hide');
                            }
                            else
                            {
                                alert(data.res);
                                return false;
                            }
                        });

                }
                else
                {
                    alert("Your mpin trasfer is grater than your balance. Please add more mpin.");
                    return false;
                }

            });



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