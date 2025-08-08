<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'user_items';

    public function itemContents()
    {
        return $this->hasMany(UserItemContent::class, 'item_id', 'id');
    }
    public function sliders()
    {
        return $this->hasMany(UserItemImage::class, 'item_id', 'id');
    }
    public function variations()
    {
        return $this->hasMany(UserItemVariation::class, 'item_id');
    }
    public function currency()
    {
        return $this->belongsTo(UserCurrency::class);
    }
    public function digitalCodes()
    {
        return $this->hasMany(DigitalProductCode::class, 'user_item_id', 'id');
    }

    public function hasCode(): bool
    {
        return $this->digitalCodes()->exists();
    }
}
