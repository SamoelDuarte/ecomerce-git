<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPageContent extends Model
{
    use HasFactory;

    protected $table = 'user_page_contents';
    protected $guarded = [];
}
