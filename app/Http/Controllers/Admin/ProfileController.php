<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use Illuminate\Validation\Rule;
use Auth;
use Session;
use Hash;
use Validator;
use App\Models\Admin;

class ProfileController extends Controller
{
  public function changePass()
  {
    return view('admin.profile.changepass');
  }

  public function editProfile()
  {
    $admin = Admin::findOrFail(Auth::guard('admin')->user()->id);
    return view('admin.profile.editprofile', ['admin' => $admin]);
  }

  public function updateProfile(Request $request)
  {
    $img = $request->file('profile_image');
    $allowedExts = array('jpg', 'png', 'jpeg');
    $admin = Admin::findOrFail(Auth::guard('admin')->user()->id);

    $request->validate([
      'username' => [
        'required',
        'max:255',
        Rule::unique('admins')->ignore($admin->id)
      ],
      'email' => 'required|email|max:255',
      'first_name' => 'required|max:255',
      'last_name' => 'required|max:255',
      'profile_image' => [
        function ($attribute, $value, $fail) use ($request, $img, $allowedExts) {
          if ($request->hasFile('profile_image')) {
            $ext = $img->getClientOriginalExtension();
            if (!in_array($ext, $allowedExts)) {
              return $fail(__('Only png, jpg, jpeg image is allowed'));
            }
          }
        },
      ],
    ]);
    $admin->username = $request->username;
    $admin->email = $request->email;
    $admin->first_name = $request->first_name;
    $admin->last_name = $request->last_name;

    if ($request->hasFile('profile_image')) {
      $dir = public_path('assets/admin/img/propics/');
      @unlink($dir . $admin->image);
      $admin->image = Uploader::upload_picture($dir, $img);
    }
    $admin->save();
    Session::flash('success', __('Updated Successfully'));
    return redirect()->back();
  }

  public function updatePassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'old_password' => 'required',
      'password' => 'required|confirmed'
    ]);
    // if given old password matches with the password of this authenticated user...
    if (Hash::check($request->old_password, Auth::guard('admin')->user()->password)) {
      $oldPassMatch = 'matched';
    } else {
      $oldPassMatch = 'not_matched';
    }
    if ($validator->fails() || $oldPassMatch == 'not_matched') {
      if ($oldPassMatch == 'not_matched') {
        $validator->errors()->add('oldPassMatch', true);
      }
      return redirect()->route('admin.changePass')
        ->withErrors($validator);
    }

    // updating password in database...
    $user = Admin::findOrFail(Auth::guard('admin')->user()->id);
    $user->password = bcrypt($request->password);
    $user->save();

    Session::flash('success', __('Updated Successfully'));

    return redirect()->back();
  }
}
