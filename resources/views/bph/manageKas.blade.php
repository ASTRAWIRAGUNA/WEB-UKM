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
          <input type="number" id="setKasInput" class="form-control d-none shadow-none" value="{{ $amountCash }}"
            style="width: 50px;">
          </div>
          <button id="toggleSetKasBtn" class="btn btn-warning shadow-none">
          <i class="fa-solid fa-pen text-white"></i>
          </button>
        </div>
        <div class="amount-side fw-semibold">
          Total Kas Rp. {{ number_format($totalKas, 0, ',', '.') }}
        </div>

        </div>
        <div class="button-side">
        <a href="{{ route('eksport-kas') }}" class="custom-btn btn-excel shadow-none">
          <i class="fa-solid fa-file-export"></i>Export Kas
        </a>
        </div>
      </div>

      <table class="table caption-top table-bordered" style="border-radius: 10px;">
        <thead>
        <tr class="bg-gray-200 border">
          <th>No</th>
          <th>Email</th>
          @foreach ($dateActivities as $date)
        <th>{{ $date->date }}</th>
      @endforeach
          <th>Jumlah</th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($members as $member)
        <tr class="border">
          <th class="align-middle">{{ $loop->iteration }}</th>
          <td class="align-middle">{{ $member->email }}</td>
          @foreach ($dateActivities as $date)
        <td class="align-middle text-center">
        @if ($member->kas->where('activities_id', $date->activities_id)->where('is_payment', true)->isNotEmpty())
      <i class="fa-solid fa-check text-success"></i> <!-- Checklist -->
    @else
    <i class="fa-solid fa-times text-danger"></i> <!-- Cross -->
  @endif
        </td>
      @endforeach
          <td class="align-middle">
          @php
        // Menjumlahkan semua 'amount' untuk user ini yang sudah dibayarkan
        $totalAmount = $member->kas->where('is_payment', true)->sum('amount');
      @endphp
          Rp. {{ number_format($totalAmount, 0, ',', '.') }}
          </td>
          <td class="align-middle">
          <button type="button" class="btn shadow-none" data-bs-toggle="modal"
          data-bs-target="#payKasModal-{{ $member->user_id }}">
          <i class="fa-solid fa-money-bill-wave text-success"></i>
          </button>
          </td>
        </tr>
    @empty
    <tr>
      <td colspan="5" class="text-center">No data found</td>
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

  {{-- form payment kas --}}
  @foreach ($members as $member)
    <div class="modal fade" id="payKasModal-{{ $member->user_id }}" tabindex="-1" aria-labelledby="payKasModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="modal-title fs-5" id="payKasModalLabel">Bayar Kas</h1>
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('pay-kas', $member->user_id) }}" method="POST">
      @csrf
      <div class="mb-3">
      <label for="date" class="form-label">Tanggal</label>
      <select class="form-select" id="date" name="date" required>
        @foreach ($dateActivities as $activity)
      <option value="{{ $activity->date }}">{{ $activity->date }}</option>
    @endforeach
      </select>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary shadow-none" data-bs-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary shadow-none">Bayar</button>
      </div>
      </form>
      </div>
    </div>
    </div>
    </div>
  @endforeach

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

      console.log("Mengirim request dengan nilai: ", newValue);

      $.ajax({
        url: '{{ route("setKas") }}',
        type: 'POST',
        data: {
        _token: '{{ csrf_token() }}',
        cash: newValue
        },
        success: function (response) {
        console.log("Response dari server: ", response);
        setKasValue.text(response.cash);
        setKasValue.removeClass('d-none');
        setKasInput.addClass('d-none');
        toggleBtn.html('<i class="fa-solid fa-pen text-white"></i>');
        isEditing = false;
        },
        error: function (xhr, status, error) {
        console.log("Terjadi kesalahan: ", xhr.responseText);
        alert("Gagal memperbarui kas. Silakan coba lagi.");
        }
      });
      } else {
      setKasValue.addClass('d-none');
      setKasInput.removeClass('d-none').focus();
      toggleBtn.html('<i class="fa-solid fa-save text-white"></i>');
      isEditing = true;
      }
    });

    });
  </script>
  <script src="{{ asset('assets/js/hamburger.js')}} "></script>
@endsection