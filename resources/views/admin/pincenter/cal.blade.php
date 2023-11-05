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
                        <h1><sup>₹</sup>{{array_sum($allTotal) }}</h1>
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
                                <option value="today">Today’s Earning</option>
                                <option value="week">Weekly Earning</option>
                                <option value="month">Monthly Earning</option>
                                <option value="lifetime">Lifetime Earning</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 Duration today week d-none">
                        <div class="form-group">
                            <input type="date" name="FromDate" id="FromDate" placeholder="Start Date" class="form-control" />
                        </div>
                    </div>
                    <div class="col-6 col-md-4 Duration week d-none">
                        <div class="form-group">
                            <input type="date" name="ToDate" id="ToDate" placeholder="End Date" class="form-control" />
                        </div>
                    </div>
                    <div class="col-6 col-md-4 Duration month d-none">
                        <div class="form-group">
                            <select name="DurationMonth" id="DurationMonth" class="form-control">
                                <option selected disabled>Select month</option>
                                <option value="Jan">Jan</option>
                                <option value="Feb">Feb</option>
                                <option value="Mar">Mar</option>
                                <option value="Apr">Apr</option>
                                <option value="May">May</option>
                                <option value="Jun">Jun</option>
                                <option value="Jul">Jul</option>
                                <option value="Aug">Aug</option>
                                <option value="Sep">Sep</option>
                                <option value="Oct">Oct</option>
                                <option value="Nov">Nov</option>
                                <option value="Dec">Dec</option>
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
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            AL6 Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['level_6'] }}</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            AL7 Income
                        </span>
                        <h3><sup>₹</sup>{{$allTotal['level_7'] }}</h3>
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
                            ƀPINs Used
                        </span>
                        <h3><sup>₹</sup>@if(isset($allTotal->bpin_used)) {{$allTotal->bpin_used}} @else {{'0.00'}} @endif</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            Total SH Done
                        </span>
                        <h3><sup>₹</sup>@if(isset($allTotal->total_sh)) {{$allTotal->total_sh}} @else {{'0.00'}} @endif</h3>
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
</script>
@endsection