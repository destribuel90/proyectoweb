<?php

namespace App\Http\Controllers;

use App\Models\Product as Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class Products extends Controller
{
    public function index(){
        $products = Product::all();
        if($products->isEmpty()){
            $data = [
                'message' => 'No se encontró ningun producto',
                'status' => 200
            ];
            return response()->json($data, 404);
        }
        return response()->json($products,200);
    }
    public function store(Request $request){
        $nameFile = null;
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:700',
            'price' => 'require d|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image', 
        ]);
        if($validator->fails()){
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $fecha = date('Y-m-d').date('His');
            $archivo = $request->file('image');
            $nameFile = "productname" . rand(1000,9999) . "_" . $fecha . "." . $archivo->extension();
            $id = $_SESSION['user_id'];
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'user_id' => $id,
                'image' => $nameFile
            ]);
            if(!$product){
                $data = [
                    'message' => 'Error al crear el producto',
                    'status' => 500
                ];
                return response()->json($data, 500);
            }
            $archivo->storeAs('img/products', $nameFile , 'public');
        }else {
            return response()->json(['error' => 'Archivo no válido o no se subió archivo'], 400);
        }
            
        return response()->json(['productos' => $product, 'message' => 'El producto ha sido creado'], 200);
    }
    public function show($id) {
        // Buscar el producto con el ID dado
        $product = DB::table('products')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('products.id', $id)
            ->select('products.id', 'products.name', 'products.description', 'products.price', 'products.stock', 'products.user_id', 'products.image', 'users.name as name_user', 'users.image as image_user')
            ->first(); // Usar first() en lugar de get() para obtener un solo producto
    
        // Si no se encuentra el producto
        if (!$product) {
            $data = [
                'message' => 'El producto no existe',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
    
        // Si el producto existe, devolver los datos
        return response()->json($product, 200);
    }
    
    public function update($id){
        $product = Product::find($id);
        if(!$product){
            $data = [
                'message' => 'El producto que intentas actualizar no existe',
                'message' => 404
            ];
            response()->json($data, 404);
        }
        $product->name = request()->name;
        $product->description = request()->description;
    }
    public function search($data){
        $results = Product::where('name', 'like',  $data . '%' )->get();
        return response()->json($results, 200);
    }
    public function updateProductStock(Request $request) {
        try {
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity');
    
            $product = Product::findOrFail($productId);
    
            // Verifica que el stock sea suficiente o suficiente para restar
            if ($product->stock + $quantity < 0) {
                return response()->json(['message' => 'Stock insuficiente para realizar la operación', 'status' => false], 400);
            }
    
            // Actualiza el stock
            $product->stock += $quantity;
            $product->save();
    
            return response()->json(['message' => 'Stock actualizado correctamente', 'status' => true, 'stock' => $product->stock], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => false], 500);
        }
    }
    
}
