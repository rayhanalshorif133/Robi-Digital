@extends('layouts.app', ['title' => 'Charge Log Details'])

@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">Charge Log</h1>
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
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">
                        <i class="fa-solid fa-paper-plane mr-1"></i>
                        Charge Log Details
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
                        Charge Log -
                        <span class="text-primary">From:</span>
                        <span class="from fw-bolder">{{ $start_date }}</span>
                        <span class="text-primary">To:</span>
                        <span class="to fw-bolder">{{ $end_date }}</span>
                    </h5>
                </div>
                <table class="table table-bordered" id="chargeLogTableId">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Keyword</th>
                            <th>Subscription Count</th>
                            <th>Renew Count</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align:end">
                                Total
                            </th>
                            <th id="total_subs">0</th>
                            <th id="total_renew">0</th>
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
            var totalRenewcount = 0;
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
                        const renewCount = row.renewcount ? row.renewcount : 0;
                        return renewCount;
                    },
                    targets: 0,
                }
            ];

            $("#start_date").change(function(event) {
                const start_date = $('#start_date').val();
                $('#end_date').val(start_date);
            });


            url = '/charge-log?start_date=' + $('#start_date').val() + '&end_date=' + $('#end_date').val();
            let INIT_DATATABLE = {
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
                    totalRenewcount = 0;
                    data.length > 0 && data.forEach((item, index) => {
                        totalSubscount += item.subscount;
                        totalRenewcount += item.renewcount;
                    });
                    $('#total_subs').text(totalSubscount);
                    $('#total_renew').text(totalRenewcount);
                    return 0;
                },
            };

            table = $('#chargeLogTableId').DataTable(INIT_DATATABLE);


            $('#searchBtn').click(function() {
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();
                $(".from").text(start_date);
                $(".to").text(end_date);
                table.destroy();
                url = '/charge-log?start_date=' + start_date + '&end_date=' + end_date;
                let INIT_DATATABLE = {
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
                        totalRenewcount = 0;
                        data.length > 0 && data.forEach((item, index) => {
                            totalSubscount += item.subscount;
                            totalRenewcount += item.renewcount;
                        });
                        $('#total_subs').text(totalSubscount);
                        $('#total_renew').text(totalRenewcount);
                        return 0;
                    },
                };
                table = $('#chargeLogTableId').DataTable(INIT_DATATABLE);
            });



        });
    </script>
@endpush
