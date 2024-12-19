@extends('layouts.admin')
@section('style')
    <link href='https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-group .select2-container .select2-selection--single {
            height: 38px;
            padding: 5px 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 32px !important;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>DataTables</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a class="btn btn-primary" href = "{{ route('user.create') }}">Add User</a>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">DataTable with minimal features & hover style</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" style="max-width: 5%">Role</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select2" id="test">
                                            <option value=""></option>
                                            <option value="Admin">Admin</option>
                                            <option value="Employee">Employee</option>
                                        </select>
                                    </div>
                                    <a class="btn btn-primary" id="search">Search</a>
                                </div>
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Company Name</th>
                                            <th>Mobile Number</th>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('model')
    <script src='https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'></script>
    <script src='https://cdn.bootcdn.net/ajax/libs/limonte-sweetalert2/11.7.0/sweetalert2.all.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#example2').DataTable({
                autoWidth: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('user.index') !!}',
                columns: [{
                        data: "fullname"
                    },
                    {
                        data: "company"
                    },
                    {
                        data: "mobile"
                    },
                    {
                        data: "role"
                    },
                    {
                        data: "email"
                    },
                    {
                        data: "image"
                    },
                    {
                        data: "action",
                        orderable: false,
                    },

                ]
            });
            $("#search").click(function() {
                var selectedData = $("#test").find("option:selected").val();
                $('#example2').DataTable({
                    destroy: true,
                    autoWidth: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('user.index') !!}',
                        data: {
                            'role': selectedData
                        }
                    },
                    columns: [{
                            data: "fullname"
                        },
                        {
                            data: "company"
                        },
                        {
                            data: "mobile"
                        },
                        {
                            data: "role"
                        },
                        {
                            data: "email"
                        },
                        {
                            data: "image"
                        },
                        {
                            data: "action",
                            orderable: false,
                        },

                    ]
                });
            });

            $('#test').select2({
                placeholder: "Select role",
                width: "100%",
                allowClear: true
            });
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var url = "{{ route('user.delete', '') }}/" + id;
                console.log("URL",url);
                swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete this !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('user.delete', '') }}/" + id,
                            type: 'GET',
                            contentType: false,
                            processData: false,
                            cache: false,
                            success: function(response) {
                                var status = response.status == 200 ? 'success' :
                                    'warning';
                                var title = response.status == 200 ? 'Success' :
                                    'Warning';
                                Swal.fire(
                                    title,
                                    response.message,
                                    status
                                ).then(function(result) {
                                    if (result.value) {
                                        location.reload();
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    "Error",
                                    "Oops! Something went wrong, please try again.",
                                    "error"
                                )
                            }
                        });
                    }
                });
            });


        });
    </script>
@endsection
