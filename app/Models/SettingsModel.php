<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsModel extends Model
{
    use HasFactory;
    protected $table = "settings";
    protected $fillable = ['key', 'value'];

    public static function getSettingsData()
    {
        return self::pluck('value', 'key')->toArray();
    }

    public $timestamps = false;
}
