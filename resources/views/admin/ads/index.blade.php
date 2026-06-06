@extends('layouts.admin')

@section('title','Ads Management')

@section('page')

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between">

            <h4>Advertisements</h4>

            <a href="{{ route('admin.ads.create') }}"
               class="btn btn-primary">

                Add Advertisement

            </a>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Position</th>
                        <th>Views</th>
                        <th>Clicks</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($ads as $key => $ad)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>

                            <img
                                src="{{ asset('uploads/ads/'.$ad->image) }}"
                                width="80">

                        </td>

                        <td>{{ $ad->title }}</td>

                        <td>{{ $ad->position }}</td>

                        <td>{{ $ad->views }}</td>

                        <td>{{ $ad->clicks }}</td>

                        <td>

                            @if($ad->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td>

                            <form
                                action="{{ route('admin.ads.delete',$ad->id) }}"
                                method="POST">

                                @csrf

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="text-center">
                            No Advertisement Found
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

            {{ $ads->links() }}

        </div>

    </div>

</div>

@endsection