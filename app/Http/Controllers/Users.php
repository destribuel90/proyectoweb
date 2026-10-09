<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Users extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'birthdate' => 'required|date',
                'password' => 'required|min:6',
                'image' => 'nullable|image|mimes:jpg,jpeg,png',
            ]);

            $nameFile = null;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $archivo = $request->file('image');
                $nameFile = "Username_" . rand(1000, 9999) . "_" . now()->format('Ymd_His') . "." . $archivo->extension();

                try {
                    $archivo->storeAs('img/users', $nameFile, 'public');
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'error_code' => 500,
                        'message' => 'Hubo un problema al guardar la imagen.'
                    ], 500);
                }
            }

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'birthdate' => $validatedData['birthdate'],
                'role' => 'U',
                'current_balance' => 5000,
                'image' => $nameFile,
                'password' => $validatedData['password'], // el cast 'hashed' del modelo la hashea
            ]);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error_code' => 500,
                    'message' => 'Error al crear el usuario.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'error_code' => 0,
                'message' => 'Usuario creado con éxito.'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error_code' => 422,
                'message' => 'Errores de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error_code' => 500,
                'message' => 'Ocurrió un error inesperado.'
            ], 500);
        }
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user, 200);
    }

    public function sesion(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Una sola sesión activa: revoca tokens anteriores
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión completado con éxito',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 200);
    }

    // Ruta protegida con auth:sanctum
    public function session_status(Request $request)
    {
        return response()->json([
            'active' => true,
            'user_id' => $request->user()->id
        ], 200);
    }

    // Ruta protegida con auth:sanctum
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['logout' => true]);
    }

    // Ruta protegida con auth:sanctum
    public function update(Request $request, $id)
    {
        $authUser = $request->user();

        // Solo puede editarse a sí mismo (o un admin; ajusta el valor del rol)
        if ($authUser->id != $id && $authUser->role !== 'A') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
                'birthdate' => 'sometimes|required|date',
                'password' => 'sometimes|required|min:6',
                'image' => 'nullable|image|mimes:jpg,jpeg,png',
            ]);

            unset($validatedData['image']);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $archivo = $request->file('image');
                $nameFile = "Username_" . rand(1000, 9999) . "_" . now()->format('Ymd_His') . "." . $archivo->extension();

                try {
                    $archivo->storeAs('img/users', $nameFile, 'public');
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'error_code' => 500,
                        'message' => 'Hubo un problema al guardar la imagen.'
                    ], 500);
                }

                if ($user->image) {
                    Storage::disk('public')->delete('img/users/' . $user->image);
                }

                $validatedData['image'] = $nameFile;
            }

            $user->update($validatedData); // password se hashea por el cast del modelo

            return response()->json([
                'success' => true,
                'error_code' => 0,
                'message' => 'Usuario actualizado con éxito.',
                'user' => $user
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error_code' => 422,
                'message' => 'Errores de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error_code' => 500,
                'message' => 'Ocurrió un error inesperado.'
            ], 500);
        }
    }
}