<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNewsletterSubscriber extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'email'];
}
