<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    protected $fillable = ['version_number', 'min_required_version', 'download_url'];

    // Obtener versión mínima actual
    public static function currentMinVersion()
    {
        return self::latest()->value('min_required_version');
    }
}