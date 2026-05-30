@extends('layouts.admin')
@section('title', 'Add Traveler')
@section('page')

<div class="card">

    <div class="card-header d-flex justify-content-between">

        <h5>Add Traveler</h5>

        <a href="{{ route('admin.travelers.index') }}"
           class="btn btn-dark btn-sm">

            All Travelers

        </a>

    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.travelers.store') }}">

            @csrf

            <div class="row">

                {{-- Booking --}}
                <div class="col-md-6 mb-3">

                    <label>Booking *</label>

                    <select name="booking_id" class="form-control">

                        <option value="">Select Booking</option>

                        @foreach($bookings as $booking)

                            <option value="{{ $booking->id }}">
                                {{ $booking->booking_code }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Name --}}
                <div class="col-md-6 mb-3">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control">
                </div>

                {{-- Phone --}}
                <div class="col-md-6 mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                {{-- Age --}}
                <div class="col-md-6 mb-3">
                    <label>Age</label>
                    <input type="number" name="age" class="form-control">
                </div>

                {{-- Gender --}}
                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                {{-- Address --}}
                <div class="col-md-12 mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>

            </div>

            <button class="btn btn-dark">
                Save Traveler
            </button>

        </form>

    </div>

</div>

@endsection