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
      <div class="header d-flex justify-content-between align-items-center mb-4" >
        <div class="fw-semibold fs-3">Manage Laporan Kegiatan</div>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
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
            <table class="table caption-top table-bordered" style="border-radius: 10px;">
            <thead>
              <tr class="bg-gray-200 border">
                <th>No</th>
                <th>Nama UKM</th>
                <th>Foto Laporan</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {{-- @foreach ($activitys as $user)
              <tr class="border">
                <th class="align-middle">{{ $loop->iteration }}</th>
                <td class="align-middle">{{ $user->nim }}</td>
                <td class="align-middle">{{ $user->email }}</td>
                <td class="align-middle">{{ $user->text_password }}</td>
                <td class="align-middle">{{ $user->role }}</td>
                <td class="align-middle">
                  <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#updateUser-{{ $user->user_id }}"><i class="fa-regular fa-pen-to-square text-warning"></i></button>
                  <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#deleteUser-{{ $user->user_id }}"><i class="fa-regular fa-trash-can text-danger"></i></i></button>
                </td>
              </tr>
              @endforeach --}}
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
  crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/hamburger.js')}} "></script>
@endsection