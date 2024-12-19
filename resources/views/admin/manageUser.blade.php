@extends('base')

@section('head')
<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('assets/css/styleAdmin.css') }}">
@endsection

@section('body')
  <div class="wrapper">
    @include('partials.sideBarAdmin')
    <div class="main p-3">
      <div class="text-center">
          <h1>
              Sidebar Bootstrap 5
          </h1>

          @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif

          <div class="tables px-4 py-3 shadow-sm rounded">
          <table class="table caption-top table-striped">
            <div class="d-flex justify-content-between mb-3">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">
                Tambah User
              </button>
              
            </div>
            <caption>List of users</caption>
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">NIM</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
                <th scope="col">Role</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $user->nim }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->text_password }}</td>
                <td>{{ $user->role }}</td>
                <td>
                  <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateUser-{{ $user->user_id }}"><i class="ri-pencil-line text-white"></i></button>
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUser-{{ $user->user_id }}"><i class="ri-delete-bin-6-line text-white"></i></button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('manage-user.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">NIM</label>
              <input type="text" class="form-control shadow-none" id="nim" name="nim" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Email</label>
              <input type="email" class="form-control shadow-none" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <input type="password" class="form-control shadow-none" name="password" id="exampleInputPassword1">
            </div>
            <div class="col-md-3 w-100">
              <label for="validationCustom04" class="form-label">Role</label>
              <select class="form-select" id="validationCustom04" name="role" required>
                <option value="Admin">Admin</option>
                <option value="BPH_UKM">BPH UKM</option>
                <option value="Mahasiswa">Mahasiswa</option>
              </select>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
        
      </div>
    </div>
  </div>
  {{-- update user --}}
  @foreach ($users as $user)
  <div class="modal fade" id="updateUser-{{ $user->user_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('manage-user.update', $user->user_id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Method untuk update -->
            <div class="mb-3">
              <label for="nim-{{ $user->user_id }}" class="form-label">NIM</label>
              <input type="text" class="form-control shadow-none" id="nim-{{ $user->user_id }}" name="nim" value="{{ $user->nim }}" required>
            </div>
            <div class="mb-3">
              <label for="email-{{ $user->user_id }}" class="form-label">Email</label>
              <input type="email" class="form-control shadow-none" id="email-{{ $user->user_id }}" name="email" value="{{ $user->email }}" required>
            </div>
            <div class="mb-3">
              <label for="password-{{ $user->user_id }}" class="form-label">Password (Isi jika ingin mengganti)</label>
              <input type="text" class="form-control shadow-none" id="password-{{ $user->user_id }}" name="text_password" value="{{ $user->text_password }}">
            </div>
            <div class="mb-3">
              <label for="role-{{ $user->user_id }}" class="form-label">Role</label>
              <select class="form-select" id="role-{{ $user->user_id }}" name="role" required>
                <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="BPH_UKM" {{ $user->role == 'BPH_UKM' ? 'selected' : '' }}>BPH UKM</option>
                <option value="Mahasiswa" {{ $user->role == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endforeach

  {{-- delete user --}}
  @foreach ($users as $user)
  <div class="modal fade" id="deleteUser-{{ $user->user_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('manage-user.destroy', $user->user_id) }}" method="POST">
            @csrf
            @method('DELETE')
            Apakah kamu yakin ingin mendelete user {{$user->email}}
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger">Hapus User</button>
            </div>
          </form>
        </div>
        
      </div>
    </div>
  </div>
  @endforeach
  


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
  crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/hamburger.js')}} "></script>
@endsection