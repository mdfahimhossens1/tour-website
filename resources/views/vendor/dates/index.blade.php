@extends('layouts.admin')

@section('page')

<div class="card p-3 mb-3">

    <h4>Tour Dates - {{ $tour->title }}</h4>

    <form action="{{ route('vendor.dates.store', $tour->slug) }}"
          method="POST">
        @csrf

        <div class="row">

            <div class="col-md-4">
                <input type="date" name="start_date" class="form-control">
            </div>

            <div class="col-md-4">
                <input type="date" name="end_date" class="form-control">
            </div>

            <div class="col-md-4">
                <input type="number" name="available_seat"
                       class="form-control"
                       placeholder="Seats">
            </div>

        </div>

        <button class="btn btn-primary mt-2">
            Add Date
        </button>

    </form>

</div>

<div class="card p-3">

    <table class="table">

        <thead>
            <tr>
                <th>Start</th>
                <th>End</th>
                <th>Seats</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            @foreach($dates as $date)

            <tr>
                <td>{{ $date->start_date }}</td>
                <td>{{ $date->end_date }}</td>
                <td>{{ $date->available_seat }}</td>

                <td>
                    <form action="{{ route('vendor.dates.destroy', $date->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection