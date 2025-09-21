@extends('layouts.app')
@section('title', 'Kelola User')

@section('content')
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card text-white">
        <div class="card-body">

          <!-- Title & Add Button -->
          <div class="pagetitle mt-4 mb-4 d-flex justify-content-between align-items-center">
            <h1 class="text-uppercase fw-bold">@yield('title')</h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
              <i class="bi bi-plus"></i> Add User
            </button>
          </div>

          <!-- User Table -->
          <table class="table table-bordered table-hover datatable">
            <thead class="table-dark">
              <tr>
                <th width="5%">#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th width="20%">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($datas as $i => $user)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>
                    @php
                      $role = $roles->firstWhere('id', $user->role_id);
                    @endphp
                    {{ $role->name ?? '-' }}
                  </td>
                  <td>
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                      <i class="bi bi-pencil"></i>
                    </button>

                    <!-- Delete Form -->
                    <form class="d-inline" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger btn-hapus" data-name="{{ $user->name }}">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <form id="formEditUser{{ $user->id }}" action="{{ route('admin.users.update', $user->id) }}" method="POST">
                          @csrf
                          @method('PUT')

                          <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                          </div>

                          <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                          </div>

                          <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role_id" class="form-select" required>
                              @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                  {{ $role->name }}
                                </option>
                              @endforeach
                            </select>
                          </div>

                          <div class="mb-3">
                            <label class="form-label">Password (kosongkan jika tidak diganti)</label>
                            <input type="password" name="password" class="form-control">
                          </div>

                          <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="formEditUser{{ $user->id }}" class="btn btn-primary">Update</button>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Tidak ada user</td>
                </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Create Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formAddUser" action="{{ route('admin.users.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
              <option value="">-- Select Role --</option>
              @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="formAddUser" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  // SweetAlert Delete Confirmation
  $('.btn-hapus').click(function(e) {
    e.preventDefault();
    var form = $(this).closest('form');
    var dataName = $(this).data('name');

    Swal.fire({
      title: `Delete "${dataName}"?`,
      text: "Are you sure you want to delete this user?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
</script>
@endsection
