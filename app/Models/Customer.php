<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory;

    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'username',
        'email',
        'email_verified',
        'email_verified_at',
        'password',
        'contact_number',
        'address',
        'city',
        'state',
        'country',
        'status',
        'verification_token',
        'remember_token',
        'verification_link',
        'user_id',
        'shipping_fname',
        'shipping_lname',
        'shipping_email',
        'shipping_number',
        'shipping_city',
        'shipping_state',
        'shipping_address',
        'shipping_country',
        'billing_fname',
        'billing_lname',
        'billing_email',
        'billing_number',
        'billing_city',
        'billing_state',
        'billing_address',
        'billing_country',
    ];

    use Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $username = Customer::query()->where('email', request()->email)->pluck('username')->first();
        $subject = 'You are receiving this email because we received a password reset request for your account.';
        $body = "Recently you tried forget password for your account.Click below to reset your account password.
             <br>
             <a href='" . url('password/reset/' . $token . '/email/' . request()->email) . "'><button type='button' class='btn btn-primary'>Reset Password</button></a>
             <br>
             Thank you.
             ";

        Common::resetPasswordMail(request()->email, $username, $subject, $body);
        session()->flash('success', "we sent you an email. Please check your inbox");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
