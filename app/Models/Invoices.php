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
        'invoice_number',
        'user_id',
        'customer_id',
        'invoice_amount',
        'discount',
        'amount_payable',
        'amount_paid',
        'balance',
        'payment_method',
        'status',
        'invoice_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(CustomersModel::class, 'customer_id');
    }

    public function invoiceDetail()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }
}
