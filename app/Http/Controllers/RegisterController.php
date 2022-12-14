<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use App\Mail\SendEmail;
use App\Jobs\ProcessMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function logUp(Request $request)
    {
        $input = $request->input("persona");
        if($input == null) return response()->json(["Error" => "Es necesario un objeto persona con {name,email,password}"], 400);
        $validated = Validator::make($input, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'tel' => 'required|string'
        ]);
        $input["password"] = Hash::make($input["password"]);
        if($validated->fails()) return response()->json(["Error" => $validated->errors()], 400);
        $user = User::create($input);
        if($user == true)
            ProcessMail::dispatchAfterResponse($user);
            return response()->json([
                "msj" => "Registrado",
                "data"=>[
                    "name"=>$input["name"],
                    "email"=>$user->email
                    ]
                ],
            200);
        return response()->json(["Error" => "Algo fallo al momento del registro","Msj"=>"Usuario no Registrado"], 400);
    }
    public function logging(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if($validated->fails()) return response()->json(["Error" => $validated->errors()], 400);
        $user = User::where("email", "=", $request->email)->first();
        if($user == null) return response()->json(["error" => "Usuario no encontrado"], 403);
        if($user->id){
            if($user->status == false) return response()->json(["Error" => "El usuario no esta activo"], 401);
            if(Hash::check($request->password, $user->password))
            return response()->json([
                "msj" => "Sesion iniciada correctamente",
                "data"=>[
                    "id"=>$user->id,
                    "name"=>$user->name,
                    "email"=>$user->email,
                    "token"=>$user->createToken("auth_token")->plainTextToken
                    ]
                ], 
            200);
            return response()->json(["error" => "La contrase??a no es correcta"], 403);
        }
    }
    public function logOut()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "msg" => "Sesion cerrada correctamente"
        ],200);
    }
    public function validationCode(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'code_verf' => 'required|integer|min:6',
        ]);
        if($validated->fails()) return response()->json(["Error" => $validated->errors()], 400);
        $user=User::where("code_verf","=",$request->code_verf)->first();
        if($user != null)
        {
            $user->status=true;
            $user->save();
            return response()->json(["msj" => "Usuario activo"], 200);
        }
        return response()->json(["msj" => "No se pudo activar el usuario"], 406);
    }
}
