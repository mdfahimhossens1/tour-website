@extends('layouts.admin')

@section('title', 'Team Members')

@section('page')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">{{ __('Team Members') }}</h4>
        <a href="{{ route('admin.team-members.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> {{ __('Add New') }}
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="60">{{ __('Photo') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Designation') }}</th>
                        <th>{{ __('Contact') }}</th>
                        <th width="80">{{ __('Status') }}</th>
                        <th width="80">{{ __('Sort') }}</th>
                        <th width="180">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>
                                @if($member->image)
                                    <img 
                                        src="{{ asset('uploads/'.$member->image) }}" 
                                        alt="{{ $member->name }}"
                                        class="rounded-circle"
                                        width="40" 
                                        height="40"
                                        style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width:40px;height:40px;color:white;font-weight:bold;">
                                        {{ substr($member->name, 0, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $member->name }}</strong>
                                @if($member->bio_en)
                                    <br><small class="text-muted">{{ Str::limit($member->bio_en, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $member->designation_en }}</div>
                                <small class="text-muted">{{ $member->designation_bn }}</small>
                            </td>
                            <td>
                                @if($member->email)
                                    <div><i class="fas fa-envelope text-muted"></i> {{ $member->email }}</div>
                                @endif
                                @if($member->phone)
                                    <div><i class="fas fa-phone text-muted"></i> {{ $member->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.team-members.toggle-status', $member) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $member->status ? 'btn-success' : 'btn-danger' }}">
                                        {{ $member->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">{{ $member->sort_order ?? 0 }}</td>
                            <td>
                                <a href="{{ route('admin.team-members.edit', $member) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.team-members.destroy', $member) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-3 d-block"></i>
                                {{ __('No team members found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection