@extends('layouts.admin')

@section('title','Add SEO')

@section('page')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Add SEO Settings</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.seo.store') }}"
                  method="POST">

                @csrf

                {{-- PAGE --}}
                <div class="mb-3">
                    <label>Page Name</label>
                    <input type="text"
                           name="page"
                           class="form-control"
                           placeholder="home / blog / contact"
                           required>
                </div>

                {{-- META TITLE --}}
                <div class="mb-3">
                    <label>Meta Title</label>
                    <input type="text"
                           name="meta_title"
                           class="form-control">
                </div>

                {{-- META DESCRIPTION --}}
                <div class="mb-3">
                    <label>Meta Description</label>
                    <textarea name="meta_description"
                              class="form-control"
                              rows="4"></textarea>
                </div>

                {{-- KEYWORDS --}}
                <div class="mb-3">
                    <label>Meta Keywords</label>
                    <input type="text"
                           name="meta_keywords"
                           class="form-control"
                           placeholder="tour, travel, bangladesh">
                </div>

                {{-- CANONICAL --}}
                <div class="mb-3">
                    <label>Canonical URL</label>
                    <input type="text"
                           name="canonical_url"
                           class="form-control">
                </div>

                <button class="btn btn-success">
                    Save
                </button>

                <a href="{{ route('admin.seo.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>

    </div>

</div>

@endsection