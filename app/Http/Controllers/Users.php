<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

class Users extends Controller
{
    public function index(){
       $users = User::all();
       return response()->json($users, 200);
    }
    public function store(){
        $user = User::create([
            "name" => request()->name,
            "email" => request()->email,
            "birthdate" => request()-> birthdate,
            "role" => request()->role,
            "current_balance" => request()->current_balance,
            "image" => request()->image,
            "password" => Hash::make(request()->password)
        ]);
        return response()->json($user, 200);
    }
    public function show(){

    }
    public function sesion(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $validatedData['email'];
        $password = $validatedData['password'];
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json(['message' => 'No se encontró un usuario con ese correo. Por favor, ingrese un correo registrado.'], 400);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Contraseña incorrecta'], 400);
        }

        $request->session()->put('user.id', $user->id);

        return response()->json(['message' => 'Inicio de sesión completado con éxito'], 200);
    }
}
