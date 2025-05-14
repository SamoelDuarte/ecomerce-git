<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserSection extends Model
{
    public $table = "user_sections";
    protected $guarded = [];

    public function language(){
        return $this->hasMany('App\Models\User\Language','user_id');
    }
}
