<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Process;
use Validator;
use Session;

class ProcessController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $lang_id = $lang->id;
        $data['processes'] = Process::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;
        return view('admin.home.process.index', $data);
    }

    public function edit($id)
    {
        $data['process'] = Process::findOrFail($id);
        return view('admin.home.process.edit', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'icon' => 'required',
            'color' => 'required',
            'title' => 'required|max:50',
            'text' => 'required|max:255',
            'serial_number' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $process = new Process;
        $process->icon = $request->icon;
        $process->language_id = $request->language_id;
        $process->title = $request->title;
        $process->text = $request->text;
        $process->color = $request->color;
        $process->serial_number = $request->serial_number;
        $process->save();

        Session::flash('success', __('Created Successfully'));
        return "success";
    }

    public function update(Request $request)
    {
        $process = Process::findOrFail($request->process_id);
        $process->icon = $request->icon;
        $process->title = $request->title;
        $process->color = $request->color;
        $process->text = $request->text;
        $process->serial_number = $request->serial_number;
        $process->save();

        Session::flash('success', __('Successfully Updated'));
        return back();
    }

    public function delete(Request $request)
    {
        $process = Process::findOrFail($request->process_id);
        @unlink(public_path('assets/front/img/process/') . $process->image);
        $process->delete();
        Session::flash('success', __('Successfully Deleted'));
        return back();
    }

    public function removeImage(Request $request)
    {
        $type = $request->type;
        $featId = $request->process_id;
        $process = Process::findOrFail($featId);
        if ($type == "process") {
            @unlink(public_path("assets/front/img/process/") . $process->image);
            $process->image = NULL;
            $process->save();
        }

        Session::flash('success', __('The image has been successfully removed'));
        return "success";
    }
}
