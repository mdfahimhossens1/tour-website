@extends('layouts.admin')

@section('title','Testimonials')

@section('page')

<div class="container-fluid">

    <div class="card">

        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">All Testimonials</h4>

            <a href="{{ route('admin.testimonials.create') }}"
               class="btn btn-primary btn-sm">

                + Add Testimonial

            </a>

        </div>

        <div class="card-body">

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TABLE --}}
            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($testimonials as $key => $t)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>
                            @if($t->image)
                                <img src="{{ asset('uploads/testimonials/'.$t->image) }}"
                                     width="60" height="60"
                                     style="object-fit:cover; border-radius:50%;">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>

                        <td>{{ $t->name }}</td>

                        <td>{{ $t->designation ?? '-' }}</td>

                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $t->rating)
                                    ⭐
                                @endif
                            @endfor
                        </td>

                        <td>
                            @if($t->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                        <td>

                            <form action="{{ route('admin.testimonials.delete',$t->id) }}"
                                  method="POST">

                                @csrf

                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this testimonial?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center">
                            No Testimonials Found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $testimonials->links() }}
            </div>

        </div>

    </div>

</div>

@endsection