<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    public $timestamps = false;
    protected $table = 'user_faqs';
}
