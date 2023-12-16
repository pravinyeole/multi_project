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
                        <h3>{{$data['myadminSlug']}}</h3>
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
                      <button type="button" class="input-group-text copyBtn" id="idcopy" onclick="copyText('{{$data['cryptUrl']}}')"><i data-feather="copy"></i>Copy</button>
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

                            <!-- <div class="col-12">
                                <div class="form-group">
                                    <label for="">UPI ID</label>
                                    @error('user_upi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input id="user_upi" type="user_upi" class="form-control form-control-md text-left" name="user_upi" value="{{ old('upi', $user->upi) }}" required>
                                </div>
                            </div> -->
                            <div class="col-12 mb-2 d-flex gap-1">
                                <input type="text" class="form-control form-control-md text-left" name="user_upi" id="user_upi" placeholder="UPI ID" value="{{ old('upi', $user->upi) }}" required>
                                <button type="button" id="checkBtn" class="btn btn-success">Verify</button>
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