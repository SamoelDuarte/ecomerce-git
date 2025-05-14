<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterInformation extends Model
{
    use HasFactory;

    protected $table = 'user_counter_information';

    protected $fillable = [
        'user_id',
        'language_id',
        'icon',
        'color',
        'amount',
        'title'
    ];
}
