<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomersModel extends Model
{
    use HasFactory;
    protected $table = "customers";
    protected $fillable = ['name', 'created_by', 'address', 'email', 'phone', 'counter'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function orders()
    {
        return $this->hasMany(OrdersModel::class, 'customer_id');
    }
}
