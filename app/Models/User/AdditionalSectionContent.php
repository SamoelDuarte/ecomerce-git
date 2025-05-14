<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalSectionContent extends Model
{
    use HasFactory;

    protected $table = 'user_additional_section_contents';

    protected $fillable = [
        'language_id',
        'addition_section_id',
        'section_name',
        'content'
    ];
}
