<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItemSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'language_id',
        'category_id',
        'name',
        'slug',
        'status',
        'unique_id'
    ];

    protected $table = 'user_item_sub_categories';

    public function category()
    {
        return $this->belongsTo(UserItemCategory::class);
    }

    public function items()
    {
        return $this->hasMany(UserItemContent::class, 'subcategory_id', 'id');
    }
    public function variations()
    {
        return $this->hasMany(UserSubCategoryVariation::class, 'subcategory_id');
    }
}
