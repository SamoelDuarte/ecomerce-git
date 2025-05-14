<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowitWorkSection extends Model
{
    use HasFactory;
    protected $table = 'user_howit_work_sections';
    protected $fillable = [
        'user_id',
        'language_id',
        'icon',
        'title',
        'text'
    ];
}
