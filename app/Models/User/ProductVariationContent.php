<?php

namespace App\Models\User;

use App\Models\VariantContent;
use App\Models\VariantOptionContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariationContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'product_variation_id',
        'language_id',
        'variation_name'
    ];

    public function variation()
    {
        return $this->belongsTo(VariantContent::class, 'variation_id', 'id');
    }

    public function variation_option()
    {
        return $this->belongsTo(VariantOptionContent::class, 'variation_option_id', 'id');
    }
}
