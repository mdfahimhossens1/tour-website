<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\FAQCategory;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = FAQ::with('category')->latest()->paginate(20);
        $categories = FAQCategory::where('status', 1)->orderBy('serial')->get();
        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = FAQCategory::where('status', 1)->orderBy('serial')->get();
        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|max:500',
            'answer' => 'required',
        ]);

        FAQ::create([
            'faq_category_id' => $request->faq_category_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'status' => $request->status ?? 1,
            'serial' => $request->serial ?? 0,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ Created Successfully');
    }

    public function edit($id)
    {
        $faq = FAQ::findOrFail($id);
        $categories = FAQCategory::where('status', 1)->orderBy('serial')->get();
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $faq = FAQ::findOrFail($id);

        $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|max:500',
            'answer' => 'required',
        ]);

        $faq->update([
            'faq_category_id' => $request->faq_category_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'status' => $request->status ?? 1,
            'serial' => $request->serial ?? 0,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ Updated Successfully');
    }

    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();
        return back()->with('success', 'FAQ Deleted Successfully');
    }
}