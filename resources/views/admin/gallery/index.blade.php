@extends('layouts.admin')

@section('title','Gallery')

@section('page')

<div class="container-fluid">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Gallery</h4>

            <a href="{{ route('admin.gallery.create') }}"
               class="btn btn-primary btn-sm">

                + Upload Image

            </a>

        </div>

        <div class="card-body">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- GRID VIEW --}}
            <div class="row">

                @forelse($galleries as $gallery)

                    <div class="col-md-3 mb-4">

                        <div class="card shadow-sm">

                            {{-- IMAGE --}}
                            <img src="{{ asset('uploads/gallery/'.$gallery->image) }}"
                                 class="card-img-top"
                                 style="height:180px; object-fit:cover;">

                            <div class="card-body p-2">

                                {{-- TOUR NAME --}}
                                <small class="text-muted">
                                    {{ $gallery->tour->title ?? 'No Tour' }}
                                </small>

                                <br>

                                {{-- TYPE BADGE --}}
                                @if($gallery->type == 'video')
                                    <span class="badge bg-danger">Video</span>
                                @else
                                    <span class="badge bg-success">Image</span>
                                @endif

                                {{-- DELETE --}}
                                <form action="{{ route('admin.gallery.delete',$gallery->id) }}"
                                      method="POST"
                                      class="mt-2">

                                    @csrf

                                    <button class="btn btn-danger btn-sm w-100"
                                            onclick="return confirm('Delete this image?')">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-12 text-center text-muted">
                        No Gallery Found
                    </div>

                @endforelse

            </div>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $galleries->links() }}
            </div>

        </div>

    </div>

</div>

@endsection