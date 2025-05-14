<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHeroSlider extends Model
{
    use HasFactory;

    protected $table = 'user_product_hero_sliders';
    protected $fillable = [
        'user_id',
        'products'
    ];
}
