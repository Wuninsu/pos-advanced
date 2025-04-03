<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoices extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'invoices';
    protected $fillable = [
        'uuid',
        'invoice_number',
        'order_id',
        'invoice_date',
        'total_amount',
        'tax_amount',
        'discount',
        'status', //'unpaid', 'paid', 'canceled'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }
    protected $guarded = ['uuid'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
    public function order()
    {
        return $this->belongsTo(OrdersModel::class, 'order_id');
    }
}
