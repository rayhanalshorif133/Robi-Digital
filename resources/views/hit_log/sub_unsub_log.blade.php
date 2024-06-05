@extends('layouts.app', ['title' => 'Subs and Unsubs Logs Details'])

@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">Subs and Unsubs</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Subs and Unsubs</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="px-2">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">
                        <i class="fa-solid fa-paper-plane mr-1"></i>
                        Subs and Unsubs Logs Details
                    </h3>
                    <div class="d-flex space-x-2">
                        @php
                            $start_date = date('Y-m-d');
                            $end_date = date('Y-m-d');
                        @endphp
                        <input type="date" class="form-control mx-2" id="start_date" value="{{ $start_date }}">
                        <input type="date" class="form-control" id="end_date" value="{{ $end_date }}">
                        <button class="btn btn-primary d-flex" id="searchBtn">
                            <i class="fas fa-search m-1"></i>
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div>
                    <h5 class="mx-1">
                        Subs and Unsubs Logs -
                        <span class="text-primary">From:</span>
                        <span class="from fw-bolder">{{ $start_date }}</span>
                        <span class="text-primary">To:</span>
                        <span class="to fw-bolder">{{ $end_date }}</span>
                    </h5>
                </div>
                <table class="table table-bordered" id="subsAndUnsubsTableId">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Keyword</th>
                            <th>Subscription Count</th>
                            <th>Unsubscription Count</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align:end">
                                Total
                            </th>
                            <th id="total_subs">0</th>
                            <th id="total_unsubs">0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- scripts --}}

@push('scripts')
    <script>
        $(function() {

            var totalSubscount = 0;
            var totalUnsubsCount = 0;

            const columns = [{
                    render: function(data, type, row) {
                        return row.DT_RowIndex;
                    },
                    targets: 0,
                },
                {
                    render: function(data, type, row) {
                        return row.keyword;
                    },
                    targets: 0,
                },
                {
                    render: function(data, type, row) {
                        const subCount = row.subscount ? row.subscount : 0;
                        return subCount;
                    },
                    targets: 0,
                },
                {
                    render: function(data, type, row) {
                        const unsubscount = row.unsubscount ? row.unsubscount : 0;
                        return unsubscount;
                    },
                    targets: 0,
                }
            ];

            url = '/sub-unsub-log?start_date=' + $('#start_date').val() + '&end_date=' + $('#end_date').val();
            table = $('#subsAndUnsubsTableId').DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                ordering: false,
                paging: false,
                searching: false,
                info: false,
                columns: columns,
                footerCallback: function(row, data, start, end, display) {
                    totalSubscount = 0;
                    totalUnsubsCount = 0;
                    data.length > 0 && data.forEach((item, index) => {
                        totalSubscount += item.subscount;
                        totalUnsubsCount += item.unsubscount;
                    });
                    $('#total_subs').text(totalSubscount);
                    $('#total_unsubs').text(totalUnsubsCount);
                    return 0;
                },
            });

            $("#start_date").change(function(event) {
                const start_date = $('#start_date').val();
                $('#end_date').val(start_date);
            });

            $('#searchBtn').click(function() {
                table.destroy();
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();
                $(".from").text(start_date);
                $(".to").text(end_date);
                url = '/sub-unsub-log?start_date=' + start_date + '&end_date=' + end_date;
                table = $('#subsAndUnsubsTableId').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: url,
                    ordering: false,
                    paging: false,
                    searching: false,
                    info: false,
                    columns: columns,
                    footerCallback: function(row, data, start, end, display) {
                        totalSubscount = 0;
                        totalUnsubsCount = 0;
                        data.length > 0 && data.forEach((item, index) => {
                            totalSubscount += item.subscount;
                            totalUnsubsCount += item.unsubscount;
                        });
                        $('#total_subs').text(totalSubscount);
                        $('#total_unsubs').text(totalUnsubsCount);
                        return 0;
                    },
                });
            });
        });
    </script>
@endpush
