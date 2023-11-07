@extends('layouts/common_template')

@section('title', "Update Profile")

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
        <div class="refForm mb-4">
                  <div class="row">
                    <div class="col-6">
                      <div class="p-3 ref-code">
                        <p>My Referral Code</p>
                        <h3>SS9595</h3>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="p-3 sys-code">
                        <p>System Access Code</p>
                        <h3>XYZ</h3>
                      </div>
                    </div>
                  </div>
                  <div class="btn-group d-flex">
                      <button type="button" class="input-group-text copyBtn" id="idcopy" onclick="copyText('http://127.0.0.1:8000/register/eyJpdiI6IkNZdlVoekhEVzZMZVY1dEMyNnZtZ3c9PSIsInZhbHVlIjoiY3dFNG1vMU1pQkZWL2QvSlJWaDMwZz09IiwibWFjIjoiMTJmMTdlMDNlY2E0OWM1M2Y5YmM3YzZkYjlkZDM0ZDU1YTc0MDg5ZTRlNWRkNWI4N2Q1MDYyZWUyOWJlMDA3MyIsInRhZyI6IiJ9/eyJpdiI6Imt1d1k5QSsrcHdKYlZWWGRtRXVnZVE9PSIsInZhbHVlIjoiTy94U0V0Q2FjUkFET0RhdmY5ZU5EZz09IiwibWFjIjoiMjYwYWIwYjU2Y2FlY2FkODUwYzQ2NTY4MDg2YWMyNWJhY2Q3MmM3ZGU2ZmYwMGFiOGZjN2I1ZDgyY2U5MGEwYSIsInRhZyI6IiJ9')}}')"><i data-feather="copy"></i>Copy</button>
                      <button type="button" class="input-group-text copyBtn" id="idshare"><i data-feather="share-2"></i>Share</button>
                  </div>
        </div>
            <!-- <div class="card">
                <div class="page-title">
                    <h4>Update Profile</h4>
                </div>
                <div class="card-body gray-bg"> -->
                <h4 class="font-weight-bold f24 mb-3">Update Profile</h4>
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
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">First Name</label>
                                    @error('user_fname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input id="user_fname" type="text" class="form-control @error('user_fname') is-invalid @enderror" name="user_fname" value="{{ old('user_fname', $user->user_fname) }}" required autofocus>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Last Name</label>
                                    @error('user_lname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input id="user_lname" type="text" class="form-control @error('user_lname') is-invalid @enderror" name="user_lname" value="{{ old('user_lname', $user->user_lname) }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Mobile</label>
                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="email" value="6666666666" readonly required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Email ID</label>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
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
                                    <label for="">UPI ID</label>
                                    @error('user_upi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input id="user_upi" type="user_upi" class="form-control @error('user_upi') is-invalid @enderror" name="user_upi" value="{{ old('upi', $user->upi) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start gap-1">
                            <button type="button" class="btn btn-secondary w-50 m-0 b-r-r-0 py-3" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-success w-50 m-0 b-l-r-0 py-3">Update</button>
                        </div>
                    </form>
                <!-- </div>
            </div> -->
        </div>
    </div>
</div>
@endsection
@section('page-script')
@endsection