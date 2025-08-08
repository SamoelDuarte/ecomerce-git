<?php

namespace App\Models\User;

use App\Models\User\UserItem;
use App\Models\User\UserOrder;
use Illuminate\Database\Eloquent\Model;

class DigitalProductCode extends Model
{
    protected $table = 'digital_product_codes';

    protected $fillable = [
        'user_item_id',
        'name',
        'code',
        'price',
        'is_used',
        'used_at',
        'order_id',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    // Produto digital relacionado
    public function product()
    {
        return $this->belongsTo(UserItem::class, 'user_item_id');
    }

    // Pedido relacionado (opcional)
    public function order()
    {
        return $this->belongsTo(UserOrder::class);
    }
}
