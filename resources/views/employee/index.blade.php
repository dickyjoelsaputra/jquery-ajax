<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>

<body class="bg-light">
    <main class="container">
        <!-- START DATA -->
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <!-- TOMBOL TAMBAH DATA -->
            <div class="pb-3">
                <a href='' class="btn btn-primary tombol-tambah">+ Tambah Data</a>
            </div>
            <table class="table table-striped" id="myTable">
                <thead>
                    <tr>
                        <th class="col-md-1">No</th>
                        <th class="col-md-5">Nama</th>
                        <th class="col-md-4">Email</th>
                        <th class="col-md-2">Aksi</th>
                    </tr>
                </thead>
            </table>

        </div>
        <!-- AKHIR DATA -->
    </main>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- START FORM -->
                    <div class="alert alert-danger d-none"></div>
                    <div class="alert alert-success d-none"></div>

                    <div class="mb-3 row">
                        <label for="name" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='name' id="name">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='email' id="email">
                        </div>
                    </div>
                    <!-- AKHIR FORM -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary tombol-simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ url('/employees') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'name',
                    name: 'Nama'
                }, {
                    data: 'email',
                    name: 'Email'
                }, {
                    data: 'aksi',
                    name: 'Aksi'
                }]
            });
        });

        // GLOBAL SETUP
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 02_PROSES SIMPAN
        $('body').on('click', '.tombol-tambah', function(e) {
            e.preventDefault();
            $('#exampleModal').modal('show');
            $('.tombol-simpan').click(function() {

                simpan();
            });
        });

        // 03_PROSES EDIT
        $('body').on('click', '.tombol-edit', function(e) {
            var id = $(this).data('id');
            $.ajax({
                url: '/employee/' + id,
                type: 'GET',
                success: function(response) {
                    $('#exampleModal').modal('show');
                    $('#name').val(response.data.name);
                    $('#email').val(response.data.email);
                    console.log(response.data);
                    $('.tombol-simpan').click(function() {
                        simpan(id);
                    });
                }
            });

        });

        // 04_PROSES Delete
        $('body').on('click', '.tombol-delete', function(e) {
            if (confirm('Yakin mau hapus data ini?') == true) {
                var id = $(this).data('id');
                $.ajax({
                    url: '/employee/' + id,
                    type: 'DELETE',
                });
                $('#myTable').DataTable().ajax.reload();
            }
        });

        // fungsi simpan dan update
        function simpan(id = '') {
            if (id == '') {
                var var_url = '/employee';
                var var_type = 'POST';
            } else {
                var var_url = '/employee/' + id + '/edit';
                var var_type = 'PUT';
            }
            $.ajax({
                url: var_url,
                type: var_type,
                data: {
                    name: $('#name').val(),
                    email: $('#email').val()
                },
                success: function(response) {
                    if (response.errors) {
                        console.log(response.errors);
                        $('.alert-danger').removeClass('d-none');
                        $('.alert-danger').html("<ul>");
                        $.each(response.errors, function(key, value) {
                            $('.alert-danger').find('ul').append("<li>" + value +
                                "</li>");
                        });
                        $('.alert-danger').append("</ul>");
                    } else {
                        $('.alert-success').removeClass('d-none');
                        $('.alert-success').html(response.success);
                    }
                    $('#myTable').DataTable().ajax.reload();
                }

            });
        }

        $('#exampleModal').on('hidden.bs.modal', function() {
            $('#name').val('');
            $('#email').val('');

            $('.alert-danger').addClass('d-none');
            $('.alert-danger').html('');

            $('.alert-success').addClass('d-none');
            $('.alert-success').html('');
        });
    </script>
</body>

</html>
