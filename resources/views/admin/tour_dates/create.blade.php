@extends('layouts.admin')
@section('title', 'Add Tour Date')
@section('page')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Add Tour Date
        </h5>

        <a href="{{ route('admin.tour.dates.index') }}"
           class="btn btn-dark btn-sm">

            All Dates

        </a>

    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.tour.dates.store') }}">

            @csrf

            <div class="row">

                {{-- TOUR --}}
                <div class="col-md-6 mb-3">

                    <label>Tour *</label>

                    <select name="tour_id"
                            class="form-control">

                        <option value="">
                            Select Tour
                        </option>

                        @foreach($tours as $tour)

                            <option value="{{ $tour->id }}">
                                {{ $tour->title }}
                            </option>

                        @endforeach

                    </select>

                    @error('tour_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- START DATE --}}
                <div class="col-md-3 mb-3">

                    <label>Start Date *</label>

                    <input type="date"
                           name="start_date"
                           class="form-control">

                    @error('start_date')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- END DATE --}}
                <div class="col-md-3 mb-3">

                    <label>End Date *</label>

                    <input type="date"
                           name="end_date"
                           class="form-control">

                    @error('end_date')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- AVAILABLE SEAT --}}
                <div class="col-md-6 mb-3">

                    <label>Available Seat *</label>

                    <input type="number"
                           name="available_seat"
                           class="form-control">

                </div>

                {{-- PRICE --}}
                <div class="col-md-6 mb-3">

                    <label>Special Price</label>

                    <input type="number"
                           step="0.01"
                           name="price"
                           class="form-control">

                </div>

                {{-- STATUS --}}
                <div class="col-md-6 mb-3">

                    <label>Status</label>

                    <select name="status"
                            class="form-control">

                        <option value="1">
                            Active
                        </option>

                        <option value="0">
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

            <button class="btn btn-dark">
                Save Tour Date
            </button>

        </form>

    </div>

</div>

@endsection