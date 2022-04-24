<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    //show all the product to users/admin
    public function index(){
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('product', compact('products'));
    }

    //show all the product to admin
    public function view(){
        $products = Product::orderBy('created_at', 'desc')->paginate(5);
        return view('admin/product', compact('products'));
    }

    //add the product to admin
    public function add(){
        return view('admin/product');
    }

    //add after submit the product to admin
    public function insert(Request $request){
        $product = new Product();
        if($request->hasFile('image')){
            $this->validate($request, [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|dimensions:width=200,height=200',
            ]);
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'_prod.'.$ext;
            $file->move('assets/uploads/products/',$filename);
            $product->image = $filename;
        }

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->original_price  = $request->input('price');
        $product->discount = $request->input('discount');
        $product->status = 1;
        $product->save();
        return redirect()->route('view_product');
    }

    //edit the product to admin
    public function edit($id){
        $update_product = Product::findOrFail($id);
        return view('admin/product', compact('update_product'));
    }

    //edit after submit the product to admin
    public function update(Request $request, $id){
        $product = Product::findOrFail($id);
        if($request->hasFile('image')){
            $this->validate($request, [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|dimensions:width=200,height=200',
            ]);
            $path = 'assets/uploads/products/'.$product->image;
            if(File::exists($path)){
                File::delete($path);
            }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'_prod.'.$ext;
            $file->move('assets/uploads/products/',$filename);
            $product->image = $filename;
        }
        if($request->input('name')){
            $product->name = $request->input('name');
        }
        if($request->input('description')){
            $product->description = $request->input('description');
        }
        if($request->input('price')){
            $product->original_price  = $request->input('price');
        }
        if($request->input('discount')){
            $product->discount = $request->input('discount');
        }
        if($request->input('status') == "0"){
            $product->status = 0;
        }elseif($request->input('status') == "1"){
            $product->status = 1;
        }
        $product->update();
        return redirect()->route('view_product');
    }

    //delete the product to admin
    public function delete($id){
        return view('admin/product');
    }

    //delete after submit the product to admin
    public function post_delete(){
        return view('admin/product');
    }
}
