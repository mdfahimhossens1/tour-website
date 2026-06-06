@extends('layouts.admin')

@section('title','Create Advertisement')

@section('page')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            <h4>Create Advertisement</h4>

        </div>

        <div class="card-body">

            <form
                action="{{ route('admin.ads.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Advertisement Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="{{ old('title') }}">

                    @error('title')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Advertisement Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control">

                    @error('image')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Target Link
                    </label>

                    <input
                        type="url"
                        name="link"
                        class="form-control"
                        placeholder="https://example.com">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Position
                    </label>

                    <select
                        name="position"
                        class="form-control">

                        <option value="homepage_banner">
                            Homepage Banner
                        </option>

                        <option value="sidebar">
                            Sidebar
                        </option>

                        <option value="package_page">
                            Package Page
                        </option>

                        <option value="footer">
                            Footer
                        </option>

                    </select>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <label>
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control">

                    </div>

                    <div class="col-md-6">

                        <label>
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control">

                    </div>

                </div>

                <br>

                <div class="mb-3">

                    <label>
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-control">

                        <option value="1">
                            Active
                        </option>

                        <option value="0">
                            Inactive
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="btn btn-success">

                    Save Advertisement

                </button>

                <a
                    href="{{ route('admin.ads.index') }}"
                    class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

@endsection