<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Sitemap\SitemapGenerator;
use App\Models\Language;
use App\Models\Sitemap;
use Illuminate\Support\Facades\Session;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        $data['langs'] = Language::all();
        $data['sitemaps'] = Sitemap::orderBy('id', 'DESC')->paginate(10);
        return view('admin.sitemap.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sitemap_url' => 'required'
        ]);
        $data = new Sitemap();
        $input = $request->all();
        $filename = 'sitemap' . uniqid() . '.xml';
        @mkdir(public_path('assets/front/files/'), 777);
        SitemapGenerator::create($request->sitemap_url)->writeToFile(public_path('assets/front/files/') . $filename);
        $input['filename']    = $filename;
        $input['sitemap_url'] = $request->sitemap_url;
        $data->fill($input)->save();

        Session::flash('success', __('Sitemap has been successfully generated'));
        return "success";
    }

    public function download(Request $request)
    {
        $filePath = public_path('assets/front/files/') . $request->filename;
        if (!file_exists($filePath)) {
            return response()->json(['error' => __('Something went wrong.Please recheck')], 404);
        }
        return response()->download($filePath);
    }


    public function update(Request $request)
    {
        $data  = Sitemap::find($request->id);
        $input = $request->all();
        $dir = public_path('assets/front/files/');
        @mkdir($dir, 777);
        @unlink($dir . $data->filename);

        $filename = 'sitemap' . uniqid() . '.xml';
        SitemapGenerator::create($data->sitemap_url)->writeToFile($dir . $filename);
        $input['filename']  = $filename;

        $data->update($input);
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete($id)
    {
        $sitemap = Sitemap::find($id);
        @unlink(public_path('assets/front/files/') . $sitemap->filename);
        $sitemap->delete();

        Session::flash('success', __('Deleted Successfully'));
        return back();
    }
}
