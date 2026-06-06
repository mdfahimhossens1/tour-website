@extends('layouts.admin')

@section('title','Message Details')

@section('page')

<div class="card">

    <div class="card-header">
        <h4>Message Details</h4>
    </div>

    <div class="card-body">

        <p>
            <strong>Name:</strong>
            {{ $message->name }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ $message->email }}
        </p>

        <p>
            <strong>Phone:</strong>
            {{ $message->phone }}
        </p>

        <p>
            <strong>Subject:</strong>
            {{ $message->subject }}
        </p>

        <hr>

        <p>
            {{ $message->message }}
        </p>

        <form method="POST"
              action="{{ route('admin.contact.delete',$message->id) }}">

            @csrf

            <button class="btn btn-danger">
                Delete
            </button>

        </form>

    </div>

</div>

@endsection