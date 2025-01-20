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
</style>
@endsection

@section('body')
<div class="wrapper">
  @include('partials.sideBarAdmin')

  <div class="main p-3">
    <div class="header d-flex justify-content-between align-items-center mb-2">
      <div class="fw-semibold fs-3">Dashboard</div>
      <div class="user profile d-flex align-items-center">Hi, {{ auth()->user()->email }}</div>
    </div>
    <div class="mb-3 d-flex justify-content-end">
      <!-- Form untuk toggle status semua UKM -->
      <form action="{{ route('dashboard-admin.toggleAllRegistrationStatus') }}" method="POST" id="toggleForm">
        @csrf
        <div class="form-check form-switch">
          <!-- Menambahkan kondisi untuk status checkbox -->
          <label class="form-check-label" for="flexSwitchCheckDefault">Pendaftaran Anggota UKM</label>
          <input class="form-check-input shadow-none" type="checkbox" id="flexSwitchCheckDefault" name="status"
            value="on" @if($isActive) checked @endif>

        </div>
      </form>
    </div>
    <div class="row">

      <!-- Card 1 -->
      <div class="col-md-4 mb-3">
        <div class="card-box card-purple">
          <div>
            <h3 class="mb-0">{{$c_users}}</h3>
            <p class="mb-0">Total User</p>
          </div>
          <div class="icon-box">
            <i class="fa-solid fa-folder"></i>
          </div>
        </div>
      </div>
      <!-- Card 2 -->
      <div class="col-md-4 mb-3">
        <div class="card-box card-blue">
          <div>
            <h3 class="mb-0">{{ $c_ukms }}</h3>
            <p class="mb-0">Total UKM</p>
          </div>
          <div class="icon-box">
            <i class="fa-solid fa-folder"></i>
          </div>
        </div>
      </div>
      <!-- Card 3 -->
      <div class="col-md-4 mb-3">
        <div class="card-box card-green">
          <div>
            <h3 class="mb-0">{{ $c_mhs }}</h3>
            <p class="mb-0">Total Mahasiswa</p>
          </div>
          <div class="icon-box">
            <i class="fa-solid fa-folder"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- Card 4 -->
      <div class="col-md-4 mb-3">
        <div class="card-box card-purple">
          <div>
            <h3 class="mb-0">{{ $c_activities }}</h3>
            <p class="mb-0">Total Activity</p>
          </div>
          <div class="icon-box">
            <i class="fa-solid fa-folder"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('assets/js/hamburger.js')}} "></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $('#flexSwitchCheckDefault').on('change', function () {
      var status = $(this).is(':checked') ? 'on' : 'off';  // Determine the status based on the switch position
      console.log("Switch status changed to: " + status);  // Log the status change

      // Send an AJAX request to update the status
      $.ajax({
        url: '{{ route("dashboard-admin.toggleAllRegistrationStatus") }}',  // URL to send the request to
        type: 'POST',  // HTTP method to use for the request
        data: {
          _token: '{{ csrf_token() }}',  // CSRF token for security
          status: status  // Status to be sent to the server
        },
        success: function (response) {
          console.log("Server response: ", response);  // Log the server response
          alert(response.message);  // Show a success message

          // Update the switch status in the UI based on the server response
          if (response.status === 'on') {
            $('#flexSwitchCheckDefault').prop('checked', true);
          } else if (response.status === 'off') {
            $('#flexSwitchCheckDefault').prop('checked', false);
          } else {
            console.log("Unexpected response status: " + response.status);
          }
        },
        error: function (xhr, status, error) {
          console.log("Terjadi kesalahan: " + error);  // Log the error
          alert("Gagal mengubah status. Silakan coba lagi.");  // Show an error message
          // Revert the switch status if an error occurs
          $('#flexSwitchCheckDefault').prop('checked', !$('#flexSwitchCheckDefault').is(':checked'));
        }
      });
    });
  });
</script>
@endsection