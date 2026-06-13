@extends('layouts.admin')
@section('page')

<div class="card">

    <div class="card-header d-flex justify-content-between">

        <h4>My Tours</h4>

        <a href="{{ route('vendor.tours.create') }}"
           class="btn btn-primary">
            Add Tour
        </a>

    </div>

    <div class="card-body">

        <table class="table">

            <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach($tours as $tour)

                <tr>

                    <td>{{ $tour->title }}</td>

                    <td>{{ $tour->price }}</td>

                    <td>
                        {{ $tour->status ? 'Active' : 'Inactive' }}
                    </td>

                    <td>

                        <a href="{{ route('vendor.tours.edit',$tour->slug) }}"
                           class="btn btn-sm btn-warning">
                            Edit
                        </a>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        {{ $tours->links() }}

    </div>

</div>

@endsection