@extends('layouts.admin')
@section('page')
                    <div class="row">
                        <div class="col-md-12 ">
                            <form method="post" action="{{route('dashboard.user.store')}}" enctype="multipart/form-data">
                              @csrf
                                <div class="card mb-3">
                                        @if(session('success'))
                                      <div style="color:green">{{ session('success') }}</div>
                                        @endif
                                  <div class="card-header">
                                    <div class="row">

                                        <div class="col-md-8 col-8 card_title_part">
                                            <i class="fab fa-gg-circle"></i>User Registration
                                        </div>  
                                        <div class="col-md-4 col-4 card_button_part">
                                            <a href="{{url('dashboard/user')}}" class="btn btn-sm btn-dark"><i class="fas fa-th"></i>All User</a>
                                        </div>  
                                    </div>
                                  </div>
                                  <div class="card-body">
                                      <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label col_form_label">Name<span class="req_star">*</span>:</label>
                                        <div class="col-sm-7">
                                          <input type="text" class="form-control form_control" id="" name="name" value="{{old('name')}}">
                                          @error('name')<div style="color:red">{{ $message }}</div>@enderror
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label col_form_label">Phone:</label>
                                        <div class="col-sm-7">
                                          <input type="text" class="form-control form_control" id="" name="phone" value="{{old('phone')}}">
                                          @error('phone')<div style="color:red">{{ $message }}</div>@enderror
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label col_form_label">Email<span class="req_star">*</span>:</label>
                                        <div class="col-sm-7">
                                          <input type="email" class="form-control form_control" id="" name="email" value="{{old('email')}}">
                                          @error('email')<div style="color:red">{{ $message }}</div>@enderror
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label col_form_label">Username<span class="req_star">*</span>:</label>
                                        <div class="col-sm-7">
                                          <input type="text" class="form-control form_control" id="" name="username" value="{{old('username')}}">
                                          @error('username')<div style="color:red">{{ $message }}</div>@enderror
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label col_form_label">Password<span class="req_star">*</span>:</label>
                                        <div class="col-sm-7">
                                          <input type="password" class="form-control form_control" id="" name="password">
                                          @error('password')<div style="color:red">{{ $message }}</div>@enderror
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label col_form_label">Confirm-Password<span class="req_star">*</span>:</label>
                                        <div class="col-sm-7">
                                          <input type="password" class="form-control form_control" id="" name="password_confirmation">
                                          @error('password_confirmation')<div style="color:red">{{ $message }}</div>@enderror
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label col_form_label">User Role<span class="req_star">*</span>:</label>
                                        <div class="col-sm-4">
                                          <select class="form-control form_control" id="" name="role_id">
                                            <option>Select Role</option>
                                            @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{old('role_id')==$role->id ? 'selected' : ''}}>
                                              {{$role->role_name}}
                                            </option>
                                            @endforeach
                                          </select>
                                          @error('role_id')<div style="color:red">{{ $message }}</div>@enderror
                                        </div>
                                      </div>
                                      <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label col_form_label">Photo:</label>
                                        <div class="col-sm-4">
                                          <input type="file" class="form-control form_control" id="" name="photo" accept="image/*">
                                          @error('photo') <div style="color:red">{{ $message }}</div> @enderror
                                        </div>
                                      </div>
                                  </div>
                                  <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-sm btn-dark">Add User</button>
                                  </div>  
                                </div>
                            </form>
                        </div>
                    </div>
@endsection