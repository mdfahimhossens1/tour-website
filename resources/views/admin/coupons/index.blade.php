@extends('layouts.admin')
@section('title','Coupons')
@section('page')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h5>Coupons</h5>

        <a href="{{ route('admin.coupons.create') }}" class="btn btn-dark btn-sm">
            Add Coupon
        </a>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->type }}</td>
                    <td>{{ $coupon->value }}</td>
                    <td>
                        @if($coupon->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.coupons.edit',$coupon->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form method="POST"
                              action="{{ route('admin.coupons.delete',$coupon->id) }}"
                              style="display:inline;">
                            @csrf
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection