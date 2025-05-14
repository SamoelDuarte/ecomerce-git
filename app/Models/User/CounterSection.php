<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterSection extends Model
{
    use HasFactory;

    protected $table = 'user_counter_sections';

    protected $fillable = [
        'user_id',
        'language_id',
        'image',
        'title',
        'text'
    ];
}
