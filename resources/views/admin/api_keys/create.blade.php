@extends('layouts.admin')
@section('title','Generate API Key')
@section('page')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            <h4>Generate New API Key</h4>

        </div>

        <div class="card-body">

            <form action="{{ route('admin.api.keys.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">

                        API Name

                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Example: Flutter App"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-success">

                    Generate API Key

                </button>

                <a href="{{ route('admin.api.keys.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

@endsection