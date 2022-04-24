@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
            @if(isset($products))
                @foreach($products as $pk => $pv)
                <div class="col-4 mt-4">
                    <div class="card" style="width: 18rem;margin:auto">
                        <img src="{{url('assets/uploads/products/'.$pv->image)}}" class="card-img-top" height="250px" alt="">
                        <div class="card-body">
                            <h5 class="card-title">{{$pv->name}}</h5>
                            <p class="card-text">{{$pv->description}}</p>
                            <p class="card-text">Rs{{$pv->original_price}}</p>
                            <a href="javascript:void(0)" class="btn btn-primary">show</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <p>products not found</p>
            @endif
    </div>
</div>
@endsection