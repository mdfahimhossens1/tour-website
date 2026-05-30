@extends('layouts.admin')
@section('title', 'Travelers')
@section('page')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5>All Travelers</h5>

        <a href="{{ route('admin.travelers.create') }}"
           class="btn btn-dark btn-sm">

            Add Traveler

        </a>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-dark">

                <tr>
                    <th>#</th>
                    <th>Booking Code</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Gender</th>
                </tr>

            </thead>

            <tbody>

                @forelse($travelers as $traveler)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $traveler->booking->booking_code ?? 'N/A' }}
                    </td>

                    <td>{{ $traveler->name }}</td>

                    <td>{{ $traveler->phone }}</td>

                    <td>{{ $traveler->email }}</td>

                    <td>{{ $traveler->age }}</td>

                    <td>{{ $traveler->gender }}</td>

                </tr>

                @empty

                <tr>

                    <td colspan="7" class="text-center text-muted">
                        No Travelers Found
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection