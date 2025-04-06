<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name', 'monthly_price', 'calculation_limit', 'is_active'];

    // Relación con suscripciones de usuarios
    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }
}