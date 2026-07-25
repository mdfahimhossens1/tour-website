@extends('layouts.admin')

@section('title', 'Edit Team Member')

@section('page')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">{{ __('Edit Team Member') }}</h4>
    </div>
    <div class="card-body">
        <form 
            action="{{ route('admin.team-members.update', $teamMember) }}" 
            method="POST" 
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.team-members._form')
        </form>
    </div>
</div>
@endsection