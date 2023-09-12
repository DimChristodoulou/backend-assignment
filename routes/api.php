<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ships', [ShipController::class, 'getPositions']);
Route::get('/ships/{mmsi}', [ShipController::class, 'getmmsi']);
Route::get('/ships/latStart:{latStart}/latEnd:{latEnd}', [ShipController::class, 'getByLatRange']);
Route::get('/ships/lonStart:{lonStart}/lonEnd:{lonEnd}', [ShipController::class, 'getByLonRange']);
Route::get('/ships/from:{timeStart}/to:{timeEnd}', [ShipController::class, 'getByTimeInterval']);
