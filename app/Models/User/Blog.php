<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public $table = "user_blogs";
    protected $fillable = [
        "image",
        "serial_number",
        "user_id",
        "status",'created_at','updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function blogContent()
    {
        return $this->hasOne('App\Models\User\BlogContent', 'blog_id', 'id');
    }
}
