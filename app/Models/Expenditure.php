<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'amount',
        'description',
        'date',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the formatted amount for the expenditure.
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    /**
     * Get the formatted date for the expenditure.
     */
    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->format('d M, Y');
    }
}
