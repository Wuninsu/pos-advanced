<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class companiesModel extends Model
{
    protected $table = "companies";
    protected $fillable =['name','user_id','address','website_url','phone','fox'];
}
