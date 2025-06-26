<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGlobal extends Model
{
    protected $table = 'users';

    public function banners()
    {
        return \App\Models\User\Banner::all(); // traz todos os banners, de todos os users
    }

    public function faqs()
    {
        return \App\Models\User\Faq::all(); // todos os FAQs
    }

    public function orders()
    {
        return \App\Models\User\UserOrder::all(); // todos os pedidos de todos os users
    }

    // você pode fazer isso para qualquer tabela vinculada
}
