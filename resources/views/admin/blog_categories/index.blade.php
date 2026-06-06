@extends('layouts.admin')

@section('title','Blog Categories')

@section('page')

<div class="container-fluid">

<div class="row">

    <div class="col-md-4">

        <div class="card">

            <div class="card-header">
                <h5>Create Category</h5>
            </div>

            <div class="card-body">

                <form method="POST"
                      action="{{ route('admin.blog.categories.store') }}">

                    @csrf

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-success w-100">
                        Save
                    </button>

                </form>

            </div>

        </div>

    </div>

    <div class="col-md-8">

        <div class="card">

            <div class="card-header">
                <h5>All Categories</h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($categories as $key => $cat)

                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $cat->name }}</td>
                            <td>{{ $cat->slug }}</td>
                            <td>
                                @if($cat->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>

                                <a href="{{ route('admin.blog.categories.edit',$cat->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.blog.categories.delete',$cat->id) }}"
                                      style="display:inline-block">

                                    @csrf

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete?')">
                                        Delete
                                    </button>

                                </form>

                            </td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>

                {{ $categories->links() }}

            </div>

        </div>

    </div>

</div>

</div>

@endsection