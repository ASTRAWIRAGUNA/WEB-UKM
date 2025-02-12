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
      <div class="fw-semibold fs-3">Manage Kas</div>
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
            <form action="{{ route('manage-kas-ukm.index') }}" method="GET" class="d-flex gap-2">
              <input type="text" name="search" class="form-control shadow-none border-0 pr-4 py-2 fw-semibold"
                placeholder="Search" style="font-size: 12px;" value="{{ request('search') }}">
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </form>
          </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between gap-3 mb-3 align-items-center">
          <div class="left-side">
            <div class="setCash d-flex align-items-center gap-2">
              <div class="text">Kas:
                <span id="setKasValue">{{ $amountCash }}</span>
                <input type="number" id="setKasInput" class="form-control d-none" value="{{ $amountCash }}"
                  style="width: 80px;">
              </div>
              <button id="toggleSetKasBtn" class="btn btn-primary">
                <i class="fa-solid fa-pen"></i> Edit
              </button>
            </div>
            <div class="amount-side fw-semibold">Total Kas Rp.3000.000</div>
          </div>
          <div class="button-side">
            <button type="button" class="custom-btn btn-excel" data-bs-toggle="modal" data-bs-target="#importUser">
              <i class="fa-solid fa-file-export"></i>Export Kas
            </button>
            <button type="button" class="custom-btn btn-tambah" data-bs-toggle="modal" data-bs-target="#addUser">
              <i class="fa-solid fa-plus"></i>Tambah Kegiatan
            </button>
          </div>
        </div>

        <table class="table caption-top table-bordered" style="border-radius: 10px;">
          <thead>
            <tr class="bg-gray-200 border">
              <th>No</th>
              <th>Email</th>

              @foreach ($dateActivities as $date)
          <th>{{ $date->date}}</th>
        @endforeach

              <th>Jumlah</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($dateActivities as $activity)
        <tr class="border">
          <th class="align-middle">
          {{ $loop->iteration + ($dateActivities->currentPage() - 1) * $dateActivities->perPage() }}
          </th>
          <td class="align-middle">{{ $activity->user->email ?? 'Tidak ada email' }}</td>
          @foreach ($dateActivities as $date)
        <td class="align-middle">{{ $date->date }}</td>
      @endforeach
          <td class="align-middle">nue</td>
          <td class="align-middle">
          <button type="button" class="btn" data-bs-toggle="modal"
            data-bs-target="#updateActivity-{{ $activity->activities_id }}">
            <i class="fa-regular fa-pen-to-square text-warning"></i>
          </button>
          </td>
        </tr>
      @empty
    <tr>
      <td colspan="6" class="text-center">No data found</td>
    </tr>
  @endforelse
          </tbody>
        </table>
        <div class="d-flex justify-content-center">
          <nav aria-label="Page navigation">
            <ul class="pagination">
              {{ $dateActivities->appends(['search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-5') }}
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('manage-user.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">NIM</label>
            <input type="text" class="form-control shadow-none" id="nim" name="nim" aria-describedby="emailHelp">
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email</label>
            <input type="email" class="form-control shadow-none" name="email" id="exampleInputEmail1"
              aria-describedby="emailHelp">
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control shadow-none" name="password" id="exampleInputPassword1">
          </div>
          <div class="col-md-3 w-100">
            <label for="validationCustom04" class="form-label">Role</label>
            <select class="form-select" id="validationCustom04" name="role" required>
              <option value="Admin">Admin</option>
              <option value="BPH_UKM">BPH UKM</option>
              <option value="Mahasiswa">Mahasiswa</option>
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
{{-- @foreach ($users as $user)
<div class="modal fade" id="updateUser-{{ $user->user_id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('manage-user.update', $user->user_id) }}" method="POST">
          @csrf
          @method('PUT') <!-- Method untuk update -->
          <div class="mb-3">
            <label for="nim-{{ $user->user_id }}" class="form-label">NIM</label>
            <input type="text" class="form-control shadow-none" id="nim-{{ $user->user_id }}" name="nim"
              value="{{ $user->nim }}" required>
          </div>
          <div class="mb-3">
            <label for="email-{{ $user->user_id }}" class="form-label">Email</label>
            <input type="email" class="form-control shadow-none" id="email-{{ $user->user_id }}" name="email"
              value="{{ $user->email }}" required>
          </div>
          <div class="mb-3">
            <label for="password-{{ $user->user_id }}" class="form-label">Password (Isi jika ingin mengganti)</label>
            <input type="text" class="form-control shadow-none" id="password-{{ $user->user_id }}" name="text_password"
              value="{{ $user->text_password }}">
          </div>
          <div class="mb-3">
            <label for="role-{{ $user->user_id }}" class="form-label">Role</label>
            <select class="form-select" id="role-{{ $user->user_id }}" name="role" required>
              <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
              <option value="BPH_UKM" {{ $user->role == 'BPH_UKM' ? 'selected' : '' }}>BPH UKM</option>
              <option value="Mahasiswa" {{ $user->role == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
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
@endforeach --}}

{{-- delete user --}}
{{-- @foreach ($users as $user)
<div class="modal fade" id="deleteUser-{{ $user->user_id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('manage-user.destroy', $user->user_id) }}" method="POST">
          @csrf
          @method('DELETE')
          Apakah kamu yakin ingin mendelete user {{$user->email}}
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Hapus User</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endforeach --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    const toggleBtn = $('#toggleSetKasBtn');
    const setKasValue = $('#setKasValue');
    const setKasInput = $('#setKasInput');

    let isEditing = false;

    toggleBtn.on('click', function () {
      if (isEditing) {
        const newValue = setKasInput.val();

        $.ajax({
          url: '{{ route("setKas") }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            cash: newValue
          },
          success: function (response) {
            // Update nilai amountCash di tampilan
            setKasValue.text(response.amountCash);
            setKasValue.removeClass('d-none');

            setKasInput.addClass('d-none');
            toggleBtn.html('<i class="fa-solid fa-pen"></i> Edit');
            isEditing = false;
          },
          error: function (xhr, status, error) {
            console.log("Terjadi kesalahan: " + error);
            alert("Gagal memperbarui kas. Silakan coba lagi.");
          }
        });
      } else {
        setKasValue.addClass('d-none');
        setKasInput.removeClass('d-none').focus();

        toggleBtn.html('<i class="fa-solid fa-save"></i> Simpan');
        isEditing = true;
      }
    });
  });
</script>
<script src="{{ asset('assets/js/hamburger.js')}} "></script>
@endsection