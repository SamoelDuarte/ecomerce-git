<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Language extends Model
{
    public $table = "user_languages";

    protected $fillable = [
        'id',
        'name',
        'is_default',
        'code',
        'rtl',
        'type',
        'user_id',
        'keywords'
    ];

    public function pageName()
    {
        return $this->hasOne(UserHeading::class, 'language_id');
    }
    public function itemInfo()
    {
        return $this->hasMany(UserItemContent::class, 'language_id');
    }
    public function banners()
    {
        return $this->hasMany(Banner::class, 'language_id');
    }
    public function category_variations()
    {
        return $this->hasMany(UserCategoryVariation::class, 'language_id');
    }
    public function contacts()
    {
        return $this->hasMany(UserContact::class, 'language_id');
    }
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'language_id');
    }
    public function footers()
    {
        return $this->hasMany(UserFooter::class, 'language_id');
    }
    public function headers()
    {
        return $this->hasMany(UserHeader::class, 'language_id');
    }
    public function hero_sliders()
    {
        return $this->hasMany(HeroSlider::class, 'language_id');
    }
    public function item_categories()
    {
        return $this->hasMany(UserItemCategory::class, 'language_id');
    }
    public function item_sub_categories()
    {
        return $this->hasMany(UserItemSubCategory::class, 'language_id');
    }
    public function menus()
    {
        return $this->hasMany(UserMenu::class, 'language_id');
    }
    public function sections()
    {
        return $this->hasMany(UserSection::class, 'language_id');
    }
    public function seos()
    {
        return $this->hasMany(SEO::class, 'language_id');
    }
    public function sub_category_variations()
    {
        return $this->hasMany(UserSubCategoryVariation::class, 'language_id');
    }
    public function tabs()
    {
        return $this->hasMany(Tab::class, 'language_id');
    }

    public function testimonials()
    {
        return $this->hasMany('App\Models\User\UserTestimonial', 'lang_id')->where('user_id', Auth::id());
    }
    public function blogs()
    {
        return $this->hasMany('App\Models\User\Blog')->where('user_id', Auth::id());
    }
    public function blog_categories()
    {
        return $this->hasMany('App\Models\User\BlogCategory')->where('user_id', Auth::id());
    }
}
