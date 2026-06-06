@extends('layouts.admin')

@section('title','Upload Gallery')

@section('page')

<div class="container-fluid">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header">
            <h4>Upload New Gallery Image</h4>
        </div>

        <div class="card-body">

            {{-- VALIDATION ERROR --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.gallery.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- TOUR SELECT --}}
                <div class="mb-3">

                    <label class="form-label">Select Tour</label>

                    <select name="tour_id" class="form-control" required>

                        <option value="">Select Tour</option>

                        @foreach($tours as $tour)

                            <option value="{{ $tour->id }}">
                                {{ $tour->title }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- IMAGE --}}
                <div class="mb-3">

                    <label class="form-label">Upload Image</label>

                    <input type="file"
                           name="image"
                           class="form-control"
                           required>

                </div>

                {{-- TYPE --}}
                <div class="mb-3">

                    <label class="form-label">Type</label>

                    <select name="type" class="form-control">

                        <option value="image">Image</option>
                        <option value="video">Video</option>

                    </select>

                </div>

                {{-- BUTTONS --}}
                <button type="submit" class="btn btn-success">
                    Upload
                </button>

                <a href="{{ route('admin.gallery.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>

    </div>

</div>

@endsection