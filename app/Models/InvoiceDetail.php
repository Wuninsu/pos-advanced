<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $table = "invoice_details";
    protected $fillable = ['invoice_id', 'product_id', 'unit_price', 'quantity', 'discount', 'total_amount', 'description'];


    public function invoice()
    {
        return $this->belongsTo(Invoices::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }
}
