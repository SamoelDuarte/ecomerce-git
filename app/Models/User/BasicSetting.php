<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class BasicSetting extends Model
{
    public $table = "user_basic_settings";

    protected $fillable = [
        'user_id',
        'favicon',
        'preloader',
        'preloader_status',
        'logo',
        'cv',
        'base_color',
        'theme',
        'breadcrumb',
        'hero_section_background_image',
        'timezone'
    ];

    public function language()
    {
        return $this->hasMany('App\Models\User\Language', 'user_id');
    }
}
