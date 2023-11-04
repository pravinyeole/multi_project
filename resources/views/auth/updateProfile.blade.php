@extends('layouts/common_template')

@section('title', "Update Profile")

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="page-title">
                    <h4>
                        Update Profile
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
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
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
                            <div class="col-12">
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
                        <div class="modal-footer border-top-0 d-flex justify-content-start">
                            <button type="button" class="btn btn-secondary w-50 m-0 b-r-r-0" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-success w-50 m-0 b-l-r-0">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
@endsection