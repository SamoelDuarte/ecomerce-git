<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'language_id', 'status', 'slug', 'user_id', 'is_feature', 'serial_number'];
    protected $table = 'user_tabs';

    public function items()
    {
        return $this->hasMany(UserItemContent::class, 'category_id', 'id');
    }
    public function subcategories()
    {
        return $this->hasMany(UserItemSubCategory::class, 'category_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
