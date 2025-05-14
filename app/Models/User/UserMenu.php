<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMenu extends Model
{
    use HasFactory;
    public $table = "user_menus";

    protected $fillable = ['user_id', 'language_id' ,'menus'];

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
