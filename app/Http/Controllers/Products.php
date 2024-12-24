<?php

namespace App\Http\Controllers;

use App\Models\Product as Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            'price' => 'required|numeric',
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
            $nameFile = "productname" . rand(1000,9999) . $request->user_id . "_" . $fecha . "." . $archivo->extension();
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'user_id' => $request->user_id,
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
    public function show($id){
        $product = Product::find($id);
        if(!$product){
            $data = [
                'message' => 'El producto no existe',
                'status' => 404
            ];
            return response()->json($data);
        }

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
}
