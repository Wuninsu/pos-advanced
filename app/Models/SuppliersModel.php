<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuppliersModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "suppliers";
    protected $fillable = [
        'company_name',
        'contact_person',
        'user_id',
        'address',
        'email',
        'phone',
        'status',
        'website'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(ProductsModel::class, 'supplier_id');
    }
}
