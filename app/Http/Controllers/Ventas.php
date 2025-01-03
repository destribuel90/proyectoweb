<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Venta;
class Ventas extends Controller
{
    public function index(){
        $ventas = Venta::all();
        return response()->json($ventas,200);
    }
    public function store(){
        $product = Product::where('id', request()->id);
        $user = User::where('id', request()->session()->get('id'));
        if($user->current_balance < $product->price){
            return response()->json(['message' => 'saldo insuficiente'], 400);
        }
        Venta::created([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);
    }

}
