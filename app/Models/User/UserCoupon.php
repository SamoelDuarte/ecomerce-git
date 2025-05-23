<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'code',
        'type',
        'value',
        'currency_id',
        'minimum_spend',
        'start_date',
        'end_date',
    ];

    public function currency()
    {
        return $this->belongsTo(UserCurrency::class);
    }
}
