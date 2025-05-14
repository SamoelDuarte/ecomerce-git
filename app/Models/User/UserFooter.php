<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFooter extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $table = 'user_footers';

    public function language() {
        return $this->belongsTo('App\Models\Language');
    }

}
