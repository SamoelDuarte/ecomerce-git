<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function variantContents()
    {
        return $this->hasMany(VariantContent::class);
    }

    public function variantOptions()
    {
        return $this->hasMany(VariantOption::class);
    }
}
