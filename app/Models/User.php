<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['email', 'password'];
    protected $hidden = ['password'];

    // Relación con suscripciones
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    // Relación con cálculos
    public function calculations()
    {
        return $this->hasMany(Calculation::class);
    }

    // Obtener suscripción activa
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('is_active', true)
            ->where('end_date', '>', now())
            ->first();
    }
}