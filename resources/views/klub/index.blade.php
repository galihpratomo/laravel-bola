<!DOCTYPE html>
<html>
<head>
    <title>Laravel Bola</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>  
    <div class="container">
        <h1>Klub</h1>
        <a class="btn btn-success" href="javascript:void(0)" id="TambahKlub"> Tambah Klub</a>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Klub</th>
                    <th>Kota</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="klubForm" name="klubForm" class="form-horizontal">
                    <input type="hidden" name="klub_id" id="klub_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-5 control-label">Nama Klub</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="nama_klub" name="nama_klub" placeholder="Nama Klub" value="" maxlength="75" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-5 control-label">Kota</label>
                            <div class="col-sm-12">
                            <input type="text" class="form-control" id="kota" name="kota" placeholder="Kota" value="" maxlength="75" required="">
                            </div>
                        </div>
                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="simpanBtn" value="create">Simpan
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

      
<script type="text/javascript">

$(function () {

    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('klub.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama_klub', name: 'nama_klub'},
            {data: 'kota', name: 'kota'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#TambahKlub').click(function () {
        $('#simpanBtn').val("create-klub");
        $('#klub_id').val('');
        $('#klubForm').trigger("reset");
        $('#modelHeading').html("Tambah klub");
        $('#ajaxModel').modal('show');
    });


    $('body').on('click', '.ubahKlub', function () {
      var klub_id = $(this).data('id');
      $.get("{{ route('klub.index') }}" +'/' + klub_id +'/edit', function (data) {
          $('#modelHeading').html("Ubah Klub");
          $('#simpanBtn').val("ubah-klub");
          $('#ajaxModel').modal('show');
          $('#klub_id').val(data.id);
          $('#nama_klub').val(data.nama_klub);
          $('#kota').val(data.kota);
      })
    });

    $('#simpanBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Simpan..');
      
        $.ajax({
            data: $('#klubForm').serialize(),
            url: "{{ route('klub.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                if($.isEmptyObject(data.error)){
                    $('#klubForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                }else{
                    printErrorMsg(data.error);
                    $('#simpanBtn').html('Simpan');
                }
            },
      });
    });

    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }


    $('body').on('click', '.hapusKlub', function () {
        var klup_id = $(this).data("id");
        if (confirm('Apakah Anda ingin menghapus data ini ?')) {
            $.ajax({
                type: "DELETE",
                url: "{{ route('klub.store') }}"+'/'+klup_id,
                success: function (data) {
                    table.draw();
                },

                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });

       

});

</script>

</html>