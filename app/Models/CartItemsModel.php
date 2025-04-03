<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItemsModel extends Model
{
    use HasFactory;
    protected $table = "cart_items";
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(ProductsModel::class,'product_id'); // Assuming a Product model exists
    }
}
