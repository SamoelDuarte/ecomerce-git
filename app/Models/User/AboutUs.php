<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'user_about_us';
    protected $fillable = [
        'user_id',
        'language_id',
        'image',
        'title',
        'subtitle',
        'button_url',
        'button_text'
    ];
}
