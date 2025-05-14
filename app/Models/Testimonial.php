<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'language_id',
        'image',
        'comment',
        'name',
        'designation',
        'serial_number',
    ];

    public function language() {
        return $this->belongsTo('App\Models\Language');
    }
}
