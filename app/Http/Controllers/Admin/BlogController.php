<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(20);

        return view(
            'admin.blogs.index',
            compact('blogs')
        );
    }

    public function create()
    {
        $categories = BlogCategory::where('status',1)->get();

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required',
            'image'       => 'nullable|image'
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time().'_blog.'.$request->image->extension();

            $request->image->move(
                public_path('uploads/blogs'),
                $imageName
            );
        }

        $slug = Str::slug($request->title);

        $count = Blog::where('slug',$slug)->count();

        if($count > 0){
            $slug = $slug.'-'.time();
        }

        Blog::create([

            'title' => $request->title,
            'slug' => $slug,
            'image' => $imageName,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->status ?? 1,
            'blog_category_id' => $request->blog_category_id,

        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success','Blog Created Successfully');
    }

    public function show($slug)
    {
        $blog = Blog::where(
            'slug',
            $slug
        )->firstOrFail();

        return view(
            'admin.blogs.show',
            compact('blog')
        );
    }

    public function edit($slug)
    {
        $blog = Blog::where('slug',$slug)->firstOrFail();

        $categories = BlogCategory::where('status',1)->get();

        return view('admin.blogs.edit', compact('blog','categories'));
    }

    public function update(Request $request, $slug)
    {
        $blog = Blog::where(
            'slug',
            $slug
        )->firstOrFail();

        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required',
            'image'       => 'nullable|image'
        ]);

        $imageName = $blog->image;

        if($request->hasFile('image'))
        {
            if(
                $blog->image &&
                file_exists(
                    public_path(
                        'uploads/blogs/'.$blog->image
                    )
                )
            ){
                unlink(
                    public_path(
                        'uploads/blogs/'.$blog->image
                    )
                );
            }

            $imageName = time().'_blog.'.$request->image->extension();

            $request->image->move(
                public_path('uploads/blogs'),
                $imageName
            );
        }

        $newSlug = Str::slug($request->title);

        if($newSlug != $blog->slug)
        {
            $exists = Blog::where(
                'slug',
                $newSlug
            )->where(
                'id',
                '!=',
                $blog->id
            )->exists();

            if($exists){
                $newSlug .= '-'.time();
            }
        }
        else{
            $newSlug = $blog->slug;
        }

        $blog->update([

            'title' => $request->title,
            'slug' => $newSlug,
            'image' => $imageName,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->status,
            'blog_category_id' => $request->blog_category_id

        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success','Blog Updated Successfully');
    }

    public function destroy($slug)
    {
        $blog = Blog::where(
            'slug',
            $slug
        )->firstOrFail();

        if(
            $blog->image &&
            file_exists(
                public_path(
                    'uploads/blogs/'.$blog->image
                )
            )
        ){
            unlink(
                public_path(
                    'uploads/blogs/'.$blog->image
                )
            );
        }

        $blog->delete();

        return back()
            ->with(
                'success',
                'Blog Deleted Successfully'
            );
    }
}