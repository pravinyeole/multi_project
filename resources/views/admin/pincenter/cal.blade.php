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
                        <h1><sup>₹</sup>20000.00</h1>
                    </div>
                </div>  
            </div>
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
                        <input type="date" name="DurationDate" id="DurationDate" placeholder="Start Date" class="form-control" />
                    </div>
                </div>
                <div class="col-6 col-md-4 Duration week d-none">
                    <div class="form-group">
                        <input type="date" name="DurationDate" id="DurationDate" placeholder="End Date" class="form-control" />
                    </div>
                </div>
                <div class="col-6 col-md-4 Duration month d-none">
                    <div class="form-group">
                        <select name="DurationMonth" id="DurationMonth" class="form-control">
                            <option value="">Select month</option>
                            <option value="">Jan</option>
                            <option value="">Feb</option>
                            <option value="">Mar</option>
                            <option value="">Apr</option>
                            <option value="">May</option>
                            <option value="">Jun</option>
                            <option value="">Jul</option>
                            <option value="">Aug</option>
                            <option value="">Sep</option>
                            <option value="">Oct</option>
                            <option value="">Nov</option>
                            <option value="">Dec</option>
                        </select>
                    </div>
                </div>
            </div>
            <script>
                $("#Duration").change(function(){
                    var selVal = $(this).val();
                    $(".Duration").addClass("d-none");
                    $(".Duration."+selVal).removeClass("d-none");
                })
            </script>
            <div class="card mb-3">
                <div class="page-title">
                    <h4 class="d-flex align-items-center justify-content-between" style="text-transform: uppercase;">Earnings</h4>
                </div>
                <div class="card-body">
                   <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            Plan Income
                        </span>
                        <h3><sup>₹</sup>5000.00</h3>
                    </div>
                   <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            Adnin Income
                        </span>
                        <h3><sup>₹</sup>5000.00</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            AL1 Income
                        </span>
                        <h3><sup>₹</sup>5000.00</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            AL2 Income
                        </span>
                        <h3><sup>₹</sup>5000.00</h3>
                    </div>
                </div>
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
                        <h3><sup>₹</sup>5000.00</h3>
                    </div>
                    <div class="list-item d-flex align-items-center justify-content-between">
                        <span>
                            <img src="{{asset('images/cash.png')}}" alt="">
                            Total SH Done
                        </span>
                        <h3><sup>₹</sup>5000.00</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
