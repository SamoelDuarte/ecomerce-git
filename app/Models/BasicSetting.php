<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasicSetting extends Model
{
    public $timestamps = false;

    protected $fillable = [
        "language_id",
        'intro_subtitle',
        'intro_title',
        'intro_text',
        'intro_button_text',
        'intro_button_url',
        'intro_video_url',
        'intro_main_image',
        'team_section_title',
        'team_section_subtitle',
        'feature_section',
        'process_section',
        'templates_section',
        'featured_users_section',
        'pricing_section',
        'partners_section',
        'intro_section',
        'testimonial_section',
        'blog_section',
        'top_footer_section',
        'copyright_section',
        'footer_text',
        'copyright_text',
        'footer_logo',
        'maintainance_mode',
        'maintainance_text',
        'maintenance_img',
        'maintenance_status',
        'secret_path',
        'additional_section_status',
        'about_features_section_status',
        'about_work_process_section_status',
        'about_counter_section_status',
        'about_testimonial_section_status',
        'about_blog_section_status',
        'about_additional_section_status',
    ];

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }
}
