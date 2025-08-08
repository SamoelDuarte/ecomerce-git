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
        'status',
        'verification_token',
        'verification_link',
        'remember_token',
        'user_id',

        // Shipping
        'shipping_fname',
        'shipping_lname',
        'shipping_email',
        'shipping_number',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_street',
        'shipping_number_address',
        'shipping_neighborhood',
        'shipping_reference',
        'shipping_country',

        // Billing
        'billing_fname',
        'billing_lname',
        'billing_email',
        'billing_number',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_street',
        'billing_number_home',
        'billing_neighborhood',
        'billing_reference',
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
