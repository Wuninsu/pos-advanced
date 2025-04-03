<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetailsModel extends Model
{
    use HasFactory;
    protected $table = "order_details";
    protected $fillable = ['order_id', 'product_id', 'unit_price', 'quantity', 'discount', 'total_amount', 'description'];

    
    public function order()
    {
        return $this->belongsTo(OrdersModel::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }
}
