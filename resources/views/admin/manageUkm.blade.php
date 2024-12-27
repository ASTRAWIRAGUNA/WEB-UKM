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
                <th scope="col">UKM Logo</th>
                <th scope="col">Nama UKM</th>
                <th scope="col">Deskripsi UKM</th>
                <th scope="col">Email UKM</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($ukms as $ukm)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td class="w-25 mx-auto"><img src="{{ asset('storage/profile_photo_ukm/'. $ukm->profile_photo_ukm )}}" alt="" style="width: 20%"></td>
                <td class="mx-auto">{{ $ukm->name_ukm }}</td>
                <td class="mx-auto">{{ $ukm->description }}</td>
                <td>{{ $ukm->bph->email ?? 'Tidak ada BPH' }}</td> <!-- Menampilkan email BPH -->


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
          <form action="{{ route('manage-ukm.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Logo UKM</label>
              <input type="file" class="form-control shadow-none" id="profile_photo_ukm" name="profile_photo_ukm" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Nama UKM</label>
              <input type="text" class="form-control shadow-none" name="name_ukm" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>  
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Deskripsi UKM</label>
              <textarea type="text" class="form-control shadow-none" name="description" id="exampleInputEmail1" aria-describedby="emailHelp"></textarea>
            </div>  
            <div class="mb-3">
              <label for="bph_id" class="form-label">BPH UKM</label>
              <select class="form-select" id="bph_id" name="bph_id" required>
                  @foreach($bph_ukm_users as $user)
                      @if($user->role == 'BPH_UKM') <!-- Pastikan hanya memilih user dengan role BPH -->
                          <option value="{{ $user->user_id }}">{{ $user->email }}</option> <!-- Menampilkan email BPH -->
                      @endif
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
          <form method="POST" action="{{ route('manage-ukm.update', $ukm->ukm_id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="profile_photo_ukm-{{ $ukm->ukm_id }}" class="form-label">Logo UKM</label>
              <img src="{{ asset('storage/profile_photo_ukm/' . $ukm->profile_photo_ukm) }}" alt="Logo UKM" class="img-thumbnail mb-2" style="max-width: 100px;">
              <input type="file" class="form-control shadow-none" id="profile_photo_ukm-{{ $ukm->ukm_id }}" name="profile_photo_ukm">
            </div>
            <div class="mb-3">
              <label for="name_ukm-{{ $ukm->ukm_id }}" class="form-label">Nama UKM</label>
              <input type="text" class="form-control shadow-none" id="name_ukm-{{ $ukm->ukm_id }}" name="name_ukm" value="{{ $ukm->name_ukm }}" required>
            </div>
            <div class="mb-3">
              <label for="description-{{ $ukm->ukm_id }}" class="form-label">Deskripsi UKM</label>
              <textarea class="form-control shadow-none" name="description" id="description-{{ $ukm->ukm_id }}" required>{{ $ukm->description }}</textarea>
            </div>
            <div class="mb-3">
              <label for="bph_id-{{ $ukm->ukm_id }}" class="form-label">BPH UKM</label>
              <select class="form-select shadow-none" id="bph_id-{{ $ukm->ukm_id }}" name="bph_id" required>
                @foreach ($bph_ukm_users as $user)
                  <option value="{{ $user->user_id }}" {{ $ukm->bph_id == $user->user_id ? 'selected' : '' }}>
                    {{ $user->email }}
                  </option>
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
          <form method="POST" action="{{ route('manage-ukm.destroy', $ukm->ukm_id) }}">
            @csrf
            @method('DELETE')
            Apakah kamu yakin ingin menghapus UKM <strong>{{ $ukm->name_ukm }}</strong>?
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