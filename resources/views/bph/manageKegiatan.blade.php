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
    <div class="header d-flex justify-content-between align-items-center mb-4">
      <div class="fw-semibold fs-3">Manage Kegiatan</div>
      <div class="user profile d-flex align-items-center">Hi, {{ auth()->user()->email }}</div>
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
          <div class="input-group ms-auto" style="max-width: 150px;">
            <form action="{{ route('manage-kegiatan-ukm.index') }}" method="GET" class="d-flex gap-2">
              <input type="text" name="search" class="form-control shadow-none border-0 pr-4 py-2 fw-semibold"
                placeholder="Search" style="font-size: 12px;" value="{{ request('search') }}">
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </form>
          </div>
        </div>
        <hr>
        <div class="d-flex justify-content-end gap-3 mb-3">

          <button type="button" class="custom-btn btn-tambah" data-bs-toggle="modal" data-bs-target="#addKegiatan">
            <i class="fa-solid fa-plus"></i>Tambah Kegiatan
          </button>
        </div>

        <table class="table caption-top table-bordered" style="border-radius: 10px;">
          <thead>
            <tr class="bg-gray-200 border">
              <th>No</th>
              <th>Aktifitas</th>
              <th>date</th>
              <th style="width: 20%">Foto Aktifitas</th>
              <th>Pesan</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($kegiatans as $kegiatan)
        <tr class="border">
          <th class="align-middle">{{ $loop->iteration + ($kegiatans->currentPage() - 1) * $kegiatans->perPage() }}
          </th>
          <td class="align-middle">{{ $kegiatan->name_activity }}</td>
          <td class="align-middle">{{ $kegiatan->date }}</td>
          <td class="align-middle">
          <img src="{{ asset('storage/proof_photo/' . $kegiatan->proof_photo) }}" alt="" style="width: 60%"
            class="ratio ratio-16x9">
          </td>
          <td class="align-middle">{{ $kegiatan->message }}</td>
          <td class="align-middle">
          @if ($kegiatan->status_activity == 'Pending')
        <span class="badge rounded-pill text-bg-secondary">Pending</span>
      @elseif ($kegiatan->status_activity == 'Diterima')
      <span class="badge rounded-pill text-bg-success">Diterima</span>
    @else
      <span class="badge rounded-pill text-bg-danger">Ditolak</span>
    @endif
          </td>
          <td class="align-middle">
          <button type="button" class="btn" data-bs-toggle="modal"
            data-bs-target="#qrcodeKegiatan-{{ $kegiatan->activities_id }}">
            <i class="fa-solid fa-qrcode text-success"></i>
          </button>
          <button type="button" class="btn" data-bs-toggle="modal"
            data-bs-target="#updateKegiatan-{{ $kegiatan->activities_id }}">
            <i class="fa-regular fa-pen-to-square text-warning"></i>
          </button>
          <button type="button" class="btn" data-bs-toggle="modal"
            data-bs-target="#deleteKegiatan-{{ $kegiatan->activities_id }}">
            <i class="fa-regular fa-trash-can text-danger"></i>
          </button>
          </td>
        </tr>
      @empty
    <tr>
      <td colspan="7" class="text-center">No data found</td>
    </tr>
  @endforelse
          </tbody>
        </table>
        <div class="d-flex justify-content-center">
          <nav aria-label="Page navigation">
            <ul class="pagination">
              {{ $kegiatans->appends(['search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-5') }}
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addKegiatan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kegiatan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('manage-kegiatan-ukm.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">Aktifitas</label>
            <input type="text" class="form-control shadow-none" name="name_activity" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" class="form-control shadow-none" name="date" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Foto Aktifitas</label>
            <input type="file" class="form-control shadow-none" name="proof_photo">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- update kegiatan --}}
@foreach ($kegiatans as $kegiatan)
  <div class="modal fade" id="updateKegiatan-{{ $kegiatan->activities_id }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Kegiatan</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('manage-kegiatan-ukm.update', $kegiatan->activities_id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
        <label class="form-label">Aktifitas</label>
        <input type="text" class="form-control shadow-none" name="name_activity"
          value="{{ $kegiatan->name_activity }}" required>
        </div>
        <div class="mb-3">
        <label class="form-label">Date</label>
        <input type="date" class="form-control shadow-none" name="date" value="{{ $kegiatan->date }}" required>
        </div>
        <div class="mb-3">
        <label class="form-label">Foto Aktifitas</label>
        <img src="{{ asset('storage/proof_photo/' . $kegiatan->proof_photo) }}" alt="" srcset="" style="width: 20%">
        <input type="file" class="form-control shadow-none" name="proof_photo">
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


{{-- delete kegiatan --}}
@foreach ($kegiatans as $kegiatan)
  <div class="modal fade" id="deleteKegiatan-{{ $kegiatan->activities_id }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Kegiatan</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <p>Apakah Anda yakin ingin menghapus kegiatan ini?</p>
      <form action="{{ route('manage-kegiatan-ukm.destroy', $kegiatan->activities_id) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
      </div>
    </div>
    </div>
  </div>
@endforeach

@foreach ($kegiatans as $kegiatan)
  <div class="modal fade" id="qrcodeKegiatan-{{ $kegiatan->activities_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="modal-title fs-5">Qr Code Absen</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
      <div class="qr-code">
        @if($kegiatan->qr_code)

      <img src="{{ asset('storage/' . $kegiatan->qr_code) }}" alt="QR Code" class="img-fluid" style="width: 100%">
    @else
    <p>No QR code available</p>
  @endif
      </div>
      </div>
    </div>
    </div>
  </div>
@endforeach


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/hamburger.js')}} "></script>
@endsection