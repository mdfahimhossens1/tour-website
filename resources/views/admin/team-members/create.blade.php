@extends('layouts.admin')

@section('title', 'Create Team Member')

@section('page')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">{{ __('Add New Team Member') }}</h4>
    </div>
    <div class="card-body">
        <form 
            action="{{ route('admin.team-members.store') }}" 
            method="POST" 
            enctype="multipart/form-data">
            @csrf
            @include('admin.team-members._form')
        </form>
    </div>
</div>
@endsection