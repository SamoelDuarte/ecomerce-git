<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPage extends Model
{
    use HasFactory;

    protected $table = 'user_pages';
    protected $fillable = [
        'user_id',
        'status',
        'serial_number'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

