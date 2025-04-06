<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'purchase_token',
        'is_active'
    ];

    // Relación con el plan
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Verificar cálculos usados este mes
    public function calculationsThisMonth()
    {
        return $this->user->calculations()
            ->whereBetween('created_at', [$this->start_date, $this->end_date])
            ->count();
    }

    // Cálculos restantes
    public function remainingCalculations()
    {
        return max(0, $this->plan->calculation_limit - $this->calculationsThisMonth());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('end_date', '>', now());
    }
}