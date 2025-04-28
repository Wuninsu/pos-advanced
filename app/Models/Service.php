<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function requests()
    {
        return $this->hasMany(ServiceRequest::class);
    }
}
