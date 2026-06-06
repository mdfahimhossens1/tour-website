@extends('layouts.admin')

@section('title','API Keys')

@section('page')

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">API Keys Management</h4>

            <a href="{{ route('admin.api.keys.create') }}"
               class="btn btn-primary">

                Generate New API Key

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
                        <th>Name</th>
                        <th>API Key</th>
                        <th>Status</th>
                        <th>Last Used</th>
                        <th width="180">Action</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($keys as $key => $api)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>{{ $api->name }}</td>

                        <td>

                            <code style="font-size:12px">
                                {{ $api->api_key }}
                            </code>

                        </td>

                        <td>

                            @if($api->status)

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

                            {{ $api->last_used_at ?? 'Never Used' }}

                        </td>

                        <td>

                            <form method="POST"
                                  action="{{ route('admin.api.keys.status',$api->id) }}"
                                  style="display:inline-block">

                                @csrf

                                <button type="submit"
                                        class="btn btn-warning btn-sm">

                                    {{ $api->status ? 'Disable' : 'Enable' }}

                                </button>

                            </form>

                            <form method="POST"
                                  action="{{ route('admin.api.keys.delete',$api->id) }}"
                                  style="display:inline-block">

                                @csrf

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete API Key?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">

                            No API Key Found

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

            {{ $keys->links() }}

        </div>

    </div>

</div>

@endsection