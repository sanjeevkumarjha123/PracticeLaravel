@extends('layouts.admin')
@section('style')
    <link href='https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet'>
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
                            <a class="btn btn-primary" href = "{{ route('company.create') }}">Add Company</a>
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
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>City Name</th>
                                            <th>State Name</th>
                                            <th>Country Name</th>
                                            <th>Logo</th>
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
    <script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js'></script>   
    <script src='https://cdn.bootcdn.net/ajax/libs/limonte-sweetalert2/11.7.0/sweetalert2.all.js'></script>
    <script>
        $(document).ready(function() {

            $('#example2').DataTable({
                autoWidth: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('company.index') !!}',
                columns: [{
                        data: "companyname"
                    },
                    {
                        data: "city"
                    },
                    {
                        data: "state"
                    },
                    {
                        data: "country"
                    },
                    {
                        data: "logo"
                    },
                    {
                        data: "action",
                        orderable: false,
                    },

                ]
            });
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete this !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('company.delete', '') }}/" + id,
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
