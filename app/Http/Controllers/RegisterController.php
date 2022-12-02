<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    public function hola()
    {
        return response()->json("Hola", 200, );
    }
    public function logUp(Request $request)
    {
        $input = $request->input("persona");
        if($input == null) return response()->json(["Error" => "Es necesario un objeto persona con {name,email,password}"], 400);
        $validated = Validator::make($input, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string'
        ]);
        $input["password"] = Hash::make($input["password"]);
        if($validated->fails()) return response()->json(["Error" => $validated->errors()], 400);
        if(User::create($input)) 
            return response()->json([
                "Msj" => "Registrado",
                "Data"=>[
                    "name"=>$input["password"],
                    "email"=>$input["email"]
                    ]
                ], 
            200);
        return response()->json(["Error" => "Algo fallo al momento de incertar","Msj"=>"Usuario no Registrado"], 400);
    }
    public function logging(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if($validated->fails()) return response()->json(["Error" => $validated->errors()], 400);
        $user = User::where("email", "=", $request->email)->first();
        if($user->id){
            if(Hash::check($request->password, $user->password))
            return response()->json([
                "Msj" => "Sesion iniciada correctamente",
                "Data"=>[
                    "name"=>$user->name,
                    "email"=>$user->email,
                    "token"=>$user->createToken("auth_token")->plainTextToken
                    ]
                ], 
            200);
            return response()->json(["Error" => "La contraseña no es correcta"], 400);
        }
        return response()->json(["Error" => "Usuario no encontrado"], 400);
    }
    public function logOut()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "msg" => "Sesion cerrada correctamente"
        ],200);
    }
}
