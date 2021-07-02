<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'login']);
Route::post('/test', [AuthController::class, 'test']);

Route::group(['middleware' => ['auth:sanctum']], function () {
	Route::post('/user', [UserController::class, 'user']);
	Route::get('/logout', [AuthController::class, 'logout']);
});