<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'variant_id',
    ];

    public function variant_options_content()
    {
        return $this->belongsTo(VariantOptionContent::class, 'variant_option_id', 'id');
    }
    public function variantOptionContents()
    {
        return $this->hasMany(VariantOptionContent::class, 'variant_option_id');
    }
}
