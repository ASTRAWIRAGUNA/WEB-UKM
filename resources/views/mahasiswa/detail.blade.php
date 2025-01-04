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
            <div class="row justify-content-center gap-3">
                @foreach ($ukms as $ukm)
                <div class="col-auto bg-light rounded-md" style="width: 25rem;">
                    <div class="d-flex align-items-center justify-content-between p-3 text-center">
                        <img src="{{ asset('storage/profile_photo_ukm/'. $ukm->profile_photo_ukm )}}" alt=""
                            class="rounded-circle mx-auto mb-2" style="width: 30%"></td>
                        <h5 class="">{{ $ukm->name_ukm }}</h5>
                        @if ($ukm->registration_status == 'active')
                        <button class="btn btn-primary ml-4">Daftar</button>
                        @else
                        <a href="#" class="btn btn-primary ml-4" hidden>Detail</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection