<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "orders";
    protected $fillable = [
        'user_id',
        'customer_id',
        'order_number',
        'order_amount',
        'discount',
        'amount_payable',
        'amount_paid',
        'balance',
        'payment_method',
        'status',
        'due_date'
    ];

    protected $dates = ['due_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(CustomersModel::class, 'customer_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetailsModel::class, 'order_id');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionsModel::class, 'order_id');
    }
    public function invoice()
    {
        return $this->hasOne(Invoices::class, 'order_id');
    }
}
