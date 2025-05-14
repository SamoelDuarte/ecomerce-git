<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
    use HasFactory;
    protected $table = 'user_seos';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'language_id',
        'home_meta_keywords',
        'home_meta_description',

        'shop_meta_keywords',
        'shop_meta_description',

        'faqs_meta_keywords',
        'faqs_meta_description',

        'contact_meta_keywords',
        'contact_meta_description',

        'login_meta_keywords',
        'login_meta_description',

        'signup_meta_keywords',
        'signup_meta_description',

        'forget_password_meta_keywords',
        'forget_password_meta_description',

        'blogs_meta_keywords',
        'blogs_meta_description',

        'about_page_meta_keywords',
        'about_page_meta_description',

        'custome_page_meta_keyword',
        'custome_page_meta_description'
    ];
}
