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
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUkm">
                Tambah UKM
              </button>
              
            </div>
            <caption>List of UKMs</caption>
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">UKM lOGO</th>
                <th scope="col">Nama UKM</th>
                <th scope="col">Ketua</th>
                <th scope="col">Wakil</th>
                <th scope="col">Bendahara</th>
                <th scope="col">Sekertaris</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($ukms as $ukm)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $ukm->nim }}</td>
                <td>{{ $ukm->email }}</td>
                <td>{{ $ukm->text_password }}</td>
                <td>
                  <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateUkm-{{ $ukm->ukm_id }}"><i class="ri-pencil-line text-white"></i></button>
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUkm-{{ $ukm->ukm_id }}"><i class="ri-delete-bin-6-line text-white"></i></button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addUkm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah UKM</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Logo UKM</label>
              <input type="file" class="form-control shadow-none" id="profile_photo" name="profile_photo" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Nama UKM</label>
              <input type="text" class="form-control shadow-none" name="name_ukm" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>  
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Deskripsi UKM</label>
              <textarea type="text" class="form-control shadow-none" name="description" id="exampleInputEmail1" aria-describedby="emailHelp"></textarea>
            </div>  
            <div class="col-md-3 w-100">
              <label for="validationCustom04" class="form-label">Pilih Ketua</label>
              <select class="form-select" id="validationCustom04" name="role_ketua">
                @foreach ($bph_ukm_users as $user)
                  <option value="{{ $user->user_id }}">{{ $user->email }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 w-100">
              <label for="validationCustom04" class="form-label">Pilih Wakil</label>
              <select class="form-select" id="validationCustom04" name="role_wakil" required>
                @foreach ($bph_ukm_users as $user)
                  <option value="{{ $user->user_id }}">{{ $user->email }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 w-100">
              <label for="validationCustom04" class="form-label">Pilih Bendahara</label>
              <select class="form-select" id="validationCustom04" name="role_bendahara" required>
                @foreach ($bph_ukm_users as $user)
                  <option value="{{ $user->user_id }}">{{ $user->email }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 w-100">
              <label for="validationCustom04" class="form-label">Pilih Sekertaris</label>
              <select class="form-select" id="validationCustom04" name="role_sekertaris" required>
                @foreach ($bph_ukm_users as $user)
                  <option value="{{ $user->user_id }}">{{ $user->email }}</option>
                @endforeach
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
  {{-- update ukm --}}
  @foreach ($ukms as $ukm)
  <div class="modal fade" id="updateUkm-{{ $ukm->ukm_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah UKM</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST">
            @csrf
            @method('PUT') <!-- Method untuk update -->
            <div class="mb-3">
              <label for="nim-{{ $ukm->ukm_id }}" class="form-label">NIM</label>
              <input type="text" class="form-control shadow-none" id="nim-{{ $ukm->ukm_id }}" name="nim" value="{{ $ukm->nim }}" required>
            </div>
            <div class="mb-3">
              <label for="email-{{ $ukm->ukm_id }}" class="form-label">Email</label>
              <input type="email" class="form-control shadow-none" id="email-{{ $ukm->ukm_id }}" name="email" value="{{ $ukm->email }}" required>
            </div>
            <div class="mb-3">
              <label for="password-{{ $ukm->ukm_id }}" class="form-label">Password (Isi jika ingin mengganti)</label>
              <input type="text" class="form-control shadow-none" id="password-{{ $ukm->ukm_id }}" name="text_password" value="{{ $ukm->text_password }}">
            </div>
            <div class="mb-3">
              <label for="role-{{ $ukm->ukm_id }}" class="form-label">Role</label>
              <select class="form-select" id="role-{{ $ukm->ukm_id }}" name="role" required>
                <option value="Admin" {{ $ukm->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="BPH_UKM" {{ $ukm->role == 'BPH_UKM' ? 'selected' : '' }}>BPH UKM</option>
                <option value="Mahasiswa" {{ $ukm->role == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
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

  {{-- delete ukm --}}
  @foreach ($ukms as $ukm)
  <div class="modal fade" id="deleteUkm-{{ $ukm->ukm_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus UKM</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST">
            @csrf
            @method('DELETE')
            Apakah kamu yakin ingin mendelete Ukm {{$ukm->name_ukm}}
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger">Hapus UKM</button>
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