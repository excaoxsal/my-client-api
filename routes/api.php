<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyClientController;

Route::prefix('clients')->group(function () {
    Route::get('/', [MyClientController::class, 'index']);
    Route::post('/', [MyClientController::class, 'store']);
    Route::get('/{id}', [MyClientController::class, 'show']);
    Route::put('/{id}', [MyClientController::class, 'update']);
    Route::delete('/{id}', [MyClientController::class, 'destroy']);
});
