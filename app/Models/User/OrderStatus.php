<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_statuses';
    protected $fillable = [
        'code', 'name', 'area', 'description', 'order', 'active'
    ];
}
