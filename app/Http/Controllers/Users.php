<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
            "password" => request()->password
        ]);
        return response()->json($user, 200);
    }
    public function show($id){
        
    }
}
