@extends('layouts.app',['title' => 'Token'])

@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">Tokens</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Token</li>
        </ol>
    </div>
@endsection


@section('content')
    <div class="px-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tools mr-1"></i>
                    Tokens
                </h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="serviceTableId">
                    <thead>
                        <tr>
                            <th>AOC Trans ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- scripts --}}
@push('scripts')
@endpush
