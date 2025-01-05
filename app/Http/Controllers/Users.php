<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\password;

class Users extends Controller
{
    public function index(){
       $users = User::all();
       return response()->json($users, 200);
    }
    public function store(Request $request)
    {
        try {
            // Validar datos de entrada
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'birthdate' => 'required|date',
                'password' => 'required|min:6',
                'image' => 'nullable|image|mimes:jpg,jpeg,png',
            ]);
    
            // Inicializar variable para el nombre del archivo
            $nameFile = null;
    
            // Manejo de la imagen (si existe y es válida)
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $fecha = now()->format('Ymd_His');
                $archivo = $request->file('image');
                $nameFile = "Username_" . rand(1000, 9999) . "_" . $fecha . "." . $archivo->extension();
    
                // Intentar guardar la imagen
                try {
                    $archivo->storeAs('img/users', $nameFile, 'public');
                } catch (\Exception $e) {
                    // Si hay un problema con la imagen, devolvemos un código de error 500
                    return response()->json([
                        'success' => false,
                        'error_code' => 500,
                        'message' => 'Hubo un problema al guardar la imagen.'
                    ], 500);
                }
            }
    
            // Intentar crear el usuario
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'birthdate' => $validatedData['birthdate'],
                'role' => "U", // Asignando un valor por defecto para el rol
                'current_balance' => 5000, // Balance inicial
                'image' => $nameFile, // Guardar el nombre del archivo de la imagen si existe
                'password' => Hash::make($validatedData['password']),
            ]);
    
            // Si no se pudo crear el usuario
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error_code' => 500,
                    'message' => 'Error al crear el usuario.'
                ], 500);
            }
    
            // Retornar éxito con código 201 para creación exitosa de recurso
            return response()->json([
                'success' => true,
                'error_code' => 0,
                'message' => 'Usuario creado con éxito.'
            ], 201); // 201: Created
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si ocurre un error de validación
            return response()->json([
                'success' => false,
                'error_code' => 422,
                'message' => 'Errores de validación.',
                'errors' => $e->errors()
            ], 422); // 422: Unprocessable Entity (Errores de validación)
        } catch (\Exception $e) {
            // Si ocurre un error general
            return response()->json([
                'success' => false,
                'error_code' => 500,
                'message' => 'Ocurrió un error inesperado.'
            ], 500); // 500: Internal Server Error
        }
    }
    

    public function show($id)
    {
    
        $user = User::where('id', $id)->first();
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    
        // Ocultar la contraseña del resultado
        $user->makeHidden('password');
    
        return response()->json($user, 200);
    }
    
    public function sesion(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Buscar usuario por correo
        $user = User::where('email', $validatedData['email'])->first();
    
        // Verificar si el usuario existe y si la contraseña es correcta
        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
    
        // Iniciar sesión PHP si aún no se ha iniciado
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Guardar el ID del usuario en la sesión de PHP puro
        $_SESSION['user_id'] = $user->id;
    
        // Retornar una respuesta con los datos del usuario
        return response()->json([
            'message' => 'Inicio de sesión completado con éxito',
            'user' => [
                'id' => $_SESSION['user_id'],
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 200);
    }
    public function session_status()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Inicia la sesión solo si no está activa
        }
    
        if (isset($_SESSION['user_id'])) {
            return response()->json([
                'active' => true,
                'user_id' => $_SESSION['user_id']
            ], 200); // Respuesta con código 200, sesión activa
        }
    
        return response()->json([
            'active' => false,
            'message' => 'Usuario no autenticado' // Mensaje claro para el cliente
        ], 401); // Respuesta con código 401, no autorizado
    }
    

    
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        return response()->json(['logout' => true]);
    }
    
    
}
