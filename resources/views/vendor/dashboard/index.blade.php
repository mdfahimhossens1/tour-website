@extends('layouts.admin')
@section('content')

<div class="container-fluid">

    <h3>Vendor Dashboard</h3>

    <div class="row">

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6>Total Tours</h6>
                    <h2>{{ $totalTours }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6>Total Bookings</h6>
                    <h2>{{ $totalBookings }}</h2>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection