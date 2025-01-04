@extends('layouts.app')

@section('template_title')
    {{ $activity->name ?? __('Show') . " " . __('Activity') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Activity</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('activities.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Ukm Id:</strong>
                                    {{ $activity->ukm_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $activity->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Date:</strong>
                                    {{ $activity->date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Proof Photo:</strong>
                                    {{ $activity->proof_photo }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
