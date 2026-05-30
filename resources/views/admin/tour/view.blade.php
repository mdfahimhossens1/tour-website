@extends('layouts.admin')
@section('title', 'Tour Details')
@section('page')

<div class="card">

    <div class="card-header">

        <h5>
            {{ $tour->title }}
        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-5">

                <img src="{{ asset('uploads/tours/'.$tour->featured_image) }}"
                     class="img-fluid rounded">

            </div>

            <div class="col-md-7">

                <table class="table table-bordered">

                    <tr>
                        <th>Destination</th>
                        <td>{{ $tour->destination->name ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <th>Price</th>
                        <td>৳ {{ number_format($tour->price, 2) }}</td>
                    </tr>

                    <tr>
                        <th>Duration</th>
                        <td>{{ $tour->duration }}</td>
                    </tr>

                    <tr>
                        <th>Location</th>
                        <td>{{ $tour->location }}</td>
                    </tr>

                    <tr>
                        <th>Max Seat</th>
                        <td>{{ $tour->max_seat }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection