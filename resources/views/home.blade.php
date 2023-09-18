@extends('layouts.app',['title' => 'Dashboard'])
@section('breadcrumb')
<div class="col-sm-6">
    <h1 class="m-0">Dashboard</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </div>
@endsection


@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{$services}}</h3>
            <p> Services </p>
          </div>
          <div class="icon">
            <i class="ion ion-hammer"></i>
          </div>
          <a href="{{route('service.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>
              {{$hitLogs}}
            </h3>

            <p>
              Total Hit Logs
            </p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="{{route('hit_log.sent')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
  </div>
</section>
@endsection
