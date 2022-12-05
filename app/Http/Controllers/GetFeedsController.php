<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuna;
use Illuminate\Support\Facades\Http;

class GetFeedsController extends Controller
{    
    protected $key="aio_DJBc469iQz1gwZv5VZHBGToj4QNM",$user="Angel_130/",$url = "https://io.adafruit.com/api/v2/";
    public function getCuna(Request $request)
    {
        $datos=Cuna::where("usuario_id","=",$request->id)->get();
        return response()->json(["data"=>$datos], 200);
        $response = Http::withHeaders(['X-AIO-Key'=>$this->key])->get($this->url.$this->user."groups");
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
}
