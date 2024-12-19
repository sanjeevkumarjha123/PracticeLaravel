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
                <h3 class="card-title">Company Form</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="form" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="companyname">Company Name</label>
                        <input type="text" class="form-control" id="companyname" name="companyname"
                            placeholder="Enter Company Name">
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select class="form-control" id="country" name="country">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <select class="form-control" id="state" name="state">
                            <option value="">Select State</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <select class="form-control" id="city" name="city">
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="form-group" id="logo_upload">
                        <label for="logo">Upload Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo">
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
            $('#country').on('change', function() {
                var country_id = $(this).val();
                console.log("country_id",country_id);
                if(country_id) {
                    $.ajax({
                        url: '/company/states/' + country_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#state').empty();
                            $('#state').append('<option value="">Select State</option>');
                            $.each(data, function(key, value) {
                                $('#state').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                            });
                        }
                    });
                } else {
                    $('#state').empty();
                    $('#city').empty();
                }
            });

            $('#state').on('change', function() {
                var state_id = $(this).val();
                if(state_id) {
                    $.ajax({
                        url: '/company/cities/' + state_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#city').empty();
                            $('#city').append('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                $('#city').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                            });
                        }
                    });
                } else {
                    $('#city').empty();
                }
            });

                $('#form').validate({ 
                rules: {
                    companyname: {
                        required: true,
                        maxlength: 20,
                    },
                    city: {
                        required: true,
                    },
                    state: {
                        required: true,
                    },
                    country: {
                        required: true,
                    },
                    logo: {
                        required: true,
                        extension: "jpeg|png|svg",
                    },
                },
                messages: {
                    companyname: {
                        required: "Company name is required.",
                        maxlength: "Company name cannot be more than 20 characters.",
                    },
                    city: {
                        required: "City is required",
                    },
                    state: {
                        required: "State is required",
                    },
                    country: {
                        required: "Country is required",
                    },
                    logo: {
                        required: "Logo is required",
                        extension: "Please select only jpeg, png and svg files",
                    }
                }
            });

            $(document).on('click', '#form_submit', function(e) {
                e.preventDefault();
                if ($('#form').valid()) {
                    swal.fire({
                        title: 'Are you sure?',
                        text: 'You want to save this !',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                    }).then(function(result) {
                        var form_data = new FormData($('#form')[0]);
                        if (result.value) {
                            $.ajax({
                                url: "{{ route('company.store') }}",
                                type: 'POST',
                                contentType: false,
                                processData: false,
                                cache: false,
                                data: form_data,
                                success: function(response) {
                                    var status = response.status == 200 ? 'success' : 'warning';
                                    var title = response.status == 200 ? 'Success' : 'Warning';
                                    Swal.fire(
                                        title,
                                        response.message,
                                        status
                                    ).then(function(result) {
                                        if (result.value) {
                                            window.location.href = '{{ route('company.index') }}';
                                        }
                                    });
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire(
                                        "Error",
                                        "Oops!! something went wrong, please try again.",
                                        "error"
                                    )
                                }
                            });
                        }
                    });
                }
            })
        });
    </script>
@endsection
