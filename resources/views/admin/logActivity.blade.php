@extends('base')

@section('head')
<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('assets/css/styleAdmin.css') }}">
@endsection

@section('body')
  <div class="wrapper">
    @include('partials.sideBarAdmin')
    <div class="main p-3">
      <div class="text-center">
          <h1>
              Sidebar Bootstrap 5
          </h1>

          @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif

          <div class="tables px-4 py-3 shadow-sm rounded">
          <table class="table caption-top table-striped">
            <div class="d-flex justify-content-between mb-3">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">
                Tambah User
              </button>
              
            </div>
            <caption>List of users</caption>
            <thead>
              <tr class="bg-gray-200">
                  <th class="border px-4 py-2">Date</th>
                  <th class="border px-4 py-2">Log Activity</th>
                  <th class="border px-4 py-2">User</th>
              </tr>
          </thead>
          <tbody>
          
              @foreach ($logs as $log)
                  <tr>
                      <td class="border px-4 py-2">{{ $log->created_at }}</td>
                      <td class="border px-4 py-2">{{ $log->activity }}</td>
                      <td class="border px-4 py-2">{{ $log->user->email ?? 'Email Tidak Ditemukan' }}</td>
    
                  </tr>
              @endforeach
          </tbody>
          </table>
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