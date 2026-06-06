@extends('layouts.admin')
@section('title','Vendors')
@section('page')

<div class="container-fluid">

<div class="card">

<div class="card-header">
    <h4>All Vendors</h4>
</div>

<div class="card-body">

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table class="table table-bordered">

<thead>
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Business</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

@foreach($vendors as $key => $vendor)

<tr>
    <td>{{ $key+1 }}</td>
    <td>{{ $vendor->user->name }}</td>
    <td>{{ $vendor->business_name }}</td>
    <td>
        <span class="badge bg-{{ $vendor->status=='approved' ? 'success':'warning' }}">
            {{ $vendor->status }}
        </span>
    </td>
    <td>

        @if($vendor->status=='pending')
        <form method="POST" action="{{ route('admin.vendors.approve',$vendor->id) }}">
            @csrf
            <button class="btn btn-sm btn-success">Approve</button>
        </form>
        @endif

    </td>
</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</div>

@endsection