@extends('base')

@section('head')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
@endsection

@section('body')
<div class="wrapper">
    @include('partials.navbarMahasiswa')
    <div class="main p-3">
        <div class="container mt-4">
            <div class="row justify-content-center g-4">
                @foreach ($ukms as $ukm)
                    @if ($ukm->registration_status == 'active' || in_array($ukm->ukm_id, $ukmFollowed))
                        <!-- Kolom responsif -->
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card border-0 shadow-md">
                                <div class="card-body shadow-sm">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <!-- Gambar -->
                                        <img src="{{ asset('storage/profile_photo_ukm/' . $ukm->profile_photo_ukm) }}"
                                            alt="Profile UKM" class="rounded-circle me-3"
                                            style="width: 60px; height: 60px; object-fit: cover;">

                                        <!-- Nama UKM -->
                                        <h5 class="mb-0 flex-grow-1 fw-bold">{{ $ukm->name_ukm }}</h5>

                                        <!-- Tombol -->
                                        @if ($ukm->registration_status == 'active')
                                            @if (in_array($ukm->ukm_id, $ukmFollowed))
                                                <button class="btn btn-primary" disabled>Terdaftar</button>
                                            @else
                                                <form action="{{ route('ukm.join') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="ukm" value="{{ $ukm->ukm_id }}">
                                                    <button type="submit" class="btn btn-primary">Daftar</button>
                                                </form>
                                            @endif
                                        @else
                                            @if (in_array($ukm->ukm_id, $ukmFollowed))
                                                <a href="#" class="btn btn-primary">Detail</a>
                                            @else
                                                <button class="btn btn-secondary" disabled>Pendaftaran Ditutup</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection