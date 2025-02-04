@extends('base')

@section('head')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('assets/css/styleAdmin.css') }}">
@endsection

@section('body')
<div class="wrapper">
  @include('partials.sideBarAdmin')
  <div class="main p-3">
    <div class="header d-flex justify-content-between align-items-center mb-4">
      <div class="fw-semibold fs-3">Manage Laporan Kegiatan</div>
      {{-- <div class="user profile d-flex align-items-center">Hi, {{ auth()->user()->email }}</div> --}}
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
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="fw-medium fs-4">Data</div>
          <div class="input-group"
            style="max-width: 150px; border: 2px solid #D3CFCF; border-radius: 8px; overflow: hidden;">
            <span class="input-group-text bg-white border-0 pl-3">
              <i class="fa-solid fa-magnifying-glass text-muted"></i>
            </span>
            <input type="text" class="form-control shadow-none border-0 pr-4 py-2 fw-semibold" placeholder="Search"
              style="font-size: 12px;">
          </div>
        </div>
        <table class="table caption-top table-bordered" style="border-radius: 10px;">
          <thead>
            <tr class="bg-gray-200 border">
              <th>No</th>
              <th>UKM</th>
              <th>Kegiatan</th>
              <th>Foto Laporan</th>
              <th>Date</th>
              <th>Pesan</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($laporan_kegiatans as $kegiatan)
        <tr class="border">
          <th class="align-middle">{{ $loop->iteration }}</th>
          <td class="align-middle">{{ $kegiatan->ukm->name_ukm }}</td>
          <td class="align-middle">{{ $kegiatan->name_activity }}</td>
          <td class="align-middle">
          <img class="ratio ratio-16x9" src="{{ asset('storage/proof_photo/' . $kegiatan->proof_photo) }}" alt=""
            data-bs-toggle="modal" data-bs-target="#viewImageModal-{{ $kegiatan->activities_id }}"
            style="width: 20%; cursor: pointer;">
          </td>
          <td class="align-middle">{{ $kegiatan->date }}</td>
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
            data-bs-target="#updateKegiatan-{{ $kegiatan->activities_id }}"><i
            class="fa-regular fa-pen-to-square text-warning"></i></button>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No data found</td>
        </tr>
      @endforelse
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

@foreach ($laporan_kegiatans as $kegiatan)
  <div class="modal fade" id="updateKegiatan-{{ $kegiatan->activities_id }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Kegiatan</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('manage-laporan-ukm.update', $kegiatan->activities_id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
        <label class="form-label">Aktifitas</label>
        <select name="status_activity" class="form-select shadow-none" onchange="this.form.submit()">
          <option class="shadow-none" value="Pending" {{ $kegiatan->status_activity === 'Pending' ? 'selected' : '' }}>Pending</option>
          <option class="shadow-none" value="Diterima" {{ $kegiatan->status_activity === 'Diterima' ? 'selected' : '' }}>Diterima</option>
          <option class="shadow-none" value="Ditolak" {{ $kegiatan->status_activity === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
        </div>
        <div class="mb-3">
        <label class="form-label">Pesan</label>
        <textarea class="form-control shadow-none" name="message" required>{{ $kegiatan->message }}</textarea>

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

@foreach ($laporan_kegiatans as $kegiatan)
  <div class="modal fade" id="viewImageModal-{{ $kegiatan->activities_id }}" tabindex="-1"
    aria-labelledby="viewImageModalLabel-{{ $kegiatan->activities_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="modal-title fs-5" id="viewImageModalLabel-{{ $kegiatan->activities_id }}">Foto Kegiatan</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
      <img class="img-fluid" src="{{ asset('storage/proof_photo/' . $kegiatan->proof_photo) }}" alt="Foto Kegiatan">
      </div>
    </div>
    </div>
  </div>
@endforeach


<script src="{{ asset('assets/js/hamburger.js')}} "></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
@endsection