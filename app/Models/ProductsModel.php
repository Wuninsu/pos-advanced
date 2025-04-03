<?php

namespace App\Models;

use App\Livewire\Admin\Suppliers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductsModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "products";
    protected $fillable = [
        'name',
        'user_id',
        'category_id',
        'price',
        'stock',
        'description',
        'sku',
        'barcode',
        'status',
        'supplier_id',
        'img',
        'uuid'
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CategoriesModel::class, 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(SuppliersModel::class, 'supplier_id');
    }


    public function orderDetails()
    {
        return $this->hasMany(OrderDetailsModel::class, 'product_id');
    }
}
