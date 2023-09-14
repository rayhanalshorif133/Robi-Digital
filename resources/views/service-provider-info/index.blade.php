@extends('layouts.app')

@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">
            Service Provider Info
        </h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Service Provider Info</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Service Provider Info
                </h3>
            </div>

            <div class="card-body">
                <form action="{{route('service-provider-info.update',$serviceProviderInfo->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aoc_endpoint_url" class="required">AOC Endpoint URL </label>
                                    <input type="text" name="aoc_endpoint_url" id="aoc_endpoint_url" required class="form-control"
                                        placeholder="Enter AOC Endpoint URL" value="{{$serviceProviderInfo->aoc_endpoint_url}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aoc_redirection_url" class="required">AOC Redirection URL </label>
                                    <input type="text" name="aoc_redirection_url" id="aoc_redirection_url" required class="form-control"
                                        placeholder="Enter AOC Redirection URL " value="{{$serviceProviderInfo->aoc_redirection_url}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aoc_getAOCToken_url" class="required">Get AOC Token URL</label>
                                    <input type="text" name="aoc_getAOCToken_url" id="aoc_getAOCToken_url" required class="form-control"
                                        placeholder="Enter Get AOC Token URL" value="{{$serviceProviderInfo->aoc_getAOCToken_url}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sp_username" class="required">SP Username</label>
                                    <input type="text" name="sp_username" id="sp_username" required class="form-control"
                                        placeholder="Enter Service Name" value="{{$serviceProviderInfo->sp_username}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sp_api_key" class="required">SP API Key</label>
                                    <input type="text" name="sp_api_key" id="sp_api_key" required class="form-control"
                                        placeholder="Enter Service Name" value="{{$serviceProviderInfo->sp_api_key}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
