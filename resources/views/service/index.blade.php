@extends('layouts.app')
@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">Service</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">dashboard</a></li>
            <li class="breadcrumb-item active">Service</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-hammer mr-1"></i>
                    Service
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#service-create">Add New</button>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="serviceTableId">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Service key</th>
                            <th>Type</th>
                            <th>Validity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    @include('service.create')
@endsection

{{-- scripts --}}
@push('scripts')
    <script>
        $(function() {

            url = '/service';
            table = $('#serviceTableId').DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [{
                        render: function(data, type, row) {
                            return row.name;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return row.service_key;
                            // return getButtons("/poll/admin", row.id);
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return row.type;
                            // return getButtons("/poll/admin", row.id);
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return row.validity;
                            // return getButtons("/poll/admin", row.id);
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return row.name;
                            // return getButtons("/poll/admin", row.id);
                        },
                        targets: 0,
                    },
                ]
            });
        });
    </script>
@endpush
