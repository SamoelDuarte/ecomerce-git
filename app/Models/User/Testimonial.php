<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'user_about_testimonials';

    protected $fillable = [
        'user_id',
        'language_id',
        'image',
        'name',
        'designation',
        'color',
        'rating',
        'comment',
    ];
}
