<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Language;
use App\Models\User\UserMenu;
use App\Models\User\UserPageContent;
use Auth;
use Illuminate\Http\Request;

class MenuBuilderController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->firstOrFail();
        $data['lang_id'] = $lang->id;

        $data['keywords'] = json_decode($lang->keywords, true);

        // get previous menus
        $menu = UserMenu::where('language_id', $lang->id)->where('user_id', $user_id)->first();
        $data['prevMenu'] = '';
        if (!empty($menu)) {
            $data['prevMenu'] = $menu->menus;
        }
        $data['apages'] = UserPageContent::where([['language_id', $lang->id], ['user_id', $user_id]])->orderBy('id', 'DESC')->get();


        return view('user.menu_builder.index', $data);
    }

    public function update(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        UserMenu::where('language_id', $request->language_id)->where('user_id', $user_id)->delete();

        $menu = new UserMenu();
        $menu->language_id = $request->language_id;
        $menu->user_id = $user_id;
        $menu->menus = $request->str;
        $menu->save();

        return response()->json(['status' => 'success', 'message' => __('Updated Successfully')]);
    }
}
