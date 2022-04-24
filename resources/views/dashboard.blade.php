@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $role_id = Auth::user()->role_id;
    @endphp
    @if($role_id == 1)
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{route('view_product')}}">View</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('add_product')}}">Add</a>
            </li>
        </ul>
    @endif
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
</div>
@endsection
