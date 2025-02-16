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
      <div class="fw-semibold fs-3">Log Activity</div>
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
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="fw-medium fs-4">Data</div>
        <div class="input-group ms-auto" style="max-width: 150px;">
        <form action="{{ route('log-activity.index') }}" method="GET" class="d-flex gap-2">
          <input type="text" name="search" class="form-control shadow-none border-0 pr-4 py-2 fw-semibold"
          placeholder="Search" style="font-size: 12px;" value="{{ request('search') }}">
          <button type="submit" class="btn btn-primary shadow-none">
          <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
        </div>
      </div>
      <table class="table caption-top table-bordered" style="border-radius: 10px;">
        <thead>
        <tr class="bg-gray-200 border">
          <th class="align-middle">Date</th>
          <th class="align-middle">Log Activity</th>
          <th class="align-middle">User</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($logs as $log)
      <tr class="border">
        <td class="align-middle">{{ $log->created_at }}</td>
        <td class="align-middle">{{ $log->activity }}</td>
        <td class="align-middle">{{ $log->user->email ?? 'Email Tidak Ditemukan' }}</td>
      </tr>
    @empty
    <tr>
      <td colspan="3" class="text-center">No data found</td>
    </tr>
  @endforelse
        </tbody>
      </table>
      <div class="d-flex justify-content-center">
        <nav aria-label="Page navigation">
        <ul class="pagination">
          {{ $logs->appends(['search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-5') }}
        </ul>
        </nav>
      </div>
      </div>
    </div>
    </div>
  </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="{{ asset('assets/js/hamburger.js')}} "></script>
@endsection