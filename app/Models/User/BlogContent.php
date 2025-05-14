<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogContent extends Model
{
    use HasFactory;

    public $table = "user_blog_contents";
    protected $fillable = [
        "blog_id",
        "language_id",
        "user_id",
        "category_id",
        "title",
        "slug",
        "content",
        "meta_keywords",
        "meta_description"
    ];

    public function blog()
    {
        return $this->belongsTo('App\Models\User\Blog');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\User\BlogCategory');
    }
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
