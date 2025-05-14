<?php

namespace App\Models;

use App\Models\User\Banner;
use App\Models\User\Faq;
use App\Models\User\HeroSlider;
use App\Models\User\SEO;
use App\Models\User\Tab;
use App\Models\User\UserCategoryVariation;
use App\Models\User\UserContact;
use App\Models\User\UserFooter;
use App\Models\User\UserHeader;
use App\Models\User\UserItem;
use App\Models\User\UserItemCategory;
use App\Models\User\UserItemContent;
use App\Models\User\UserItemSubCategory;
use App\Models\User\UserMenu;
use App\Models\User\UserOrder;
use App\Models\User\UserOrderItem;
use App\Models\User\UserSection;
use App\Models\User\UserSubCategoryVariation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Helpers\Common;
use App\Models\User\UserPage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_name',
        'email',
        'photo',
        'username',
        'password',
        'phone',
        'city',
        'state',
        'address',
        'country',
        'status',
        'featured',
        'verification_link',
        'email_verified',
        'online_status',
        'category_id'
    ];

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


    public function banners()
    {
        return $this->hasMany(Banner::class, 'language_id');
    }
    public function category_variations()
    {
        return $this->hasMany(UserCategoryVariation::class, 'user_id');
    }
    public function contacts()
    {
        return $this->hasMany(UserContact::class, 'user_id');
    }
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'user_id');
    }
    public function footers()
    {
        return $this->hasMany(UserFooter::class, 'user_id');
    }
    public function headers()
    {
        return $this->hasMany(UserHeader::class, 'user_id');
    }
    public function hero_sliders()
    {
        return $this->hasMany(HeroSlider::class, 'user_id');
    }
    public function item_categories()
    {
        return $this->hasMany(UserItemCategory::class, 'user_id');
    }
    public function item_sub_categories()
    {
        return $this->hasMany(UserItemSubCategory::class, 'user_id');
    }
    public function menus()
    {
        return $this->hasMany(UserMenu::class, 'user_id');
    }
    public function sections()
    {
        return $this->hasMany(UserSection::class, 'user_id');
    }
    public function seos()
    {
        return $this->hasMany(SEO::class, 'user_id');
    }
    public function sub_category_variations()
    {
        return $this->hasMany(UserSubCategoryVariation::class, 'user_id');
    }
    public function tabs()
    {
        return $this->hasMany(Tab::class, 'user_id');
    }
    public function items()
    {
        return $this->hasMany(UserItem::class, 'user_id');
    }
    public function itemContent()
    {
        return $this->hasMany(UserItemContent::class, 'user_id');
    }
    public function orderContents()
    {
        return $this->hasMany(UserOrderItem::class, 'user_id');
    }
    public function orders()
    {
        return $this->hasMany(UserOrder::class, 'user_id');
    }



    public function memberships()
    {
        return $this->hasMany('App\Models\Membership', 'user_id');
    }

    public function user_custom_domains()
    {
        return $this->hasMany('App\Models\User\UserCustomDomain', 'user_id');
    }

    public function permissions()
    {
        return $this->hasOne('App\Models\User\UserPermission', 'user_id');
    }

    public function basic_setting()
    {
        return $this->hasOne('App\Models\User\BasicSetting', 'user_id');
    }

    public function achievements()
    {
        return $this->hasMany('App\Models\User\Achievement', 'user_id');
    }

    public function services()
    {
        return $this->hasMany('App\Models\User\UserService', 'user_id');
    }


    public function testimonials()
    {
        return $this->hasMany('App\Models\User\UserTestimonial', 'user_id');
    }

    public function blogs()
    {
        return $this->hasMany('App\Models\User\Blog', 'user_id');
    }

    public function blog_categories()
    {
        return $this->hasMany('App\Models\User\BlogCategory', 'user_id');
    }

    public function social_media()
    {
        return $this->hasMany('App\Models\User\Social', 'user_id');
    }

    public function permission()
    {
        return $this->hasOne('App\Models\User\UserPermission', 'user_id');
    }

    public function languages()
    {
        return $this->hasMany('App\Models\User\Language', 'user_id');
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $username = User::query()->where('email', request()->email)->pluck('username')->first();
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

    public function custom_domains()
    {
        return $this->hasMany('App\Models\User\UserCustomDomain');
    }

    public function cvs()
    {
        return $this->hasMany('App\Models\User\UserCv');
    }

    public function qr_codes()
    {
        return $this->hasMany('App\Models\User\UserQrCode');
    }

    public function custome_page()
    {
        return $this->hasMany(UserPage::class);
    }
}
