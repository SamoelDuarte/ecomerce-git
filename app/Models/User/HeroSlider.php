<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    use HasFactory;

    protected $table = "user_hero_sliders";

    protected $fillable = [
        'user_id',
        'language_id',
        'img',
        'title',
        'subtitle',
        'text',
        'btn_name',
        'btn_url',
        'video_url',
        'video_button_text',
        'serial_number',
        'is_static'
    ];

    public function sliderVersionLang()
    {
        return $this->belongsTo(Language::class);
    }
}
