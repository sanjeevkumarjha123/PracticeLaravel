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
      <h3 class="card-title">Employee Form</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form id="form" enctype="multipart/form-data">
        @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="fullName">Full Name</label>
          <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter Full Name">
          {{-- <span style="color:red">
            @error('fullname')
                {{ $message }}
            @enderror
        </span> --}}
        </div>
        <div class="form-group">
          <label for="company_id">Company Name</label>
          <select class="custom-select rounded-0" id="company_id" name="company_id">
              <option disabled selected>Select Company</option>
              @foreach($companies as $company)
                  <option value="{{ $company->id }}">{{ $company->name }}</option>
              @endforeach
          </select>
          {{-- <span style="color:red">
              @error('company_id')
                  {{ $message }}
              @enderror
          </span> --}}
      </div>

        <div class="form-group">
          <label for="mobileNumber">Mobile Number</label>
          <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile Number">
          {{-- <span style="color:red">
            @error('mobile')
                {{ $message }}
            @enderror
        </span> --}}
        </div>
        <div class="form-group">
            <label for="role">Role </label>
            <select class="custom-select rounded-0" id="role" name="role">
              <option disabled selected>Please Select Role</option>
              <option>Admin</option>
              <option>Employee</option>
            </select>
            {{-- <span style="color:red">
                @error('role')
                    {{ $message }}
                @enderror
            </span> --}}
          </div>
        <div class="form-group">
          <label for="email">Email address</label>
          <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email">
          {{-- <span style="color:red">
            @error('email')
                {{ $message }}
            @enderror
        </span> --}}
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        {{-- <span style="color:red">
          @error('password')
              {{ $message }}
          @enderror
      </span> --}}
      <div class="form-group" id="file_upload">
        <label for="file">Upload File</label>
        <input type="file" class="form-control" id="inputFile" name="inputFile">
        {{-- <span style="color:red">
          @error('file')
              {{ $message }}
          @enderror
      </span> --}}
    </div>
    </div>
  </div>
</div>
  <!-- /.card-body -->

      <div class="card-footer">
        <button type="submit" class="btn btn-primary" id="form_submit">Submit</button>
        {{-- <button type="submit" class="btn btn-primary">Clear</button> --}}
        {{-- <a class="btn btn-primary" href = "{{route('fetchData')}}">Show Form List</a>
        <a class="btn btn-primary" href = "{{route('index')}}">Show Data Table</a> --}}
      </div>
    </form>
  </div>
</div>
@endsection
@section('model')
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js'></script>
<script src='https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js'></script>
<script src='https://cdn.bootcdn.net/ajax/libs/limonte-sweetalert2/11.7.0/sweetalert2.all.js'></script>
<script>
    $(document).ready(function () {
    $('#form').validate({    // initialize the plugin
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
            password: {
                required: true,
                minlength: 5,
           },
           inputFile: {
                required: true,
                extension: "jpeg|png", 
            },
        },
        messages: {
                    fullname: {
                        required: "Full name is required.",
                        maxlength: "First name cannot be more than 20 characters.",
                        lettersonly: "Only Letters allowed.",
                    },
                    mobile: {
                        required: "Mobile number is required",
                        minlength: "Phone number must be of 10 digits",
                        number: "Only numbers allowed.",
                    },
                    role: {
                      required: "Role is required",
                    },
                    email: {
                        required: "Email is required",
                        email: "Email must be a valid email address",
                        maxlength: "Email cannot be more than 50 characters",
                    },
                    password: {
                        required: "Password is required",
                        minlength: "Password must be at least 5 characters"
                    },
                    inputFile: {
                      required: "File is required",
                      extension: "Please select only jpeg and png files",
                    }

                }
      });
      $(document).on('click', '#form_submit', function(e) {
      e.preventDefault();
      if($('#form').valid()) {
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
                      url: "{{ route('user.store') }}",
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
                                window.location.href = '{{ route('user.index') }}';
                              }
                          });
                      },
                      error: function(xhr, status, error) {
                          Swal.fire(
                              "Error",
                              "Opps!! something went wrong, please try again.",
                              "error"
                          )
                      }
                  });
              }
          });
      }
  })
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
