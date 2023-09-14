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
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#service-create">Add New</button>
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
    @include('service.edit')
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
                            const btns = `
                            <div class="btn-group" id="${row.id}">
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
                    $("#service-update").modal('show');
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
                                console.log(response);
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
