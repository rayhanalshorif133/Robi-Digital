@extends('layouts.app', ['title' => 'Service'])
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
    <div class="px-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-hammer mr-1"></i>
                    Service
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#service-create">Add New</button>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="serviceTableId">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Channel</th>
                            <th>keyword</th>
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
    @include('service.edit')
    @include('service.show')
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
                ordering: false,
                columns: [{
                        render: function(data, type, row) {
                            return row.name;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return row.type;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            return row.channel;
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
                            return row.validity;
                        },
                        targets: 0,
                    },
                    {
                        render: function(data, type, row) {
                            const btns = `
                            <div class="btn-group" id="${row.id}">
                                    <button type="button" class="btn btn-outline-success serviceShowBtn" data-toggle="modal"
                                    data-target="#service-show">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info serviceEditBtn" data-toggle="modal"
                                    data-target="#service-edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger serviceDeleteBtn">
                                    <i class="fa-solid fa-trash"></i>
                                    </button>
                            </div>
                            `;

                            return btns;
                        },
                        targets: 0,
                    },
                ]
            });
            serviceEditBtnHandler();
            serviceDeleteBtnHandler();
            serviceShowBtnHandaler();
        });

        const serviceEditBtnHandler = () => {
            $(document).on('click', '.serviceEditBtn', function() {
                const id = $(this).parent().attr('id');
                console.log(id);
                axios.get(`/service/${id}/edit`)
                .then(function(response) {
                    const data = response.data.data;
                    $("#serviceUpdateFrom").attr('action', `/service/${id}`);
                    $("#serviceUpdateFrom input[name='name']").val(data.name);
                    $("#serviceUpdateFrom select[name='type']").val(data.type);
                    $("#serviceUpdateFrom select[name='validity']").val(data.validity);
                    $("#serviceUpdateFrom input[name='purchase_category_code']").val(data.purchase_category_code);
                    $("#serviceUpdateFrom input[name='reference_code']").val(data.reference_code);
                    $("#serviceUpdateFrom input[name='channel']").val(data.channel);
                    $("#serviceUpdateFrom input[name='on_behalf_of']").val(data.on_behalf_of);
                    $("#serviceUpdateFrom input[name='keyword']").val(data.keyword);
                    
                    $("#service-update").modal('show');
                });
            });
        };
        
        const serviceShowBtnHandaler = () => {
            $(document).on('click', '.serviceShowBtn', function() {
                const id = $(this).parent().attr('id');
                console.log(id);
                axios.get(`/service/${id}`)
                .then(function(response) {
                    const data = response.data.data;
                    $(".show_service_name").text(data.name);
                    $(".show_service_type").text(data.type);
                    $(".show_service_validity").text(data.validity);
                    $(".show_purchase_category_code").text(data.purchase_category_code);
                    $(".show_reference_code").text(data.reference_code);
                    $(".show_channel").text(data.channel);
                    $(".show_on_behalf_of").text(data.on_behalf_of);               
                    $("#service-show").modal('show');
                });
            });
        };
        const serviceDeleteBtnHandler = () => {
            $(document).on('click', '.serviceDeleteBtn', function() {
                const id = $(this).parent().attr('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/service/${id}`)
                            .then(function(response) {
                                table.ajax.reload();
                            })
                            .catch(function(error) {
                                console.log(error);
                            });
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }else{
                        Swal.fire(
                            'Cancelled!',
                            'Your file is safe.',
                            'error'
                        )
                    }
                })
            });
        };
    </script>
@endpush
