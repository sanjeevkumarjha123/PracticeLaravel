@extends('layouts.admin')

@section('content')
    <style>
        label.error {
            color: #dc3545;
            font-size: 14px;
        }
    </style>

    <div class="content-wrapper" style="min-height: 1345.31px;">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Page</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="form" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname"
                            value="{{ $edit_data['fullname'] }}">
                    </div>
                    <div class="form-group">
                        <label for="mobileNumber">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" name="mobile"
                            value="{{ $edit_data['mobile'] }}">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="custom-select rounded-0" id="role" name="role">
                            <option value="" disabled selected>Please Select Role</option>
                            <option value="Admin" @if ($edit_data['role'] == 'Admin') selected @endif>Admin</option>
                            <option value="Employee" @if ($edit_data['role'] == 'Employee') selected @endif>Employee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ $edit_data['email'] }}">
                    </div>
                    <div class="form-group" id="file_upload">
                        <label for="file">Upload File</label>
                        @if ($edit_data->image)
                            <a href="{{ asset('uploads/students/' . $edit_data->image) }}" alt="Logo"
                                class="btn btn-primary mt-2">View</a>
                        @endif

                        <input type="file" class="form-control" id="inputFile" name="inputFile"
                            value="{{ $edit_data['image'] }}">
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="form_submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('model')
    <script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js'></script>
    <script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js'></script>
    <script src='https://cdn.bootcdn.net/ajax/libs/limonte-sweetalert2/11.7.0/sweetalert2.all.js'></script>

    <script>
        $(document).ready(function() {
            $('#form').validate({
                rules: {
                    fullname: {
                        required: true,
                        maxlength: 20,
                        lettersonly: true,
                    },
                    mobile: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true,
                    },
                    role: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 50
                    },
                },
                messages: {
                    fullname: {
                        required: "Full name is required.",
                        maxlength: "Full name cannot be more than 20 characters.",
                        lettersonly: "Only letters are allowed.",
                    },
                    mobile: {
                        required: "Mobile number is required",
                        minlength: "Phone number must be of 10 digits",
                        number: "Only numbers are allowed.",
                    },
                    role: {
                        required: "Role is required",
                    },
                    email: {
                        required: "Email is required",
                        email: "Email must be a valid email address",
                        maxlength: "Email cannot be more than 50 characters",
                    },
                }
            });

            $(document).on('click', '#form_submit', function(e) {
                e.preventDefault();
                if ($('#form').valid()) {
                    swal.fire({
                        title: 'Are you sure?',
                        text: 'You want to update this!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                    }).then(function(result) {
                        var form_data = new FormData($('#form')[0]);
                        if (result.value) {
                            $.ajax({
                                url: "{{ route('user.update', $edit_data->id) }}",
                                type: 'POST',
                                contentType: false,
                                processData: false,
                                cache: false,
                                data: form_data,
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
                                            window.location.href =
                                                '{{ route('user.index') }}';
                                        }
                                    });
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire(
                                        "Error",
                                        "Oops!! Something went wrong, please try again.",
                                        "error"
                                    );
                                }
                            });
                        }
                    });
                }
            });
        });
        $("#role").change(function() {
            var firstChar = $('#role').val();
            if (firstChar == "Employee") {
                $("#file_upload").show();
            } else {
                $("#file_upload").hide();
            }
        });
    </script>
@endsection
