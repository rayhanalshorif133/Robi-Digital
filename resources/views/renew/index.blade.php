@extends('layouts.app', ['title' => 'Hit Log Sent'])

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
                    Renew Log
                </h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="renewLogTableId">
                    <thead>
                        <tr>
                            <th>#sl</th>
                            <th>Msisdn</th>
                            <th>Keyword</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Action</th>
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

            url = '/renew-log';
            table = $('#renewLogTableId').DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                ordering: false,
                columns: [
                    {
                        render: function(data, type, row) {
                            return row.DT_RowIndex;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return `<span>${row.msisdn}</span>`;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return `<span>${row.keyword}</span>`;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return `<span>${row.amount}</span>`;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return moment(row.created_at).format('DD-MMM-YYYY');
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return `<a href="/hit_log/sent/${row.id}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i>
                                </a>`;
                        },
                        targets: 0,
                    },
                ]
            });
        });
    </script>
@endpush
