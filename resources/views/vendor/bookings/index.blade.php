@extends('layouts.admin')
@section('page')

<h3>My Bookings</h3>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tour</th>
            <th>User</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td>#{{ $booking->id }}</td>
            <td>{{ $booking->tour->title ?? '' }}</td>
            <td>{{ $booking->user->name ?? '' }}</td>
            <td>{{ $booking->booking_status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $bookings->links() }}

@endsection