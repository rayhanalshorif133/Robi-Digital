@extends('layouts.app', ['title' => 'Subs and Unsubs Logs Details'])

@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">
            Subs Based Logs Details
        </h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Subs Based Logs</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="px-2">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">
                    <i class="fa-solid fa-paper-plane mr-1"></i>
                    Subs Based Logs Details
                </h3>
                <div class="d-flex ml-auto">
                    <input type="date" class="form-control form-control-sm mx-1" id="searchDate">
                    <button type="button" class="btn btn-sm btn-primary d-flex ml-auto text-end searchBtn">
                        <i class="fa-solid fa-search mr-1 mt-1"></i>
                        Go
                    </button>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Keyword</th>
                            <th>Subscription Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subBasedLogs as $key => $subsBasedLog)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $subsBasedLog->keyword }}</td>
                                <td>{{ $subsBasedLog->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align:end">
                                Total
                            </th>
                            <th id="total_subs">
                                {{ $subBasedLogs->sum('total') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var url = document.URL;

        

        if(url.includes('?')) {
            var date = url.split('?')[1].split('=')[1];
            $('#searchDate').val(date);
        }


        $('.searchBtn').click(function() {
            let date = $('#searchDate').val();
            window.location.href = `/subs-based?date=${date}`;
        });
    </script>
@endpush
