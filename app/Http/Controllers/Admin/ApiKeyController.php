<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function index()
    {
        $keys = ApiKey::latest()->paginate(20);

        return view(
            'admin.api_keys.index',
            compact('keys')
        );
    }

    public function create()
    {
        return view('admin.api_keys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        ApiKey::create([
            'name' => $request->name,
            'api_key' => Str::random(64),
            'status' => 1
        ]);

        return redirect()
            ->route('admin.api.keys.index')
            ->with('success','API Key Created');
    }

    public function status($id)
    {
        $key = ApiKey::findOrFail($id);

        $key->update([
            'status' => !$key->status
        ]);

        return back();
    }

    public function destroy($id)
    {
        ApiKey::findOrFail($id)->delete();

        return back()
            ->with('success','Deleted Successfully');
    }
}