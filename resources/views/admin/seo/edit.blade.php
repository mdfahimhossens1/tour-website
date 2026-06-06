@extends('layouts.admin')

@section('title','Edit SEO')

@section('page')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Edit SEO Settings</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.seo.update',$seo->id) }}"
                  method="POST">

                @csrf

                {{-- PAGE --}}
                <div class="mb-3">
                    <label>Page Name</label>
                    <input type="text"
                           name="page"
                           value="{{ $seo->page }}"
                           class="form-control"
                           required>
                </div>

                {{-- META TITLE --}}
                <div class="mb-3">
                    <label>Meta Title</label>
                    <input type="text"
                           name="meta_title"
                           value="{{ $seo->meta_title }}"
                           class="form-control">
                </div>

                {{-- META DESCRIPTION --}}
                <div class="mb-3">
                    <label>Meta Description</label>
                    <textarea name="meta_description"
                              class="form-control"
                              rows="4">{{ $seo->meta_description }}</textarea>
                </div>

                {{-- KEYWORDS --}}
                <div class="mb-3">
                    <label>Meta Keywords</label>
                    <input type="text"
                           name="meta_keywords"
                           value="{{ $seo->meta_keywords }}"
                           class="form-control">
                </div>

                {{-- CANONICAL --}}
                <div class="mb-3">
                    <label>Canonical URL</label>
                    <input type="text"
                           name="canonical_url"
                           value="{{ $seo->canonical_url }}"
                           class="form-control">
                </div>

                <button class="btn btn-success">
                    Update
                </button>

                <a href="{{ route('admin.seo.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection