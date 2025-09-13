<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'cnpj',
        'cep',
        'token_frenet',
        'rua',
        'dias_despacho',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
