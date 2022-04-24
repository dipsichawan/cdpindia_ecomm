@extends('layouts.app')

@section('content')
<div class="container justify-content-center">
    <div class="align-items-start">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{route('view_product')}}">View</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('add_product')}}">Add</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade mt-4" id="all" role="tabpanel" aria-labelledby="view-tab">
            @if(isset($products))
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">name</th>
                        <th scope="col">price</th>
                        <th scope="col">status</th>
                        <th scope="col">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cnt = 1;
                        @endphp
                        @foreach($products as $pk => $pv)
                            @php
                                $status_arr['stat'] = "Inactive";
                                $status_arr['change_text'] = "Enable";
                                $status_arr['change_value'] = 1;
                                if($pv->status == 1){
                                    $status_arr['stat'] = "Active";
                                    $status_arr['change_text'] = "Disable";
                                    $status_arr['change_value'] = 0;
                                }
                            @endphp
                            <tr>
                                <td>{{$cnt}}</td>
                                <td>{{$pv->name}}</td>
                                <td>{{$pv->original_price}}</td>
                                <td>{{$status_arr['stat']}}</td>
                                <td><a class="btn btn-primary" href="{{url('/product/edit/'.$pv->id)}}">edit</a><a class="btn btn-danger" href="{{url('/product/delete/'.$pv->id)}}">delete</a><a class="btn btn-light change_product_status" href="javascript:void(0)" data-prodstatus="{{$status_arr['change_value']}}" data-productid="{{$pv->id}}">{{$status_arr['change_text']}}</a></td>
                                <td>
                            </tr>
                            @php $cnt++; @endphp
                        @endforeach
                    </tbody>
                </table>  
                {{ $products->links() }}                  
            @else
                <h5 class="text-center">products not found.Please add the product</p>
            @endif
            </div>
            <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="add-tab">
                <form name="add_product" action="{{route('insert_product')}}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Short description</label>
                        <input type="textarea" name="description" class="form-control" id="description">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" name="price" class="form-control" id="price">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">discount</label>
                        <input type="text" name="discount" class="form-control" id="discount">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" id="image">
                        @if ($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                @if(isset($update_product))
                    <form name="edit_product" action="{{url('/product/update/'.$update_product->id)}}" enctype="multipart/form-data" method="post">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{$update_product->name}}">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Short description</label>
                            <input type="textarea" name="description" class="form-control" id="description" value="{{$update_product->description}}">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" name="price" class="form-control" id="price" value="{{$update_product->original_price}}">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">discount</label>
                            <input type="text" name="discount" class="form-control" id="discount" value="{{$update_product->discount}}">
                        </div>
                        <div class="mb-3">
                            <img src="{{url('assets/uploads/products/'.$update_product->image)}}">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" id="image">
                            @if ($errors->has('image'))
                                <span class="text-danger">{{ $errors->first('image') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
               @endif
            </div>
            <div class="tab-pane fade" id="delete" role="tabpanel" aria-labelledby="delete-tab">...</div>
        </div>
    </div>
</div>
@endsection
@section('custom_js')
<script>
    var pathname = window.location.pathname;
    var action = pathname.split('/');
    //console.log(action);
    $(".tab-pane").each(function(){
        if($(this).attr('id') == action[2]){
            $(this).addClass('active');
            $(this).removeClass('fade');
        }else{
            if($(this).hasClass('active')){
                $(this).addClass('fade');
                $(this).removeClass('active'); 
            }
        }
    });

    $(".change_product_status").click(function(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var product_id = $(this).attr("data-productid");
        var status = $(this).attr('data-prodstatus');
        console.log(product_id);
        $.ajax({
            url: '/product/update/'+product_id,
            type: 'post',
            data: {_token: CSRF_TOKEN, status: status, _method: "PUT"},
            success: function(response){
               location.reload(); 
            },
            error: function(){
                console.log(product_id);
            }
        });
    })
</script>
@endsection
