<?php

namespace App\Models;

use App\Models\User\UserItemCategory;
use App\Models\User\UserItemSubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'language_id',
        'user_id',
        'variant_id',
        'name'
    ];

    public function category()
    {
        return $this->belongsTo(UserItemCategory::class, 'category_id', 'id');
    }

    public function sub_category(){
        return $this->belongsTo(UserItemSubCategory::class, 'sub_category_id', 'id');
    }
    public function variant_options(){
        return $this->belongsTo(VariantOption::class, 'variant_id', 'id');
    }
}
