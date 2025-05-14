<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = "user_banners";

    protected $fillable = [
        'user_id',
        'language_id',
        'banner_img',
        'banner_url',
        'title',
        'subtitle',
        'text',
        'button_text',
        'position',
        'serial_number'
    ];

    public function brandLang()
    {
        return $this->belongsTo(Language::class);
    }
}
