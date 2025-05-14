<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicExtende extends Model
{
    public $table = "user_basic_extendes";

    protected $guarded = [];

    public function language(){
        return $this->hasMany('App\Models\User\Language','user_id');
    }
}
