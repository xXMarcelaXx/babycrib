<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuna;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class CreateFeedsController extends Controller
{
    //CREATE
    protected $user="Leoncio2003/",$url = "https://io.adafruit.com/api/v2/";
    public function createGroup(Request $request)
    {
        if(User::find($request->id) == null) return response()->json(["Error" => "Usuario no encontrado"], 404);
        $cuna = $request->input("cuna");
        $sensores=$request->input("sensores");
        if($cuna == null) return response()->json(["Error" => "Es necesario un objeto cuna con {name,description}"], 400);
        $validated = Validator::make($cuna, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);
        if($validated->fails()) return response()->json(["Error" => $validated->errors()], 400);
        if(Cuna::where([["usuario_id","=",$request->id],["name","=",$cuna["name"]]])->first() != null) return response()->json(["Error" => "Nombre ya utilizado"], 406);
        $response = Http::withHeaders(['X-AIO-Key'=>$request->header('aioKey')])->post($this->url.$this->user."groups", [
            'group' => $cuna
        ]);
        if($response->successful()) {
            if($sensores != null){
                if($sensores["Vibracion"] == true) $cuna["sensor1"]=$this->createFeed("Vibracion","Sensor numero 1",$response->json("key"),$request->header('aioKey'));
                if($sensores["Sonido"] == true) $cuna["sensor2"]=$this->createFeed("Sonido","Sensor numero 2",$response->json("key"),$request->header('aioKey'));
                if($sensores["Peso"] == true) $cuna["sensor3"]=$this->createFeed("Peso","Sensor numero 3",$response->json("key"),$request->header('aioKey'));
                if($sensores["Luz"] == true) $cuna["sensor4"]=$this->createFeed("Luz","Sensor numero 4",$response->json("key"),$request->header('aioKey'));
                if($sensores["Humo"] == true) $cuna["sensor5"]=$this->createFeed("Humo","Sensor numero 5",$response->json("key"),$request->header('aioKey'));
                if($sensores["Temperatura"] == true) $cuna["sensor6"]=$this->createFeed("Temperatura","Sensor numero 6",$response->json("key"),$request->header('aioKey'));
            }
            $cuna["key"]=$response->json("key");
            $cuna["usuario_id"]=$request->id;
            $cuna["arduino_id"]="ArduinoC-1";
            Cuna::Create($cuna);
            return response()->json(["msj"=>"Se creado una nueva cuna"], 201);
        }
        return response()->json(["error"=>"No se a podido añadir la cuna"], 401);
    }
    public function createFeed($nombre,$descripcion,$gkey,$aioKey)
    {
        $response = Http::withHeaders(['X-AIO-Key'=>$aioKey])->post($this->url.$this->user."groups/".$gkey."/feeds", [    
            "feed"=>[
                "description"=>$descripcion,
                "name"=>$nombre
            ]
        ]);
        if($response->successful()) return $response->json("key");
        return response()->json(["error"=>"No se encontro el dato"], 400);
    }
    public function agregarFeed(Request $request)
    {
        $feed=$request->input("feed");
        if($feed == null) return response()->json(["Error" => "Es necesario un objeto cuna con {name,description}"], 400);
        $validated = Validator::make($feed, [
            'name' => 'required|string',
            'description' => 'required|string'
        ]);
        if($validated->fails()) return response()->json(["Error" => $validated->errors()], 400);
        $cuna=Cuna::where([["usuario_id","=",$request->id],["name","=",$request->name]])->first();
        if($cuna == null) return response()->json(["error"=>"No se encontro el dato"], 400);
        $gkey=$cuna->key;
        $response = Http::withHeaders(['X-AIO-Key'=>$request->header('aioKey')])->post($this->url.$this->user."groups/".$gkey."/feeds", [    
            "feed"=>$feed
        ]);
        if($response->successful()) {
        switch($feed["name"])
        {
            case "sensor1":
                $cuna->sensor1 =  $response->json("key");
                $cuna->save();
                break;
            case "sensor2":
                $cuna->sensor2 =  $response->json("key");
                $cuna->save();
                break;
            case "sensor3":
                $cuna->sensor3 =  $response->json("key");
                $cuna->save();
                break;
            case "sensor4":
                $cuna->sensor4 = $response->json("key");
                $cuna->save();
                break;
            case "sensor5":
                $cuna->sensor5 =  $response->json("key");
                $cuna->save();
                break;
            case "sensor6":
                $cuna->sensor6 =  $response->json("key");
                $cuna->save();
                break;
            default:
                break;
        }
            return response()->json(["Se añadio un sensor",$feed["name"]], 200);
        }
        return response()->json(["error"=>"No se encontro el dato"], 400);
    }

    //DELETE
    public function deleteGroup(Request $request,$id,$name)
    {
        $cuna=Cuna::where([["usuario_id","=",$request->id],["name","=",$request->name]])->first();
        if($cuna == null) return response()->json(["error"=>"No se encontro el dato"], 400);
        $groupKey=$cuna->key;
        $response = Http::withHeaders(['X-AIO-Key'=>$request->header('aioKey')])->delete($this->url.$this->user."groups/".$groupKey, []);
        if($response->ok()) return response()->json(["msj"=>"Se elimina la cuna"], 200);
        return response()->json(["error"=>"No se encontro el dato"], 400);
    }
    public function deleteFeed(Request $request)
    {
        $cuna=Cuna::where([["usuario_id","=",$request->id],["name","=",$request->name]])->first();
        if($cuna == null) return response()->json(["error"=>"No se encontro el dato"], 400);
        switch($request->sensor)
        {
            case "sensor1":
                $fKey=$cuna->sensor1;
                $cuna->sensor1 = null;
                $cuna->save();
                break;
            case "sensor2":
                $fKey=$cuna->sensor2;
                $cuna->sensor2 = null;
                $cuna->save();
                break;
            case "sensor3":
                $fKey=$cuna->sensor3;
                $cuna->sensor3 = null;
                $cuna->save();
                break;
            case "sensor4":
                $fKey=$cuna->sensor4;
                $cuna->sensor4 = null;
                $cuna->save();
                break;
            case "sensor5":
                $fKey=$cuna->sensor5;
                $cuna->sensor5 = null;
                $cuna->save();
                break;
            case "sensor6":
                $fKey=$cuna->sensor6;
                $cuna->sensor6 = null;
                $cuna->save();
                break;
            default:
                break;
        }
        if($fKey!=null)
        {
            $response = Http::withHeaders(['X-AIO-Key'=>$request->header('aioKey')])->delete($this->url.$this->user."feeds/".$fKey, []);
            if($response->ok()) return response()->json(["msj"=>"Se elimina el sensor"], 200);
        }
        return response()->json(["error"=>"No se encontro el dato"], 400);
    }
}
