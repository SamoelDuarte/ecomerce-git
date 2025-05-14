<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallToAction extends Model
{
    use HasFactory;
    protected $table = 'user_call_to_actions';

    protected $fillable = [
        'user_id',
        'language_id',
        'title',
        'text',
        'button_text',
        'button_url',
        'side_image',
        'background_image'
    ];
}
