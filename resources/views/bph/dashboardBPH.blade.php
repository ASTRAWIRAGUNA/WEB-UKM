@extends('base')

@section('head')
<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('assets/css/styleAdmin.css') }}">
@endsection

@section('body')
  <div class="wrapper">
    @include('partials.sideBarBph')
    <div class="main p-3">


      <div id="ukmDisplay">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              
                <img src="{{ asset('storage/profile_photo_ukm/' . $ukm->profile_photo_ukm) }}" 
                     alt="Profile Photo" 
                     class="bg-light rounded" 
                     style="width: 100px; height: 100px;">
            </div>
            <div class="flex-grow-1 ms-3">
                <h2 class="fw-bold" id="nameDisplay">{{ $ukm->name_ukm }}</h2>
                <p id="descriptionDisplay">{{ $ukm->description }}</p>
            </div>
            <button class="btn btn-warning" onclick="toggleEditForm('ukm')">Edit</button>
        </div>
      </div>

      <!-- Form Edit -->
      {{-- <form id="ukmForm" class="d-none mt-4" method="POST" action="{{ route('update.profile', $ukm->ukm_id) }}" enctype="multipart/form-data"> --}}
        <form id="ukmForm" class="d-none mt-4" method="POST" action="{{ route('dashboard-ukm.update', $ukm->ukm_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="form-label">Image</label>
            <img src="{{ asset('storage/profile_photo_ukm/' . $ukm->profile_photo_ukm) }}" 
            alt="Profile Photo" 
            class="bg-light rounded" 
            style="width: 100px; height: 100px;">
            <input type="file" name="profile_photo_ukm" class="form-control">
        </div>
        <div class="mb-4">
            <label class="form-label">Nama UKM</label>
            <input type="text" name="name_ukm" class="form-control" value="{{ $ukm->name_ukm }}" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="4" class="form-control" required>{{ $ukm->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" onclick="toggleEditForm('ukm')" class="btn btn-secondary">Cancel</button>
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
  integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
  crossorigin="anonymous"></script>
  <script src="{{ asset('assets/js/hamburger.js')}} "></script>
@endsection