<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class Ventas extends Controller
{
    public function mine(Request $request)
    {
        $purchases = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('sales.user_id', $request->user()->id)
            ->select(
                'sales.id as sale_id',
                'sales.created_at as purchased_at',
                'products.id as product_id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock',
                'products.image',
                'users.name as seller_name'
            )
            ->orderByDesc('sales.created_at')
            ->get();

        return response()->json($purchases, 200);
    }

    public function index(){
        $ventas = Venta::all();
        return response()->json($ventas,200);
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'id' => 'required|integer|exists:products,id',
            'stock' => 'required|integer|min:1',
        ]);

        try {
            return DB::transaction(function () use ($request, $validated) {
                $product = Product::whereKey($validated['id'])->lockForUpdate()->firstOrFail();
                $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
                $requestedStock = $validated['stock'];

                if ($user->current_balance < $product->price * $requestedStock) {
                    return response()->json(['message' => 'Saldo insuficiente', 'status' => false], 400);
                }

                if ($product->stock < $requestedStock) {
                    return response()->json(['message' => 'Stock insuficiente', 'status' => false], 400);
                }

                $product->stock -= $requestedStock;
                $product->save();

                $user->current_balance -= $product->price * $requestedStock;
                $user->save();

                $vendedor = User::findOrFail($product->user_id);
                $vendedor->current_balance += $product->price * $requestedStock;
                $vendedor->save();

                Venta::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ]);

                return response()->json(['message' => 'Compra realizada con éxito', 'status' => true], 200);
            });
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'No se pudo completar la compra.', 'status' => false], 500);
        }
    }
    
    
    
    

}
