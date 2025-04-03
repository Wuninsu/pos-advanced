<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'avatar',
        'uuid'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function hasRole()
    {
        return ['admin', 'manager', 'cashier'];
    }

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


    public function orders()
    {
        return $this->hasMany(OrdersModel::class);
    }

    public function products()
    {
        return $this->hasMany(ProductsModel::class);
    }

    public function suppliers()
    {
        return $this->hasMany(SuppliersModel::class);
    }

    public function transactions()
    {
        return $this->hasMany(TransactionsModel::class);
    }

    public function customers()
    {
        return $this->hasMany(CustomersModel::class, 'created_by');
    }
}
