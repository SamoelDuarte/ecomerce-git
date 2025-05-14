<?php

namespace App\Models\User;

use App\Models\VariantOptionContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantOptionContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_option_id',
        'item_id',
        'language_id',
        'option_name',
    ];

    public function option_content()
    {
        return $this->belongsTo(VariantOptionContent::class, 'option_name', 'id');
    }
}
