<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ObatController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function() {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// Route::apiResource('/obat', ObatController::class);
Route::controller(ObatController::class)->group(function() {
    Route::get('obat', 'index');
    Route::get('obat/{id}', 'show');
    Route::post('obat', 'store');
    Route::put('obat/{id} ', 'update');
    Route::delete('obat/{id}', 'destroy');
});

Route::fallback(function() {
    return response()->json([
        'status' => false,
        'message' => 'Page Not Found'
    ], 404);
});