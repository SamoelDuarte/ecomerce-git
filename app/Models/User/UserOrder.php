<?php

namespace App\Models\User;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function orderitems()
    {
        return $this->hasMany(UserOrderItem::class);
    }

    public function currency()
    {
        return $this->belongsTo(UserCurrency::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
