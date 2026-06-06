@extends('layouts.admin')
@section('title','Payment Methods')
@section('page')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h4>Payment Methods</h4>
        <a href="{{ route('admin.payment_methods.create') }}" class="btn btn-primary">
            + Add Method
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Account</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($methods as $key => $method)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $method->name }}</td>
                            <td>{{ $method->type }}</td>
                            <td>{{ $method->account_number }}</td>
                            <td>
                                @if($method->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.payment_methods.edit',$method->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('admin.payment_methods.delete',$method->id) }}" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection