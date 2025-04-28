<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsModel extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $fillable = [
        'user_id',
        'order_id',
        'balance',
        'payment_method',
        'transaction_amount',
        'transaction_number',
        'transaction_date'
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
