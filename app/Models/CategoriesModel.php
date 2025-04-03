<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model
{
    protected $table = "categories";
    protected $fillable = ['name', 'description', 'status'];

    public function products()
    {
        return $this->hasMany(ProductsModel::class, 'category_id');
    }
}
