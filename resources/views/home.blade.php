@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <div class="card p-3">
                <h3 class="text-center">Everyone</h3>
                <button id="btn-global" class="btn btn-success d-block" data-url="{{route('global-message')}}">Send</button>
                <hr>
                <div id="global"></div>
            </div>
        </div>
        <div class="col">
            <div class="card p-3">
                <h3 class="text-center">By User ID</h3>
                <label for="user-id">Input User ID</label>
                <input type="text" class="form-control" id="user-id">
                <button id="btn-private" class="btn btn-warning d-block" data-url="{{route('private-message')}}">Send</button>
                <hr>
                <div id="private"></div>
            </div>
        </div>
    </div>
</div>
@endsection