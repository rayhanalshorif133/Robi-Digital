@extends('layouts.app')

@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v1</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="px-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa-solid fa-paper-plane mr-1"></i>
                    Hit Log Sent
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#service-create">Add New</button>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="hitLogSentTableId">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Time</th>
                            <th>Date</th>
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
    <script>
        $(function() {

            url = '/hit_log/sent';
            table = $('#hitLogSentTableId').DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                ordering: false,
                columns: [{
                        render: function(data, type, row) {
                            return `<a href="/service?search=${row.keyword}">${row.keyword}</a>`;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return row.time;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return row.date;
                        },
                        targets: 0,
                    },
                ]
            });
        });
    </script>
@endpush
