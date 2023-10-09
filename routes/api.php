<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\user\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('regester',[AuthController::class,'store']);
Route::post('login',[AuthController::class,'login']);

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('add-post',[PostsController::class,'store']);
        Route::post('get-post',[PostsController::class,'index']);




    });
