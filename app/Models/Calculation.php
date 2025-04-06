<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    protected $fillable = ['user_id', 'calculation_data'];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Campos calculados (opcional)
    protected $appends = ['formatted_date'];

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }
}