<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Session\TokenController;
use App\Http\Controllers\Api\Session\AuthController;


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
/*
Route::prefix('auth')->group(function() {
    Route::post('create_token', [TokenController::class, 'create']);
    Route::post('destroy_token', [TokenController::class, 'destroy']);
});
*/

Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('session', 'session');
});


/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*Route::middleware('auth:sanctum')->name('api.')->group(function() {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

});*/

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(TokenController::class)->group(function() {
        Route::get('user', function (Request $request) {
            return $request->user();
        });
    });
});