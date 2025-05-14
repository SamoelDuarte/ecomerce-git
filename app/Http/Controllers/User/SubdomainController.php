<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use Auth;

class SubdomainController extends Controller
{
    public function subdomain() {
        $userId = Auth::guard('web')->user()->id;
        $features = UserPermissionHelper::packagePermission($userId);
        $data['features'] = json_decode($features, true);
        return view('user.subdomain', $data);
    }
}
