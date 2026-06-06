<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    public function index()
    {
        $seo = SeoSetting::latest()->paginate(20);

        return view('admin.seo.index', compact('seo'));
    }

    public function create()
    {
        return view('admin.seo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'page' => 'required|unique:seo_settings,page',
        ]);

        SeoSetting::create($request->all());

        return redirect()
            ->route('admin.seo.index')
            ->with('success','SEO settings saved');
    }

    public function edit($id)
    {
        $seo = SeoSetting::findOrFail($id);

        return view('admin.seo.edit', compact('seo'));
    }

    public function update(Request $request, $id)
    {
        $seo = SeoSetting::findOrFail($id);

        $seo->update($request->all());

        return redirect()
            ->route('admin.seo.index')
            ->with('success','SEO updated');
    }

    public function destroy($id)
    {
        SeoSetting::findOrFail($id)->delete();

        return back()->with('success','Deleted');
    }
}
