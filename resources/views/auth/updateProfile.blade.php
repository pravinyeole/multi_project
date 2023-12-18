@extends('layouts/common_template')

@section('title', "Update Profile")

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">

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
                </div>
                @if(!isset($user->tel_chat_Id) && empty($user->tel_chat_Id))
                <div class="col-12 ">
                    <label for="">Telegram Chat ID</label>
                    @error('tel_chat_Id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="d-flex">
                        <div class="col-8 ">
                            <input id="tel_chat_Id" type="tel_chat_Id" class="form-control form-control-md text-left" name="tel_chat_Id" value="{{ old('tel_chat_Id', $user->tel_chat_Id) }}" required>
                        </div>
                        <div class="col-4 m-1">
                            <a href="{{config('custom.custom.telegram_bot_join')}}" target="_blank" type="button" id="getChatID" class="btn btn-success">Get Chat ID</a>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-12">
                    <div class="form-group">
                        <label for="">UPI ID</label>
                        @error('user_upi')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <input id="user_upi" type="user_upi" class="form-control form-control-md text-left" name="user_upi" value="{{ old('upi', $user->upi) }}" required>
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