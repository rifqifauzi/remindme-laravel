<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Session\TokenController;


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
Route::prefix('auth')->group(function() {
    Route::post('create_token', [TokenController::class, 'create']);
    Route::post('destroy_token', [TokenController::class, 'destroy']);
});


/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::middleware('auth:sanctum')->name('api.')->group(function() {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

});