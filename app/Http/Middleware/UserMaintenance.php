<?php

namespace App\Http\Middleware;

use App\Models\User\BasicSetting;
use Closure;
use Illuminate\Http\Request;

class UserMaintenance
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
        try {
            $user = getUser();
            $maintenanceStatus = $user->basic_setting->maintenance_status ? true : false;
            $token = $user->basic_setting->bypass_token;
            if ($maintenanceStatus == 1) {
                if (session()->has('user-bypass-token') && session()->get('user-bypass-token') == $token) {
                    return $next($request);
                }
                $userBs = BasicSetting::select('maintenance_msg', 'maintenance_img', 'favicon')->where('user_id', $user->id)->first();
                // dd($userBs);
                $data['userBs'] = $userBs;
                return response()->view('errors.user-503', $data);
            }
            return $next($request);
        } catch (\Throwable $th) {
            abort('404');
        }
    }
}
