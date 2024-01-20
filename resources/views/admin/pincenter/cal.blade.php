@extends('layouts/common_template')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-primary text-white mb-4 earning">
                <div class="card-body d-flex justify-content-center">
                    <img src="{{asset('images/indian-rupee.png')}}" alt="" class="img-fluid" />
                    <div>
                        <p>My Total Earning</p>
                        <h1><sup>₹</sup>{{ $dashboard_total }}</h1>
                    </div>
                </div>
            </div>
            <form action="{{url('help/my_income')}}" method="post" id="income_qry">
                @csrf
                <div class="form row form-row">
                    <div class="col-6 col-md-4">
                        <div class="form-group">
                            <select name="Duration" id="Duration" class="form-control">
                                <option value="">Select Duration</option>
                                <option @if(isset($queryArray['Duration']) && $queryArray['Duration'] == 'today') selected @endif value="today">Today’s Earning</option>
                                <option @if(isset($queryArray['Duration']) && $queryArray['Duration'] == 'week') selected @endif value="week">Weekly Earning</option>
                                <option @if(isset($queryArray['Duration']) && $queryArray['Duration'] == 'month') selected @endif value="month">Monthly Earning</option>
                                <option @if(isset($queryArray['Duration']) && $queryArray['Duration'] == 'lifetime') selected @endif value="lifetime">Lifetime Earning</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 Duration today week @if(isset($queryArray['Duration']) && ($queryArray['Duration'] == 'week' || $queryArray['Duration'] == 'today')) @else d-none @endif">
                        <div class="form-group">
                            <input type="date" name="FromDate" id="FromDate" placeholder="Start Date" class="form-control" value="@if(isset($queryArray['FromDate']) && $queryArray['FromDate'] != null){{$queryArray['FromDate']}}@endif"/>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 Duration week @if(isset($queryArray['Duration']) && $queryArray['Duration'] != 'week') d-none @endif">
                        <div class="form-group">
                            <input type="date" name="ToDate" id="ToDate" placeholder="End Date" class="form-control" value="@if(isset($queryArray['ToDate']) && $queryArray['ToDate'] != null){{$queryArray['ToDate']}}@endif"/>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 Duration month @if(isset($queryArray['Duration']) && $queryArray['Duration'] != 'month') d-none @endif">
                        <div class="form-group">
                            <select name="DurationMonth" id="DurationMonth" class="form-control">
                                <option selected disabled>Select month</option>
                                <option value="Jan" @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Jan') selected @endif>Jan</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Feb') selected @endif value="Feb">Feb</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Mar') selected @endif value="Mar">Mar</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Apr') selected @endif value="Apr">Apr</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'May') selected @endif value="May">May</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Jun') selected @endif value="Jun">Jun</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Jul') selected @endif value="Jul">Jul</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Aug') selected @endif value="Aug">Aug</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Sep') selected @endif value="Sep">Sep</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Oct') selected @endif value="Oct">Oct</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Nov') selected @endif value="Nov">Nov</option>
                                <option @if(isset($queryArray['DurationMonth']) && $queryArray['DurationMonth'] == 'Dec') selected @endif value="Dec">Dec</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <script>
                $("#Duration").change(function() {
                    var selVal = $(this).val();
                    $(".Duration").addClass("d-none");
                    $(".Duration." + selVal).removeClass("d-none");
                })
            </script>
            <div class="card mb-3">
                <div class="page-title">
                    <h4 class="d-flex align-items-center justify-content-between" style="text-transform: uppercase;">Earnings</h4>
                </div>
                @if(count($allTotal))
                <div class="card-body">
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            Plan Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['plan_income_amt'] }}</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            Admin Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['admin_income'] }}</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            Leader Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['leader_income'] }}</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            AL1 Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['level_1'] }}</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            AL2 Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['level_2'] }}</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            AL3 Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['level_3'] }}</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            AL4 Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['level_4'] }}</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            AL5 Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['level_5'] }}</h3>
                    </div>
                </div>
                @endif
            </div>
            <div class="card">
                <div class="page-title">
                    <h4 class="d-flex align-items-center justify-content-between" style="text-transform: uppercase;">Investments</h4>
                </div>
                <div class="card-body investment">
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            rPINs Used
                        </span>
                        <h3><sup>₹</sup>@if(isset($allTotalTwo['pin_used'])) {{$allTotalTwo['pin_used']}} @else {{'0.00'}} @endif</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            Total SH Done
                        </span>
                        <h3><sup>₹</sup>@if(isset($allTotalTwo['total_SH'])) {{$allTotalTwo['total_SH']}} @else {{'0.00'}} @endif</h3>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="page-title">
                    <h4 class="d-flex align-items-center justify-content-between" style="text-transform: uppercase;">My Withdrawals <span style="text-align:right;">{{$add_pin}}</span></h4>
                </div>
                <span style="color:green;text-align:center;"><b>rPINs calculated at INR 100 per rPIN </b></span>
                <div class="card-body ">
                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                    <form method="post" action="{{url('help/add_pin')}}" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                        <div class="container">
                            <div class="row">
                                <div  class="col-md-6 col-sm-12">
                                    <input type="number" class="form-control" name="rpin_add" min="1" max="{{$add_pin}}" Placeholder="Enter rPin Qty." onkeypress="return validateNumber(event)" required/>
                                    <input type="hidden" class="form-control" name="old_money" value="{{$dashboard_total}}"/>
                                </div>
                                <div  class="col-md-6 col-sm-12">
                                    <button type="submit" class="btn btn-primary">Withdraw</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>

            <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="page-title d-flex justify-content-between align-items-center">
                    <h4>
                    rPIN Withdrawal History
                    </h4>
                    <a href="transactionhistory" class="btn btn-sm btn-success">View All</a>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <div id="table_user_wrapper" class="dataTables_wrapper no-footer">
                        <table class="table table-striped dataTable no-footer mt-0 py-0" id="table_Requests" aria-describedby="table_user_info">
                            <thead>
                                <tr>
                                    <th>rPIN Qty</th>
                                    <th>Date Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=0 @endphp
                                @foreach($trans as $k=>$v)
                                <tr>
                                    @if($i > 1)
                                      @php break; @endphp
                                    @else
                                    <td>{{$v->withdraw_rpin}}</td>
                                    <td>{{date('d F Y',strtotime($v->created_at))}}</td>
                                    @endif
                                    @php $i++; @endphp
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script>
    var select = document.getElementById('Duration');
    select.addEventListener('change', function() {
        if ($('#Duration').val() == 'lifetime') {
            $('#income_qry').submit();
        }
    }, false);
    var selectT = document.getElementById('DurationMonth');
    selectT.addEventListener('change', function() {
        $('#income_qry').submit();
    }, false);
    var selectF = document.getElementById('ToDate');
    selectF.addEventListener('change', function() {
        $('#income_qry').submit();
    }, false);
    var selectG = document.getElementById('FromDate');
    selectG.addEventListener('change', function() {
        if ($('#Duration').val() == 'today') {
            $('#income_qry').submit();
        }
    }, false);

    
    function validateNumber(e) {
        const pattern = /^[0-9]$/;

        return pattern.test(e.key )
    }
</script>
@endsection