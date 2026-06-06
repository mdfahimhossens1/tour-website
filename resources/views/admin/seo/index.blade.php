@extends('layouts.admin')

@section('title','SEO Settings')

@section('page')

<div class="container-fluid">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">SEO Settings</h4>

            <a href="{{ route('admin.seo.create') }}"
               class="btn btn-primary btn-sm">
                + Add SEO
            </a>

        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Page</th>
                        <th>Meta Title</th>
                        <th>Meta Description</th>
                        <th>Keywords</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($seo as $key => $s)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>
                            <span class="badge bg-dark">
                                {{ ucfirst($s->page) }}
                            </span>
                        </td>

                        <td>{{ $s->meta_title ?? '-' }}</td>

                        <td>
                            {{ \Illuminate\Support\Str::limit($s->meta_description, 60) }}
                        </td>

                        <td>{{ $s->meta_keywords ?? '-' }}</td>

                        <td>

                            <a href="{{ route('admin.seo.edit',$s->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('admin.seo.delete',$s->id) }}"
                                  method="POST"
                                  style="display:inline-block">

                                @csrf

                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete?')">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center">
                            No SEO Settings Found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $seo->links() }}
            </div>

        </div>

    </div>

</div>

@endsection