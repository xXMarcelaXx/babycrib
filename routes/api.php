<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CreateFeedsController;
use App\Http\Controllers\GetFeedsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/logup',[RegisterController::class,'logUp']);
Route::post('/logging',[RegisterController::class,'logging']);
Route::get('/logout',[RegisterController::class,'logOut'])->middleware(['auth:sanctum']);
Route::post('/verf',[RegisterController::class,'validationCode'])->where("id","[0-9]+");

Route::post('/create/{id}/{name}',[CreateFeedsController::class,'agregarFeed']
        )->where("id","[0-9]+")->middleware(['auth:sanctum']);
Route::post('/create/{id}',[CreateFeedsController::class,'createGroup']
        )->where("id","[0-9]+")->middleware(['auth:sanctum']);
Route::delete('/delete/{id}/{name}',[CreateFeedsController::class,'deleteGroup']
        )->where("id","[0-9]+")->middleware(['auth:sanctum']);
Route::delete('/delete/{id}/{name}/{sensor}',[CreateFeedsController::class,'deleteFeed']
        )->where("id","[0-9]+")->middleware(['auth:sanctum']);


Route::get('/key/{id}',[GetFeedsController::class,'getKeys']);
Route::get('/prueba',[GetFeedsController::class,'prueba']);
Route::post('/createCrib',[CreateFeedsController::class,'createCrib'])->middleware(['auth:sanctum']);
Route::get('/cuna/{id}',[GetFeedsController::class,'getCuna'])->middleware(['auth:sanctum']);
Route::get('/cuna/{id}/{name}',[GetFeedsController::class,'getFeeds']);