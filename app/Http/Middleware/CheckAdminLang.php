<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;

class CheckAdminLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('admin_lang')) {
            app()->setLocale(session()->get('admin_lang'));
        } else {
            $default = Language::where('is_default', 1)->first();
            if (!empty($default)) {
                app()->setLocale('admin_' . $default->code);
            }
        }
        return $next($request);
    }
}
