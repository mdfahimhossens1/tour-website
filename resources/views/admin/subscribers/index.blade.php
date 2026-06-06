@extends('layouts.admin')

@section('title','Subscribers')

@section('page')

<div class="card">

    <div class="card-header">
        <h4>All Subscribers</h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach($subscribers as $key => $subscriber)

                <tr>

                    <td>{{ $key+1 }}</td>

                    <td>{{ $subscriber->email }}</td>

                    <td>
                        {{ $subscriber->created_at->format('d M Y') }}
                    </td>

                    <td>

                        <form method="POST"
                              action="{{ route('admin.subscribers.delete',$subscriber->id) }}">

                            @csrf

                            <button class="btn btn-danger btn-sm">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        {{ $subscribers->links() }}

    </div>

</div>

@endsection