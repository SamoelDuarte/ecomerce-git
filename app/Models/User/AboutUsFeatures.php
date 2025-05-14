<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsFeatures extends Model
{
    use HasFactory;

    protected $table = 'user_about_us_features';

    protected $fillable = [
        'user_id',
        'language_id',
        'icon',
        'title',
        'subtitle',
        'color',
        'serial_number',
        'status'
    ];
}
