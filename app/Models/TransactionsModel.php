<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsModel extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $fillable = [
        'amount_paid',
        'user_id',
        'order_id',
        'balance',
        'payment_method', // cash, online
        'transact_amount',
        'transaction_number',
        'transact_date'
    ];


    public function order()
    {
        return $this->belongsTo(OrdersModel::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
