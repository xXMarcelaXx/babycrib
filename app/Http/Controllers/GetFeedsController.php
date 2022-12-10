<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuna;
use Illuminate\Support\Facades\Http;

class GetFeedsController extends Controller
{
    protected $user="Leoncio2003/",$url = "https://io.adafruit.com/api/v2/";
    public function getCuna(Request $request)
    {
        $datos=Cuna::where("usuario_id","=",$request->id)->get();
        return response()->json(["data"=>$datos], 200);
        $response = Http::withHeaders(['X-AIO-Key'=>$request->header('aioKey')])->get($this->url.$this->user."groups");
        if($response->ok()){
            $n=0;
            for($i = 0; $i < count($response->json());$i=$i+1)
            {
                if($i!=0)
                {
                    $datos[$n]=["name"=>$response->json($i)["name"],
                    "description"=>$response->json($i)["description"]];
                }
            }
        }
    }
    public function getFeeds(Request $request)
    {
        $cuna=Cuna::where([["usuario_id","=",$request->id],["name","=",$request->name]])->first();
        if($cuna==null)return response()->json("Error", 400);
        if($cuna->sensor1 != null) $datos[]=$this->getFeed($cuna->sensor1,$request->header('aioKey'));
        if($cuna->sensor2 != null) $datos[]=$this->getFeed($cuna->sensor2,$request->header('aioKey'));
        if($cuna->sensor3 != null) $datos[]=$this->getFeed($cuna->sensor3,$request->header('aioKey'));
        if($cuna->sensor4 != null) $datos[]=$this->getFeed($cuna->sensor4,$request->header('aioKey'));
        if($cuna->sensor5 != null) $datos[]=$this->getFeed($cuna->sensor5,$request->header('aioKey'));
        if($cuna->sensor6 != null) $datos[]=$this->getFeed($cuna->sensor6,$request->header('aioKey'));
        return response()->json(["data"=>$datos], 200);
    }
    public function getFeed($key_feed,$keyAio)
    {
        $def="none";
        $response = Http::withHeaders(['X-AIO-Key'=>"aio_KsXH94NweMDJIIFxPKUZo0hETUuw"])->get(
            $this->url.$this->user."feeds/".$key_feed);
        if($response->ok()){
             $def = $response->object()->last_value;
        }
        return ["value"=>$def];
    }
    public function getKeys(Request $request)
    {
        $cuna=Cuna::where("arduino_id","=",$request->id)->first();
        if($cuna == null) return response()->json(["Error" => "No se encontro la cuna"], 404);
        return response()->json([$cuna->sensor1,$cuna->sensor2,$cuna->sensor3,
        $cuna->sensor4,$cuna->sensor5,$cuna->sensor6], 200);
    }
}
