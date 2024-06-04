@extends('layouts.app', ['title' => 'Hit Log Sent Details'])

@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">
            Hit Log Sent Details
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
                <b>{{$hitLog->getAOCToken?->spTransID}}</b> Hit Log Details
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
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex"><h5 class="mr-2"><b>Keyword:</b></h5>{{$hitLog->keyword}}</div>
                    <hr/>
                    <div>
                        <h5><b>AOC Response data details:</b></h5>
                        <p><b>aocTransID:</b> {{$hitLog->postBackSendData->aocTransID}}</p>
                        <p><b>spTransID:</b> {{$hitLog->postBackSendData->spTransID}}</p>
                        <p><b>redirectURL:</b> {{$hitLog->postBackSendData->redirectURL}}</p>
                        <hr/>
                        <h5><b>Raw data:</b></h5>
                        <p>{{$hitLog->postBack_send_data}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h5><b> AOC Token Details:</b></h5>
                        <p><b>Msisdn:</b> {{$hitLog->getAOCToken->msisdn}}</p>
                        <p><b>SpTransID:</b> {{$hitLog->getAOCToken->spTransID}}</p>
                        <p><b>Description:</b> {{$hitLog->getAOCToken->description}}</p>
                        <p><b>Currency:</b> {{$hitLog->getAOCToken->currency}}</p>
                        <p><b>Is Subscription?:</b> 
                        @if($hitLog->getAOCToken->isSubscription == 1)
                            <span class="badge badge-success">
                                Subscribed
                            </span>
                        @else
                            <span class="badge badge-danger">
                                Not Subscribed
                            </span>
                        @endif
                        </p>
                        @if($hitLog->getAOCToken->isSubscription == 1)
                        <p><b>Subscription ID:</b> {{$hitLog->getAOCToken->subscriptionID}}</p>
                        <p><b>Subscription Duration:</b> {{$hitLog->getAOCToken->subscriptionDuration}} Days</p>
                        <p><b>UnSubscription URL:</b> {{$hitLog->getAOCToken->unSubURL}}</p>
                        <p><b>UnSubscription API:</b> 
                            @php
                             $unSubscriptionApi =  'https://rd.b2mwap.com/api/cancelSubscription/' . $hitLog->getAOCToken->spTransID .'/' . $hitLog->getAOCToken->msisdn;
                            @endphp
                            <a href="{{$unSubscriptionApi}}" target="_blank" title="Click to unSubscription">
                                {{$unSubscriptionApi}}
                            </a>
                        </p>
                        @endif
                        <hr/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- scripts --}}
@push('scripts')
@endpush
