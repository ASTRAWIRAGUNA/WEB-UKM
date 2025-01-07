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
    @include('partials.sideBarBPH')
    <div class="main p-3">
      <div class="header d-flex justify-content-between align-items-center mb-4" >
        <div class="fw-semibold fs-3">Manage Anggota</div>
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
              <button type="button" class="custom-btn btn-excel" data-bs-toggle="modal" data-bs-target="#importMember">
                <i class="fa-solid fa-file-export"></i>Export Anggota
              </button>
              <button type="button" class="custom-btn btn-tambah" data-bs-toggle="modal" data-bs-target="#addMember">
                <i class="fa-solid fa-plus"></i>Tambah Anggota
              </button>
            </div>

            <table class="table caption-top table-bordered" style="border-radius: 10px;">
            <thead>
              <tr class="bg-gray-200 border">
                <th>No</th>
                <th>Email</th>
                <th>NIM</th>
                <th>Presentase</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($members as $member)
              <tr class="border">
                <th class="align-middle">{{ $loop->iteration }}</th>
                <td class="align-middle">{{ $member->email }}</td>
                <td class="align-middle">{{ $member->nim }}</td>
                <td class="align-middle">nue</td>
                <td class="align-middle">nue</td>
                <td class="align-middle">
                  <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#updateMember-{{ $member->user_id }}"><i class="fa-regular fa-pen-to-square text-warning"></i></button>
                  <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#deleteMember-{{ $member->user_id }}"><i class="fa-regular fa-trash-can text-danger"></i></i></button>
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

  <div class="modal fade" id="addMember" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Anggota</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('manage-anggota.store') }}" method="POST">
            @csrf
            <div class="col-md-3 w-100">
              <label for="validationCustom04" class="form-label">Email User</label>
              <select class="form-select" id="validationCustom04" name="email" required>
                @foreach ($users as $user)
                <option value="{{ $user->email }}">{{ $user->email }}</option>    
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
  {{-- update user --}}
  @foreach ($members as $member)
  <div class="modal fade" id="updateMember-{{ $member ->user_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Anggota</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('manage-anggota.update', $member->user_id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Method untuk update -->
            <div class="mb-3">
              <label for="member-{{ $member->user_id }}" class="form-label">Ubah Anggota</label>
              <select class="form-select" id="member-{{ $member->user_id }}" name="email" required>
                @foreach ($users as $user)
                  <option value="{{ $user->email }}">{{ $user->email }}</option>
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

  {{-- delete user --}}
  @foreach ($members as $member)
  <div class="modal fade" id="deleteMember-{{ $member->user_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('manage-anggota.destroy', $member->user_id) }}" method="POST">
            @csrf
            @method('DELETE')
            Apakah kamu yakin ingin mengeluarkan user ini {{$member->email}}
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