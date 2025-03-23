@extends('layouts.app', ['title' => 'Customer Log'])

@section('breadcrumb')
    <div class="col-sm-6">
        <h1 class="m-0">
            Customer Log
        </h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Customer Log</li>
        </ol>
    </div>
@endsection

@section('styles')
    <style>
        .loading {
            margin-left: 2rem; 
        }

        .loading h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 6px 5px 0 0;
        }

        .hidden{
            display: none!important;
        }
    </style>
@endsection

@section('content')
    <div class="px-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Find a Customer Log 
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone_number" class="required">Customer's Phone Number</label>
                            <input type="number" class="form-control" value="" id="phone_number" name="phone_number"
                                placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-top: 31px">
                        <div class="d-flex">
                            <div>
                                <button type="button" id="searchBtn" class="btn btn-primary">
                                    <i class="fa-solid fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="subscriber_details-tab" data-toggle="tab"
                            data-target="#subscriber_details" type="button" role="tab"
                            aria-controls="subscriber_details" aria-selected="true">
                            Subscriber Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="subs_log-tab" data-toggle="tab" data-target="#sub_logs" type="button"
                            role="tab" aria-controls="sub_logs" aria-selected="true">
                            Subs Logs
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="charge_log-tab" data-toggle="tab" data-target="#charge_log"
                            type="button" role="tab" aria-controls="charge_log" aria-selected="false">
                            Charge Logs
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="subscriber_details" role="tabpanel"
                        aria-labelledby="subscriber_details-tab">
                        <table class="table my-2">
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">Service Name</th>
                                <th scope="col">Keyword</th>
                                <th scope="col">Subs Date & Time</th>
                                <th scope="col">Unsubs Date & Time</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </thead>
                            <tbody id="subscriber_detail_tbody"></tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="sub_logs" role="tabpanel" aria-labelledby="subs_log-tab">
                        <table class="table my-2">
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">Keyword</th>
                                <th scope="col">Opt Date</th>
                                <th scope="col">Opt Time</th>
                                <th scope="col">Status</th>
                            </thead>
                            <tbody id="subs_log_tbody"></tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="charge_log" role="tabpanel" aria-labelledby="charge_log-tab">
                        <table class="table my-2">
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">Keyword</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Charge Date</th>
                                <th scope="col">Created Time</th>
                            </thead>
                            <tbody id="charge_log_tbody"></tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            const GET_CURRENT_URL = window.location.href;
            const urlParams = new URLSearchParams(new URL(GET_CURRENT_URL).search);
            const msisdn = urlParams.get('msisdn');
            if (msisdn != null) {
                $("#phone_number").val(msisdn);
                $("#searchBtn").click(searchNow());
            }

            $("#searchBtn").click(function() {
                searchNow()
            });

        });

        const checkStatus = (item) => {
            const status = item == 1 ? '<span class="badge badge-success">Subscribed</span>' :
                '<span class="badge badge-danger">Unsubscribed</span>';
            return status;
        };


        const searchNow = () => {
            $(".loading").removeClass('hidden');
            console.clear();
            var phone_number = $("#phone_number").val();
            if (phone_number == '') {
                Toastr.fire({
                    icon: 'error',
                    title: 'Please enter phone number'
                });
                return false;
            }
            axios.get(`/customer-log?phone=${phone_number}`)
                .then((res) => {
                    const {
                        subscriber_details,
                        subs_logs,
                        chargeLogs
                    } = res.data.data;
                    console.log(res.data.data)
                    $("#subscriber_detail_tbody").html('');



                    subscriber_details.length > 0 && subscriber_details.map((item, index) => {
                        const subs_date = moment(item.subs_date, 'YYYY-MM-DD HH:mm:ss')
                            .format('DD-MM-YYYY HH:mm:ss A');
                        const unsubs_date = item.unsubs_date ? moment(item.unsubs_date,
                                'YYYY-MM-DD HH:mm:ss').format('DD-MM-YYYY HH:mm:ss A') :
                            'N/A';

                        const btns = item.status == 1 ?
                            `<button href="#" data-sptransid="${item.spTransID}" data-msisdn="${item.msisdn}" data-keyword="${item.keyword}" class="btn btn-sm btn-outline-danger unSubsNow"><i class="fa-solid fa-xmark"></i></button>` :
                            '';

                            console.log(item);
                        $("#subscriber_detail_tbody").append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.name}</td>
                                <td>${item.keyword}</td>
                                <td>${subs_date}</td>
                                <td>${unsubs_date}</td>
                                <td>${checkStatus(item.status)}</td>
                                <td>${btns}</td>
                            </tr>
                            `);
                    });

                    $(document).on('click', '.unSubsNow', function(event) {
                        const spTransID = $(this).data('sptransid');
                        const msisdn = $(this).data('msisdn');
                        const GET_BASE_URL = window.location.origin;


                        const URL = GET_BASE_URL + '/api/cancelSubscription/' + spTransID + '/' + msisdn;
                        const currentUrl =  GET_BASE_URL + '/customer-log' + '?msisdn=' + msisdn;

                        axios.get(URL).then(res => {
                            if (res) {
                                window.location.href = currentUrl;
                            }
                        });
                    });

                    $("#subs_log_tbody").html('');
                    subs_logs.length > 0 && subs_logs.map((item, index) => {
                        const time = moment(item.opt_time, 'HH:mm:ss').format('hh:mm A');
                        $("#subs_log_tbody").append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.keyword}</td>
                                    <td>${item.opt_date}</td>
                                    <td>${time}</td>
                                    <td>${checkStatus(item.status)}</td>
                                </tr>
                            `);
                    });

                   

                    $("#charge_log_tbody").html('');
                    chargeLogs.length > 0 && chargeLogs.map((item, index) => {
                        const time = moment(item.created_at, 'HH:mm:ss').format('hh:mm A');
                        $("#charge_log_tbody").append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.keyword}</td>
                                    <td>${item.amount} tk</td>
                                    <td>${item.charge_date}</td>
                                    <td>${time}</td>
                                </tr>
                            `);
                    });


                    $(".loading").addClass('hidden');
                })
        };
    </script>
@endpush
