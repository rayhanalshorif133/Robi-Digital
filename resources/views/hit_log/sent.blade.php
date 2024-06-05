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
                    Hit Log Sent
                </h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="hitLogSentTableId">
                    <thead>
                        <tr>
                            <th>SP Trans ID</th>
                            <th>Keyword</th>
                            <th>Time</th>
                            <th>Date</th>
                            <th>Is Subscription</th>
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

            url = '/hit_log/sent';
            table = $('#hitLogSentTableId').DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                ordering: false,
                columns: [
                    {
                        render: function(data, type, row) {
                            return `<span>${row.get_a_o_c_token?.spTransID}</span>`;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return `<a href="/service?search=${row.keyword}">${row.keyword}</a>`;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return moment(row.time, 'HH:mm:ss').format('hh:mm A');
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return moment(row.date).format('DD-MMM-YYYY');
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            console.log(row.get_a_o_c_token.isSubscription)
                            const is_subs = row.get_a_o_c_token.isSubscription;
                            const status = is_subs == 1? '<span class="badge bg-success">True</span>' :
                            '<span class="badge bg-danger">False</span>';
                            return status;
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
