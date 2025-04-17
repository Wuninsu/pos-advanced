<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event', 'ip_address', 'user_agent', 'details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
