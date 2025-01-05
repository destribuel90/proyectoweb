<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
class Ventas extends Controller
{
    public function index(){
        $ventas = Venta::all();
        return response()->json($ventas,200);
    }
    public function store() {
        try {
            DB::beginTransaction();
    
            $product = Product::findOrFail(request()->id);
            $user = User::findOrFail($_SESSION['user_id']);
            $vendedor = User::findOrFail($product->user_id);
    
            // Verificar que el stock a comprar sea mayor que 0
            $requested_stock = request()->stock;
            if ($requested_stock <= 0) {
                return response()->json(['message' => 'El stock solicitado debe ser mayor a 0', 'status' => false], 400);
            }
    
            if ($user->current_balance < $product->price * $requested_stock) {
                return response()->json(['message' => 'Saldo insuficiente', 'status' => false], 400);
            }
    
            if ($product->stock < $requested_stock) {
                return response()->json(['message' => 'Stock insuficiente', 'status' => false], 400);
            }
    
            // Actualización del stock del producto
            $product->stock -= $requested_stock;
            $product->save();
    
            // Actualización del saldo del usuario
            $user->current_balance -= $product->price * $requested_stock;
            $user->save();
    
            // Actualización del saldo del vendedor
            $vendedor->current_balance += $product->price * $requested_stock;
            $vendedor->save();
    
            // Registro de la venta
            Venta::create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
    
            DB::commit();
    
            return response()->json(['message' => 'Compra realizada con éxito', 'status' => true], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'status' => false], 500);
        }
    }
    
    
    
    

}
