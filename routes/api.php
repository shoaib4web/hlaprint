<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PrintJobController;



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
Route::post('/login', [AuthenticatedSessionController::class, 'api_store']);
Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'api_destroy']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/printjobs', [PrintJobController::class, 'api_store']);  // Create print jobs
    Route::get('/printjobs/{transactionId}', [PrintJobController::class, 'api_show']);  // Retrieve print jobs by transaction ID
});

