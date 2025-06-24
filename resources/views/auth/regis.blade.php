<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Pengguna</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    {{-- DataTables --}}
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center"><a href="{{ url('/') }}" class="h1">Point of Sales</a></div>
            <div class="card-body">
                <p class="login-box-msg">Register a new membership</p>
                <form action="{{ route('postregister') }}" method="POST" id="form-register">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="username" name="username" class="form-control"
                            placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <small id="error-username" class="error-text text-danger"></small>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <small id="error-password" class="error-text text-danger"></small>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-id-card"></span>
                            </div>
                        </div>
                        <small id="error-nama" class="error-text text-danger"></small>
                    </div>
                    <div class="input-group mb-3">
                        <select id="level_id" name="level_id" class="form-control">
                            <option value="">Pilih Level</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->level_id }}">{{ $level->level_nama }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-layer-group"></span>
                            </div>
                        </div>
                        <small id="error-level_id" class="error-text text-danger"></small>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <a href="{{ url('login') }}" class="text-center">I already have an account</a>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'An error occurred on the server. Please try again later.'
                });
            }
        });

        $(document).ready(function() {
            $("#form-register").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 20
                    },
                    nama: {
                        required: true,
                        maxlength: 100
                    },
                    level_id: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: "{{ route('postregister') }}",
                        method: "POST",
                        data: $('#form-register').serialize(),
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).map(err => err
                                    .join('\n')).join('\n');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Membuat Akun',
                                    text: errorMessages
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Server Error', //return eror dr be
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan pada server'
                                });
                            }
                        }
                    });

                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
</body>

</html>
