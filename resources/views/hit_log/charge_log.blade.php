@extends('layouts.app', ['title' => 'Charge Log'])

@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">
            Charge Log Details
        </h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('hit_log.sent') }}">Hit Log Sent</a>
            </li>
            <li class="breadcrumb-item">Details</li>
        </ol>
    </div>
@endsection

@section('content')
<div class="px-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mt-1">
                <i class="fa-solid fa-paper-plane mr-1"></i>
                <b>asdfs</b> Hit Log Details
            </h3>
            <div class="card-tools">
                <a href="{{route('hit_log.sent')}}" class="btn btn-outline-danger btn-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- scripts --}}
@push('scripts')
@endpush
