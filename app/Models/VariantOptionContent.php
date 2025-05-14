<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOptionContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_id',
        'variant_option_id',
        'language_id',
        'index_key',
        'user_id'
    ];
}
