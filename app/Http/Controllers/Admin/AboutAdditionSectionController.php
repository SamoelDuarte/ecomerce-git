<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdditionalSection;
use App\Models\AdditionalSectionContent;
use App\Models\BasicSetting;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Purifier;

class AboutAdditionSectionController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $information['langs'] = Language::all();

        $information['sections'] = AdditionalSection::join('additional_section_contents', 'additional_section_contents.addition_section_id', '=', 'additional_sections.id')
            ->where('language_id', $lang->id)
            ->where('page_type', 'about')
            ->select('additional_sections.*', 'additional_section_contents.section_name')
            ->get();

        return view('admin.about.additional-section.index', $information);
    }

    public function create(Request $request)
    {
        $information['language'] = Language::where('is_default', 1)->first();
        $information['languages'] = Language::all();
        $information['page_type'] = 'about';
        return view('admin.about.additional-section.create', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'possition' => 'required',
            'page_type' => 'required',
            'serial_number' => 'required',
        ];
        $languages = Language::get();
        $messages = [];
        foreach ($languages as $language) {
            $code = $language->code;
            $langaugeName = ' ' . $language->name . ' ' . __('language');

            if (
                $language->is_default == 1 ||
                $request->input($code . '_section_name') ||
                $request->input($code . '_content')
            ) {
                $rules[$code . '_section_name'] = 'required|max:255';
                $rules[$code . '_content'] = 'required';
            }
            
            //messsge array
            $messages[$code . '_section_name.required'] = __('The title field is required for') . $langaugeName;
            $messages[$code . '_section_name.max'] = __('The title field cannot contain more than 255 characters for') . $langaugeName;
            $messages[$code . '_content.required'] = __('The content field is required for') . $langaugeName;
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $languages = Language::all();
        $section = AdditionalSection::create($request->all());


        foreach ($languages as $language) {
            $code = $language->code;
            if ($language->is_default == 1 || $request->filled($code . '_section_name') || $request->filled($code . '_content')) {
                $content = new AdditionalSectionContent();
                $content->language_id = $language->id;
                $content->addition_section_id = $section->id;
                $content->section_name = $request[$code . '_section_name'];
                $content->content = Purifier::clean($request[$code . '_content'], 'youtube');
                $content->save();
            }
        }

        $bss = BasicSetting::all();

        foreach ($bss as $key => $bs) {
            $arr = json_decode($bs->about_additional_section_status, true);
            $arr["$section->id"] = "1";

            $bs->about_additional_section_status = json_encode($arr);
            $bs->save();
        }

        Session::flash('success', __('Created Successfully'));

        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id, Request $request)
    {
        $information['languages'] = Language::all();
        $information['language'] = Language::where('is_default', 1)->first();
        $information['section'] = AdditionalSection::where('page_type', 'about')->where('id', $id)->firstOrFail();
        return view('admin.about.additional-section.edit', $information);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'possition' => 'required',
            'page_type' => 'required',
            'serial_number' => 'required',
        ];
        $languages = Language::get();
        $messages = [];
        foreach ($languages as $language) {
            $code = $language->code;
            $langaugeName = ' ' . $language->name . ' ' . __('language');

            if (
                $language->is_default == 1 ||
                $request->input($code . '_section_name') ||
                $request->input($code . '_content')
            ) {
                $rules[$code . '_section_name'] = 'required|max:255';
                $rules[$code . '_content'] = 'required';
            }
            
            //messsge array
            $messages[$code . '_section_name.required'] = __('The title field is required for') . $langaugeName;
            $messages[$code . '_section_name.max'] = __('The title field cannot contain more than 255 characters for') . $langaugeName;
            $messages[$code . '_content.required'] = __('The content field is required for') . $langaugeName;
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $section = AdditionalSection::findOrFail($id);
        $section->possition = $request->possition;
        $section->page_type = $request->page_type;
        $section->serial_number = $request->serial_number;
        $section->save();

        $languages = Language::all();

        foreach ($languages as $language) {
            $content = AdditionalSectionContent::where('addition_section_id', $id)->where('language_id', $language->id)->first();
            if (empty($content)) {
                $content = new AdditionalSectionContent();
            }
            $code = $language->code;
            if ($language->is_default == 1 || $request->filled($code . '_section_name') || $request->filled($code . '_content')) {
                // Retrieve the content for the given section and language, or create a new one if it doesn't exist
                $content = AdditionalSectionContent::firstOrNew([
                    'addition_section_id' => $section->id,
                    'language_id' => $language->id
                ]);
                $content->section_name = $request[$code . '_section_name'];
                $content->content = Purifier::clean($request[$code . '_content'], 'youtube');
                $content->save();
            }
        }

        Session::flash('success', __('Updated successfully'));

        return response()->json(['status' => 'success'], 200);
    }

    public function delete($id)
    {
        $section = AdditionalSection::findOrFail($id);
        $contents = AdditionalSectionContent::where('addition_section_id', $id)->get();
        foreach ($contents as $content) {
            $content->delete();
        }
        $section->delete();
        return redirect()->back()->with('success', __('Deleted Successfully'));
    }

    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $page = AdditionalSection::query()->findOrFail($id);

            $contents = AdditionalSectionContent::where('addition_section_id', $id)->get();

            foreach ($contents as $pageContent) {
                $pageContent->delete();
            }

            $page->delete();
        }

        Session::flash('success', __('Deleted Successfully'));

        return 'success';
    }
}
