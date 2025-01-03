<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(){
        $cart = request()->session()->get('user.cart');
        $products = [];
        foreach ($cart as $product){
            $products[] = Product::find($product->id);
        }
        return response()->json($products, 200);
    }
    public function add($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
        $cart = request()->session()->get('user.cart', []);
        $productIndex = array_search($id, array_column($cart, 'id'));
        if($productIndex !== false){
            $cart[$productIndex]['quatity'] += 1;
        }else{
            $cart[] = [
                'id' => $product->id,
                'quantity' => 1
            ];
        }
        request()->session()->put('user.cart', $cart);
        return response()->json(['message' => 'Product added to cart successfully', 'cart' => $cart]);
    }
    public function remove($id){
          
    }
    public function clear(){

    }
}
