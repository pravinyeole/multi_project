@extends('layouts/common_template')

@section('title', "Update Profile")

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/base/plugins/forms/pickers/form-flat-pickr.css') }}">
@endsection

@section('content')
    <!-- Forgot Password v1 -->
    <div class="card mb-0">
        <div class="card-body">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="user_fname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                                <div class="col-md-6">
                                    <input id="user_fname" type="text" class="form-control @error('user_fname') is-invalid @enderror" name="user_fname" value="{{ old('user_fname', $user->user_fname) }}" required autofocus>

                                    @error('user_fname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="user_lname" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                                <div class="col-md-6">
                                    <input id="user_lname" type="text" class="form-control @error('user_lname') is-invalid @enderror" name="user_lname" value="{{ old('user_lname', $user->user_lname) }}" required>

                                    @error('user_lname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">Payment Modes</label>
                                <div class="col-md-6">
                                  @foreach ($paymentModes as $mode)
                                  <div class="form-check">
                                      <input class="form-check-input" type="checkbox" name="payment_modes[]" id="{{ $mode }}" value="{{ $mode }}" @if(in_array($mode, (array)old('payment_modes', json_decode($user->payment_modes) ?? []))) checked @endif>
                                      <label class="form-check-label" for="{{ $mode }}">
                                          {{ ucfirst($mode) }}
                                          @if (isset(${$mode . 'Details'}))
                                              {{ ${$mode . 'Details'} }}
                                          @endif
                                      </label>
                                  </div>
                                  <div class="payment-details-field {{ !in_array($mode, (array)old('payment_modes', json_decode($user->payment_modes) ?? [])) ? 'd-none' : '' }}" id="payment_details_{{ $mode }}">
                                      <label for="{{ $mode }}_details" class="col-form-label">{{ ucfirst($mode) }} Details</label>
                                      <input type="text" id="{{ $mode }}_details" name="payment_details[{{ $mode }}]" class="form-control" value="{{ old('payment_details.'.$mode) }}">
                                  </div>
                              @endforeach
                              
                                </div>
                            </div>
                          
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        $(document).ready(function () {
            // Show/hide payment details fields based on selected payment modes
            var paymentModes = {!! json_encode($paymentModes) !!};
            var paymentDetailsContainer = $('.payment-details');

            paymentModes.forEach(function (mode) {
                var modeCheckbox = $('#' + mode);
                var paymentDetailsField = $('#payment_details_' + mode);

                modeCheckbox.on('change', function () {
                    if (modeCheckbox.is(':checked')) {
                        paymentDetailsField.removeClass('d-none');
                    } else {
                        paymentDetailsField.addClass('d-none');
                    }
                });
            });
        });
    </script>
@endsection
