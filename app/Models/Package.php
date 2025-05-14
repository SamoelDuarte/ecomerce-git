<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $table = "packages";

    protected $fillable = [
        'title',
        'slug',
        'price',
        'term',
        'featured',
        'recommended',
        'icon',
        'is_trial',
        'trial_days',
        'post_limit',
        'product_limit',
        'categories_limit',
        'subcategories_limit',
        'order_limit',
        'language_limit',
        'number_of_custom_page',
        'status',
        'features',
        'meta_keywords',
        'meta_description',
    ];

    public function memberships()
    {
        return $this->hasMany('App\Models\Membership');
    }
}
