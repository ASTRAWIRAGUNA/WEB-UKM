@extends('base')

@section('head')
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/styleAdmin.css') }}">
@endsection

@section('body')
<div class="wrapper">
    @include('partials.sideBarAdmin')

    <div class="main p-3">
        <div class="text-center">
            <h1>UKM List</h1>
        </div>

        <div id="ukm-container" class="row">
            @foreach ($ukms as $ukm)
            <div class="col-md-4">
                <div class="card mb-3">
                  <img src="{{ asset('storage/profile_photo_ukm/'. $ukm->profile_photo_ukm )}}" alt="" style="width: 20%"></td>
                    <div class="card-body">
                        <h5 class="card-title">{{ $ukm->name_ukm }}</h5>
                        <p class="card-text">{{ $ukm->description }}</p>
                        
                        @if ($ukm->registration_status == 'active')
                            <button class="btn btn-primary">Daftar</button>
                        @else
                            <a href="#" class="btn btn-primary" hidden>Dstail</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection