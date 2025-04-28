<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table = 'service_requests';
    protected $fillable = [
        'user_id',
        'service_id',
        'date',
        'client',
        'loading_place',
        'destination',
        'quantity',
        'unit_price',
        'unit_id',
        'amount',
        'revenue',
        'remarks',
        'other_expenses',

        // percentage-based fields
        'fuel',
        'allowance',
        'feeding',
        'maintenance',
        'owner',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
