<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $subscription = $user->activeSubscription();

        if (!$subscription || $subscription->remainingCalculations() <= 0) {
            return response()->json(['error' => 'Límite de cálculos excedido'], 403);
        }

        $calculation = $user->calculations()->create($request->all());
        return response()->json($calculation);
    }
}