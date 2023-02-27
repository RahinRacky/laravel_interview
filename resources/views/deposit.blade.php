@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-capitalize">{{ __('Deposit Money') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="/transaction" method="POST">
                        @csrf
                        <input type="hidden" name="transction_type" value="Deposit">
                        <div class="form-group">
                            <label for="">Amount</label>
                            <input class="form-control" type="number" name="amount" placeholder="Enter amount to deposit" required>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary form-control">Deposit</button>
                        </div>
                    </form>

                    @include('layouts.flash')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
