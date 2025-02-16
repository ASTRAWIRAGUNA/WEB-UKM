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

                                <div class="card" style="width: 18rem; min-height: 25rem;">
                                    <img src="{{ asset('storage/profile_photo_ukm/' . $ukm->profile_photo_ukm) }}"
                                        class="card-img-top" width="200px" height="200px" alt="Profile UKM">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $ukm->name_ukm }}</h5>
                                        <p class="card-text">{{ $ukm->description }}</p>
                                        @if ($ukm->registration_status == 'active')
                                            @if (in_array($ukm->ukm_id, $ukmFollowed))
                                                <button class="btn btn-primary shadow-none w-100" disabled>Terdaftar</button>
                                            @else
                                                <form action="{{ route('ukm.join') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="ukm" value="{{ $ukm->ukm_id }}">
                                                    <button type="submit" class="btn btn-primary shadow-none w-100">Daftar</button>
                                                </form>
                                            @endif
                                        @else
                                            @if (in_array($ukm->ukm_id, $ukmFollowed))
                                                <a href="{{ route('home.detail', ['ukm_id' => $ukm->ukm_id]) }}"
                                                    class="btn btn-primary shadow-none w-100">Detail</a>
                                            @else
                                                <button class="btn btn-secondary shadow-none w-100" disabled>Pendaftaran Ditutup</button>
                                            @endif
                                        @endif
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