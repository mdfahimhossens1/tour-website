@extends('layouts.admin')

@section('title','Contact Messages')

@section('page')

<div class="card">

    <div class="card-header">
        <h4>Contact Messages</h4>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

            @foreach($messages as $key => $message)

                <tr>

                    <td>{{ $key+1 }}</td>

                    <td>{{ $message->name }}</td>

                    <td>{{ $message->email }}</td>

                    <td>{{ $message->subject }}</td>

                    <td>

                        @if($message->is_read)

                            <span class="badge bg-success">
                                Read
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Unread
                            </span>

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('admin.contact.show',$message->id) }}"
                           class="btn btn-info btn-sm">

                            View

                        </a>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

        {{ $messages->links() }}

    </div>

</div>

@endsection