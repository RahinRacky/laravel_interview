@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-capitalize">{{ __('Welcome ') . Auth::user()->name }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row"><div class="col-md-3">{{ __('Your ID') }}</div><div class="col-md-3">{{ Auth::user()->email }}</div></div>
                    <div class="row"><div class="col-md-3">{{ __('Your Balance') }}</div><div class="col-md-3">{{ sprintf("%0.2f", Auth::user()->balance) }} INR</div></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
