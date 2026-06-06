@extends('layouts.admin')

@section('title','Create Blog')

@section('page')

<div class="container-fluid">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header">
            <h4>Create New Blog</h4>
        </div>

        <div class="card-body">

            {{-- ERROR MESSAGE --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.blogs.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- TITLE --}}
                <div class="mb-3">

                    <label class="form-label">Blog Title</label>

                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title') }}"
                           required>

                </div>

                {{-- CATEGORY (NEW ⭐) --}}
                <div class="mb-3">

                    <label class="form-label">Category</label>

                    <select name="blog_category_id" class="form-control" required>

                        <option value="">Select Category</option>

                        @foreach($categories as $cat)

                            <option value="{{ $cat->id }}"
                                {{ old('blog_category_id') == $cat->id ? 'selected' : '' }}>

                                {{ $cat->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- IMAGE --}}
                <div class="mb-3">

                    <label class="form-label">Blog Image</label>

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
                              required>{{ old('description') }}</textarea>

                </div>

                {{-- META TITLE --}}
                <div class="mb-3">

                    <label class="form-label">Meta Title</label>

                    <input type="text"
                           name="meta_title"
                           class="form-control"
                           value="{{ old('meta_title') }}">

                </div>

                {{-- META DESCRIPTION --}}
                <div class="mb-3">

                    <label class="form-label">Meta Description</label>

                    <textarea name="meta_description"
                              rows="3"
                              class="form-control">{{ old('meta_description') }}</textarea>

                </div>

                {{-- STATUS --}}
                <div class="mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-control">

                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>

                {{-- BUTTONS --}}
                <button type="submit"
                        class="btn btn-success">

                    Save Blog

                </button>

                <a href="{{ route('admin.blogs.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

@endsection