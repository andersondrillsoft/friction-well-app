<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculationController;
use App\Models\SubscriptionPlan;
use App\Models\AppVersion;
use App\Models\UserSubscription;

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

// Rutas públicas
Route::get('/subscription-plans', function() {
    return response()->json([
        'status' => 'success',
        'data' => SubscriptionPlan::where('is_active', true)->get()
    ]);
});

Route::get('/app-versions/latest', function() {
    return response()->json([
        'status' => 'success',
        'data' => AppVersion::latest()->first()
    ]);
});

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/user/subscription', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()->activeSubscription()
        ]);
    });

    Route::post('/calculations', [CalculationController::class, 'store']);
    
    Route::get('/calculations', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()->calculations()->latest()->get()
        ]);
    });
});