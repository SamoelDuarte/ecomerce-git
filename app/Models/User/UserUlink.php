<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUlink extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'name', 'language_id', 'url'];

    public function language() {
        return $this->belongsTo('App\Models\Language');
    }
}
