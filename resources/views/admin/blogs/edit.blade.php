@extends('layouts.admin')

@section('title','Edit Blog')

@section('page')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Edit Blog</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.blogs.update',$blog->slug) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- TITLE --}}
                <div class="mb-3">
                    <label class="form-label">Blog Title</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $blog->title) }}"
                           class="form-control"
                           required>
                </div>

                {{-- CURRENT IMAGE --}}
                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <br>

                    @if($blog->image)
                        <img src="{{ asset('uploads/blogs/'.$blog->image) }}"
                             width="140"
                             class="rounded border">
                    @else
                        <p class="text-muted">No Image Found</p>
                    @endif
                </div>

                {{-- NEW IMAGE --}}
                <div class="mb-3">
                    <label class="form-label">Change Image</label>
                    <input type="file"
                           name="image"
                           class="form-control">
                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              rows="8"
                              class="form-control"
                              required>{{ old('description', $blog->description) }}</textarea>
                </div>

                {{-- META TITLE --}}
                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text"
                           name="meta_title"
                           value="{{ old('meta_title', $blog->meta_title) }}"
                           class="form-control">
                </div>

                {{-- META DESCRIPTION --}}
                <div class="mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description"
                              rows="3"
                              class="form-control">{{ old('meta_description', $blog->meta_description) }}</textarea>
                </div>

                {{-- STATUS --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">

                        <option value="1" {{ $blog->status == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ $blog->status == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    Update Blog
                </button>

                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection