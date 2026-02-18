@extends('layouts.admin')
@section('page')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-3">
                              <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8 col-8 card_title_part">
                                        <i class="fab fa-gg-circle"></i>All User Information
                                    </div>  
                                    <div class="col-md-4 col-4 card_button_part">
                                        <a href="{{url('dashboard/user/add')}}" class="btn btn-sm btn-dark"><i class="fas fa-plus-circle"></i>Add User</a>
                                    </div>  
                                </div>
                              </div>
                              <div class="card-body">
                                @php $myRole = strtolower(Auth::user()->role->role_name ?? ''); @endphp
                                <table class="table table-bordered table-striped table-hover custom_table">
                                  <thead class="table-dark">
                                    <tr>
                                      <th>Name</th>
                                      <th>Phone</th>
                                      <th>Email</th>
                                      <th>Username</th>
                                      @if($myRole !== 'viewer')
                                      <th>Role</th>
                                      @endif
                                      <th>Status</th>
                                      <th>Photo</th>
                                      <th>Manage</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                      <td>{{$user->name}}</td>
                                      <td>{{$user->phone}}</td>
                                      <td>{{$user->email}}</td>
                                      <td>{{$user->username}}</td>
                                      @if($myRole !== 'viewer')
                                      <td>
                                      <span class="badge bg-info">
                                          {{ $user->role->role_name ?? 'N/A' }}
                                      </span>
                                      </td>
                                      @endif
                                      <td>
                                        @if($user->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                      </td>
                                      <td>
                                        @if($user->photo)
                                         <img src="{{asset('uploads/users/'.$user->photo)}}" alt="User Photo" width="50" height="50" style="object-fit: cover; border-radius: 50%;">  
                                         @else
                                        <img src="{{asset('contents/admin/images/avatar.jpg')}}" alt="Default Photo" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                                        @endif
                                      </td>
                                      <td>
                                          <div class="btn-group btn_group_manage" role="group">
                                            <button type="button" class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Manage</button>
                                            <ul class="dropdown-menu">
                                              <li><a class="dropdown-item" href="{{url('dashboard/user/view/' . $user->slug)}}">View</a></li>
                                              <li><a class="dropdown-item" href="{{url('dashboard/user/edit/' . $user->slug)}}">Edit</a></li>
                                              <li>
                                              <li>
                                                  <button 
                                                      type="button"
                                                      class="dropdown-item text-danger"
                                                      data-bs-toggle="modal"
                                                      data-bs-target="#deleteUserModal"
                                                      data-userid="{{ $user->id }}">
                                                      @if($user->id == 1) Disabled @endif
                                                      Delete
                                                  </button>
                                              </li>

                                            </ul>
                                          </div>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                                <!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title text-danger">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        Are you sure you want to delete this user?  
        <br>
        <small class="text-muted">This action cannot be undone.</small>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
          Cancel
        </button>

        <form id="deleteUserForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm">
            Yes, Delete
          </button>
        </form>
      </div>

    </div>
  </div>
</div>
                              </div>
                            </div>
                        </div>
                    </div>

  <script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteUserModal');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var userId = button.getAttribute('data-userid');

        var form = document.getElementById('deleteUserForm');
        form.action = "/dashboard/user/delete/" + userId;
    });
});
</script>

  @endsection   
