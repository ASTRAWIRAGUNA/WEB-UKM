@extends('base')

@section('head')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('assets/css/styleAdmin.css') }}">
<style>
    .custom-btn {
      border-radius: 8px;
      padding: 8px 15px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      font-weight: 700;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-excel {
      background-color: #b2fce4;
      color: #00d084;
      border: none;
    }

    .btn-excel:hover {
      background-color: #99e6ce;
    }

    .btn-tambah {
      background-color: #d8dcfc;
      color: #5a61e6;
      border: none;
    }

    .btn-tambah:hover {
      background-color: #c0c6f5;
    }

    .custom-icon {
      font-size: 20px;
    }

    .d-flex {
      display: flex;
    }

    .gap-3 {
      gap: 12px;
    }
  </style>
@endsection

@section('body')
  <div class="wrapper">
    @include('partials.sideBarAdmin')
    <div class="main p-3">
      <div class="header d-flex justify-content-between align-items-center mb-4" >
        <div class="fw-bold fs-3">Manage UKM</div>
        <div class="user profile d-flex align-items-center">Hi, achmadsymsl87@gmail.com</div>
      </div>
      <div class="text-center bg-light">
          @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
          <div class="tables px-4 py-3 shadow-sm bg-light" style="border-radius: 10px;">
            <div class="d-flex justify-content-between align-items-center">
              <div class="fw-medium fs-4">Data</div>
              <div 
                class="input-group" 
                style="max-width: 150px; border: 2px solid #D3CFCF; border-radius: 8px; overflow: hidden;"
              >
                <span class="input-group-text bg-white border-0 pl-3">
                  <i class="fa-solid fa-magnifying-glass text-muted"></i>
                </span>
                <input 
                  type="text" 
                  class="form-control shadow-none border-0 pr-4 py-2 fw-semibold" 
                  placeholder="Search"
                  style="font-size: 12px;"
                >
              </div>
            </div> 
            <hr>
            <div class="d-flex justify-content-end gap-3 mb-3">
              <button type="button" class="custom-btn btn-tambah" data-bs-toggle="modal" data-bs-target="#addUkm">
                <i class="fa-solid fa-plus"></i>Tambah UKM
              </button>
            </div>
            
            <table class="table caption-top table-bordered" style="border-radius: 10px;">
              <thead>
              <tr class="bg-gray-200 border">
                <th>No</th>
                <th>UKM Logo</th>
                <th>Nama UKM</th>
                <th>Deskripsi UKM</th>
                <th>Email UKM</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($ukms as $ukm)
              <tr class="border">
                <th class="align-middle">{{ $loop->iteration }}</th>
                <td class="w-25 align-middle"><img src="{{ asset('storage/profile_photo_ukm/'. $ukm->profile_photo_ukm )}}" alt="" style="width: 20%"></td>
                <td class="align-middle">{{ $ukm->name_ukm }}</td>
                <td class="align-middle">{{ $ukm->description }}</td>
                <td class="align-middle">{{ $ukm->bph->email ?? 'Tidak ada BPH' }}</td> <!-- Menampilkan email BPH -->

                <td class="align-middle">
                  <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#updateUkm-{{ $ukm->ukm_id }}"><i class="fa-regular fa-pen-to-square text-warning"></i></button>
                  <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#deleteUkm-{{ $ukm->ukm_id }}"><i class="fa-regular fa-trash-can text-danger"></i></i></button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="pagination d-flex justify-content-end align-items-center">
            <nav aria-label="Page navigation example">
              <ul class="pagination">
                <li class="page-item">
                  <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
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