@extends('layouts.app')
@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">Service</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">dashboard</a></li>
            <li class="breadcrumb-item active">Service</li>
        </ol>
    </div>
@endsection

@section('content')
@endsection

{{-- scripts --}}
@push('scripts')
@endpush

