<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegisterController;
use Laravel\Sanctum\Http\Controllers\AuthenticatedSessionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['prefix' => '/v1'], function () {
    Route::post('/register', [RegisterController::class, 'registerUser']);
    Route::post('/login',[AuthController::class,'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::delete('/logout', [AuthController::class, 'logout']);
        //Eventos
        Route::post('/create/event', [EventController::class, 'create']);
        Route::put('/update/event/{id}', [EventController::class, 'update']);
        Route::delete('/delete/event/{id}', [EventController::class, 'destroy']);

    });

});
