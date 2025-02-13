@extends('base')

@section('head')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('assets/css/styleAdmin.css') }}">
  <style>
    .card-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    color: white;
    border-radius: 8px;
    }

    .card-purple {
    background-color: #6f42c1;
    }

    .card-blue {
    background-color: #0d6efd;
    }

    .card-green {
    background-color: #198754;
    }

    .icon-box {
    font-size: 24px;
    }

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

    .btn-edit {
    background-color: #FFE2AD;
    color: #F3AC2B;
    border: none;
    }

    .btn-edit:hover {
    background-color: #FFE2AD;
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
    @include('partials.sideBarBph')
    <div class="main p-3">
    <div class="header d-flex justify-content-between align-items-center mb-2">
      <div class="fw-semibold fs-3">Dashboard</div>
      <div class="user profile d-flex align-items-center">Hi, {{ auth()->user()->email }}</div>
    </div>
    <div class="row">
      <!-- Card 1 -->
      <div class="col-md-4 mb-3">
      <div class="card-box card-purple">
        <div>
        <h3 class="mb-0">{{ $c_anggota }}</h3>
        <p class="mb-0">Total Anggota</p>
        </div>
        <div class="icon-box">
        <i class="fa-solid fa-user-group"></i>
        </div>
      </div>
      </div>
      <!-- Card 2 -->
      <div class="col-md-4 mb-3">
      <div class="card-box card-blue">
        <div>
        <h3 class="mb-0">{{ $c_aktivitas }}</h3>
        <p class="mb-0">Total Aktivitas</p>
        </div>
        <div class="icon-box">
        <i class="fa-solid fa-chart-line"></i>
        </div>
      </div>
      </div>
      <!-- Card 3 -->
      <div class="col-md-4 mb-3">
      <div class="card-box card-green">
        <div>
        <h3 class="mb-0">Rp. 3.000.000</h3>
        <p class="mb-0">Total Kas</p>
        </div>
        <div class="icon-box">
        <i class="fa-solid fa-wallet"></i>
        </div>
      </div>
      </div>
    </div>
    <div class="header-ukm-display fw-bold mt-1 mb-2 fs-5">Profile UKM</div>
    <div id="ukmDisplay">
      <div class="d-flex align-items-start">

      <div class="flex-shrink-0">
        <img src="{{ asset('storage/profile_photo_ukm/' . $ukm->profile_photo_ukm) }}" alt="Profile Photo"
        class="bg-light rounded" style="width: 200px; height: 200px;">
      </div>
      <div class="flex-grow-1 ms-3">
        <h2 class="fw-bold" id="nameDisplay">{{ $ukm->name_ukm }}</h2>
        <p id="descriptionDisplay">{{ $ukm->description }}</p>
      </div>
      <button class="custom-btn btn-edit" onclick="toggleEditForm('ukm')">
        <i class="fa-regular fa-pen-to-square"></i>
      </button>
      </div>
    </div>

    <!-- Form Edit -->
    <form id="ukmForm" class="d-none mt-4" method="POST" action="{{ route('update.profile', $ukm->ukm_id) }}"
      enctype="multipart/form-data">
      <form id="ukmForm" class="d-none mt-4" method="POST" action="{{ route('dashboard-ukm.update', $ukm->ukm_id) }}"
      enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="d-flex align-items-start">
        <!-- Bagian Gambar -->
        <div class="align-items-start">
        <img src="{{ asset('storage/profile_photo_ukm/' . $ukm->profile_photo_ukm) }}" alt="Profile Photo"
          class="bg-light rounded" style="width: 200px; height: 200px;">
        <input type="file" name="profile_photo_ukm" class="form-control mt-2" style="width: 200px;">
        </div>
        <!-- Bagian Input -->
        <div class="flex-grow-1 ms-3 d-flex flex-column">
        <div class="mb-1">
          <label class="form-label">Nama UKM</label>
          <input type="text" name="name_ukm" class="form-control" value="{{ $ukm->name_ukm }}" required>
        </div>
        <div class="mb-1">
          <label class="form-label">Deskripsi</label>
          <textarea name="description" rows="4" class="form-control" required>{{ $ukm->description }}</textarea>
        </div>
        <!-- Bagian Tombol -->
        <div class="mt-auto d-flex justify-content-end">
          <button type="submit" class="btn btn-primary me-2">Save</button>
          <button type="button" onclick="toggleEditForm('ukm')" class="btn btn-secondary">Cancel</button>
        </div>
        </div>
      </div>
      </form>

    </div>
  </div>
  <script>
    function toggleEditForm(section) {
    const displayDiv = document.getElementById(section + 'Display');
    const form = document.getElementById(section + 'Form');

    // Toggle visibility of form and display div
    if (form.classList.contains('d-none')) {
      form.classList.remove('d-none');  // Show the form
      displayDiv.classList.add('d-none');  // Hide the display content
    } else {
      form.classList.add('d-none');  // Hide the form
      displayDiv.classList.remove('d-none');  // Show the display content
    }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="{{ asset('assets/js/hamburger.js')}} "></script>
@endsection