<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $table = "preferences";
    protected $fillable = ['key', 'value'];

    public static function getPreference()
    {
        return self::pluck('value', 'key')->toArray();
    }

    public $timestamps = false;
}
