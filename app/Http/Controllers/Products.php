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
    public function mine(Request $request)
    {
        return response()->json(
            Product::where('user_id', $request->user()->id)->latest()->get(),
            200
        );
    }

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image',
        ]);

        $image = $validated['image'];
        $nameFile = 'productname' . rand(1000, 9999) . '_' . now()->format('Y-m-dHis') . '.' . $image->extension();
        $image->storeAs('img/products', $nameFile, 'public');

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'user_id' => $request->user()->id,
            'image' => $nameFile,
        ]);

        return response()->json([
            'productos' => $product,
            'message' => 'El producto ha sido creado',
        ], 201);
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
    
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'El producto que intentas actualizar no existe',
                'status' => 404
            ], 404);
        }

        // Solo el dueño del producto (o un admin) puede editarlo
        $authUser = $request->user();
        if ($product->user_id != $authUser->id && $authUser->role !== 'A') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:255',
            'description' => 'sometimes|required|max:700',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'image' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        $data = $validator->validated();
        unset($data['image']);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $archivo = $request->file('image');
            $nameFile = "productname" . rand(1000, 9999) . "_" . date('Y-m-d') . date('His') . "." . $archivo->extension();

            $archivo->storeAs('img/products', $nameFile, 'public');

            // Borrar la imagen anterior
            if ($product->image) {
                Storage::disk('public')->delete('img/products/' . $product->image);
            }

            $data['image'] = $nameFile;
        }

        $product->update($data);

        return response()->json([
            'productos' => $product,
            'message' => 'El producto ha sido actualizado'
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'El producto no existe.'
            ], 404);
        }

        if ($product->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar este producto.'
            ], 403);
        }

        if ($product->image) {
            Storage::disk('public')->delete('img/products/' . $product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'El producto se eliminó correctamente.'
        ], 200);
    }
}
