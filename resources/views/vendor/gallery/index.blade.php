@extends('layouts.admin')

@section('page')

<div class="card p-3 mb-3">

    <h4>Gallery for: {{ $tour->title }}</h4>

    {{-- Upload Form --}}
<form action="{{ route('gallery.store', $tour->slug) }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf

    <input type="file" name="images[]" multiple class="form-control mb-2">

    <button class="btn btn-primary">
        Upload Images
    </button>
</form>

</div>

<div class="row">

    @foreach($galleries as $img)
    <div class="col-md-3 mb-3">

        <div class="card">

            <img src="{{ asset($img->image) }}"
                 class="w-100"
                 style="height:180px; object-fit:cover;">

            <div class="p-2 text-center">

    <form action="{{ route('gallery.destroy', $img->id) }}"
        method="POST">
        @csrf
        @method('DELETE')

        <button class="btn btn-danger btn-sm">
            Delete
        </button>
    </form>

            </div>

        </div>

    </div>
    @endforeach

</div>

@endsection