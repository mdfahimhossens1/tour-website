@extends('layouts.admin')

@section('title','Add Testimonial')

@section('page')

<div class="container-fluid">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header">
            <h4>Add New Testimonial</h4>
        </div>

        <div class="card-body">

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.testimonials.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- NAME --}}
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           required>
                </div>

                {{-- DESIGNATION --}}
                <div class="mb-3">
                    <label>Designation</label>
                    <input type="text"
                           name="designation"
                           class="form-control">
                </div>

                {{-- IMAGE --}}
                <div class="mb-3">
                    <label>Image</label>
                    <input type="file"
                           name="image"
                           class="form-control">
                </div>

                {{-- MESSAGE --}}
                <div class="mb-3">
                    <label>Message</label>
                    <textarea name="message"
                              rows="5"
                              class="form-control"
                              required></textarea>
                </div>

                {{-- RATING --}}
                <div class="mb-3">
                    <label>Rating (1 - 5)</label>

                    <select name="rating" class="form-control">

                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} Star</option>
                        @endfor

                    </select>
                </div>

                {{-- BUTTONS --}}
                <button type="submit" class="btn btn-success">
                    Save
                </button>

                <a href="{{ route('admin.testimonials.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>

    </div>

</div>

@endsection