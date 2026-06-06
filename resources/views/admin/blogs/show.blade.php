@extends('layouts.admin')

@section('title','View Blog')

@section('page')

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">{{ $blog->title }}</h4>

            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">
                Back
            </a>

        </div>

        <div class="card-body">

            {{-- IMAGE --}}
            @if($blog->image)
                <img src="{{ asset('uploads/blogs/'.$blog->image) }}"
                     class="img-fluid mb-4 rounded"
                     style="max-height:400px; object-fit:cover;">
            @endif

            {{-- INFO TABLE --}}
            <table class="table table-bordered">

                <tr>
                    <th width="200">Title</th>
                    <td>{{ $blog->title }}</td>
                </tr>

                <tr>
                    <th>Slug</th>
                    <td>{{ $blog->slug }}</td>
                </tr>

                <tr>
                    <th>Meta Title</th>
                    <td>{{ $blog->meta_title ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Meta Description</th>
                    <td>{{ $blog->meta_description ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @if($blog->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>{{ $blog->created_at->format('d M Y h:i A') }}</td>
                </tr>

            </table>

            {{-- DESCRIPTION --}}
            <div class="mt-4">

                <h5>Description</h5>
                <hr>

                <div class="p-2 border rounded bg-light">
                    {!! $blog->description !!}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection