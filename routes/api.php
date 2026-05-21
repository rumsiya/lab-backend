<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AppointmentTestController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChatController;
Route::options('/{any}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', '*');
})->where('any', '.*');

Route::get('/check', function () {
    return response()->json([
        'success' => true
    ]);
});

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::middleware('auth:api')->group(function(){
    Route::apiResource('user', UserController::class);
    Route::apiResource('role', RoleController::class);
    Route::apiResource('test', TestController::class);
    Route::apiResource('status',StatusController::class);
    Route::apiResource('unit', UnitController::class);
    Route::apiResource('appointment', AppointmentController::class);
    Route::apiResource('appointment-test', AppointmentTestController::class);
    Route::apiResource('reports', ReportController::class);
    Route::get('report-generate/{id}',[ReportController::class,'generateReport']);

})
;

Route::post('/chat', [ChatController::class, 'sendMessage']);
