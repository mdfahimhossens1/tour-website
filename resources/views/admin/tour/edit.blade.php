@extends('layouts.admin')

@section('title', 'Edit Tour Package')

@section('page')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Edit Tour Package
        </h5>

        <a href="{{ route('admin.tours.index') }}"
           class="btn btn-dark btn-sm">

            All Packages

        </a>

    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.tours.update', $tour->id) }}"
              enctype="multipart/form-data">

            @csrf

            <div class="row">

                {{-- Title --}}
                <div class="col-md-6 mb-3">

                    <label>
                        Tour Title *
                    </label>

                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title', $tour->title) }}">

                    @error('title')

                        <small class="text-danger">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

                {{-- Destination --}}
                <div class="col-md-6 mb-3">

                    <label>
                        Destination *
                    </label>

                    <select name="destination_id"
                            class="form-control">

                        <option value="">
                            Select Destination
                        </option>

                        @foreach($destinations as $destination)

                            <option value="{{ $destination->id }}"
                                {{ old('destination_id', $tour->destination_id) == $destination->id ? 'selected' : '' }}>

                                {{ $destination->name }}

                            </option>

                        @endforeach

                    </select>

                    @error('destination_id')

                        <small class="text-danger">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

                {{-- Price --}}
                <div class="col-md-6 mb-3">

                    <label>
                        Price *
                    </label>

                    <input type="number"
                           step="0.01"
                           name="price"
                           class="form-control"
                           value="{{ old('price', $tour->price) }}">

                    @error('price')

                        <small class="text-danger">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

                {{-- Discount Price --}}
                <div class="col-md-6 mb-3">

                    <label>
                        Discount Price
                    </label>

                    <input type="number"
                           step="0.01"
                           name="discount_price"
                           class="form-control"
                           value="{{ old('discount_price', $tour->discount_price) }}">

                </div>

                {{-- Duration --}}
                <div class="col-md-6 mb-3">

                    <label>
                        Duration
                    </label>

                    <input type="text"
                           name="duration"
                           class="form-control"
                           placeholder="3 Days 2 Nights"
                           value="{{ old('duration', $tour->duration) }}">

                </div>

                {{-- Location --}}
                <div class="col-md-6 mb-3">

                    <label>
                        Location
                    </label>

                    <input type="text"
                           name="location"
                           class="form-control"
                           value="{{ old('location', $tour->location) }}">

                </div>

                {{-- Max Seat --}}
                <div class="col-md-6 mb-3">

                    <label>
                        Maximum Seat
                    </label>

                    <input type="number"
                           name="max_seat"
                           class="form-control"
                           value="{{ old('max_seat', $tour->max_seat) }}">

                </div>

                {{-- Featured --}}
                <div class="col-md-3 mb-3">

                    <label>
                        Featured?
                    </label>

                    <select name="is_featured"
                            class="form-control">

                        <option value="1"
                            {{ old('is_featured', $tour->is_featured) == 1 ? 'selected' : '' }}>

                            Yes

                        </option>

                        <option value="0"
                            {{ old('is_featured', $tour->is_featured) == 0 ? 'selected' : '' }}>

                            No

                        </option>

                    </select>

                </div>

                {{-- Status --}}
                <div class="col-md-3 mb-3">

                    <label>
                        Status
                    </label>

                    <select name="status"
                            class="form-control">

                        <option value="1"
                            {{ old('status', $tour->status) == 1 ? 'selected' : '' }}>

                            Active

                        </option>

                        <option value="0"
                            {{ old('status', $tour->status) == 0 ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>

                </div>

                {{-- Current Image --}}
                <div class="col-md-12 mb-3">

                    <label>
                        Current Image
                    </label>

                    <br>

                    @if($tour->featured_image)

                        <img src="{{ asset('uploads/tours/'.$tour->featured_image) }}"
                             width="120"
                             height="80"
                             style="object-fit:cover; border-radius:10px;">

                    @else

                        <img src="{{ asset('contents/admin/images/no-image.png') }}"
                             width="120">

                    @endif

                </div>

                {{-- Featured Image --}}
                <div class="col-md-12 mb-3">

                    <label>
                        Change Featured Image
                    </label>

                    <input type="file"
                           name="featured_image"
                           class="form-control">

                    @error('featured_image')

                        <small class="text-danger">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

                {{-- Short Description --}}
                <div class="col-md-12 mb-3">

                    <label>
                        Short Description
                    </label>

                    <textarea name="short_description"
                              rows="3"
                              class="form-control">{{ old('short_description', $tour->short_description) }}</textarea>

                </div>

                {{-- Description --}}
                <div class="col-md-12 mb-3">

                    <label>
                        Description
                    </label>

                    <textarea name="description"
                              rows="5"
                              class="form-control">{{ old('description', $tour->description) }}</textarea>

                </div>

                {{-- Included --}}
                <div class="col-md-12 mb-3">

                    <label>
                        Included
                    </label>

                    <textarea name="included"
                              rows="4"
                              class="form-control">{{ old('included', $tour->included) }}</textarea>

                </div>

                {{-- Excluded --}}
                <div class="col-md-12 mb-3">

                    <label>
                        Excluded
                    </label>

                    <textarea name="excluded"
                              rows="4"
                              class="form-control">{{ old('excluded', $tour->excluded) }}</textarea>

                </div>

                {{-- Tour Plan --}}
                <div class="col-md-12 mb-3">

                    <label>
                        Tour Plan
                    </label>

                    <textarea name="tour_plan"
                              rows="5"
                              class="form-control">{{ old('tour_plan', $tour->tour_plan) }}</textarea>

                </div>

                {{-- Map --}}
                <div class="col-md-12 mb-3">

                    <label>
                        Google Map iframe
                    </label>

                    <textarea name="map_iframe"
                              rows="3"
                              class="form-control">{{ old('map_iframe', $tour->map_iframe) }}</textarea>

                </div>

            </div>

            <button type="submit"
                    class="btn btn-dark">

                Update Tour Package

            </button>

        </form>

    </div>

</div>

@endsection