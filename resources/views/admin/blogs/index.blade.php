@extends('layouts.admin')

@section('title','Blog Management')

@section('page')

<div class="container-fluid">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">All Blogs</h4>

            <a href="{{ route('admin.blogs.create') }}"
               class="btn btn-primary">

                <i class="fas fa-plus"></i> Add New Blog

            </a>

        </div>

        {{-- BODY --}}
        <div class="card-body">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-striped align-middle">

                <thead class="table-dark">

                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="220">Action</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($blogs as $key => $blog)

                    <tr>

                        {{-- SERIAL --}}
                        <td>
                            {{ $blogs->firstItem() + $key }}
                        </td>

                        {{-- IMAGE --}}
                        <td>

                            @if($blog->image)

                                <img src="{{ asset('uploads/blogs/'.$blog->image) }}"
                                     width="70"
                                     style="border-radius:8px;">

                            @else

                                <span class="text-muted">No Image</span>

                            @endif

                        </td>

                        {{-- TITLE --}}
                        <td>
                            {{ $blog->title }}
                        </td>

                        {{-- CATEGORY --}}
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $blog->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>

                        {{-- SLUG --}}
                        <td>
                            <small class="text-muted">
                                {{ $blog->slug }}
                            </small>
                        </td>

                        {{-- STATUS --}}
                        <td>

                            @if($blog->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        {{-- CREATED --}}
                        <td>
                            {{ $blog->created_at->format('d M Y') }}
                        </td>

                        {{-- ACTION --}}
                        <td>

                            <a href="{{ route('admin.blogs.show',$blog->slug) }}"
                               class="btn btn-info btn-sm">

                                <i class="fas fa-eye"></i>

                            </a>

                            <a href="{{ route('admin.blogs.edit',$blog->slug) }}"
                               class="btn btn-warning btn-sm">

                                <i class="fas fa-edit"></i>

                            </a>

                            <form method="POST"
                                  action="{{ route('admin.blogs.delete',$blog->slug) }}"
                                  style="display:inline-block">

                                @csrf

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this blog?')">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No Blog Found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $blogs->links() }}
            </div>

        </div>

    </div>

</div>

@endsection