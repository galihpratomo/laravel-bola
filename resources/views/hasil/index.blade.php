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
        <h1>hasil</h1>
        <a class="btn btn-success" href="javascript:void(0)" id="Tambahhasil"> Tambah hasil</a>
        <a class="btn btn-warning" href="{{ url('/hasil-multiple') }}" > Tambah Multiple</a>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Klub</th>
                    <th>Main</th>
                    <th>Menang</th>
                    <th>Seri</th>
                    <th>Kalah</th>
                    <th>Goal Menang</th>
                    <th>Goal Kalah</th>
                    <th>Poin</th>
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
                    <form id="hasilForm" name="hasilForm" class="form-horizontal">
                    <input type="hidden" name="hasil_id" id="hasil_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-5 control-label">Pilih Klub</label>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col">
                                        <select required name="klub_a" id="id_klub_a" class="form-control">
                                            <option value=''>Pilih Klub A</option>
                                            @foreach ($klub as $row)
                                                <option value="{{ $row->id }}">{{ $row->nama_klub }}</option>
                                            @endforeach 
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select required name="klub_b" id="id_klub_b" class="form-control">
                                            <option value=''>Pilih Klub B</option>
                                            @foreach ($klub as $row)
                                                <option value="{{ $row->id }}">{{ $row->nama_klub }}</option>
                                            @endforeach 
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-5 control-label">Score</label>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="score_a" id="score_a" class="form-control" placeholder="Score A" onkeypress='return check_int(event)' required="">
                                    </div>
                                    <div class="col">
                                        <input type="text" name="score_b" id="score_b" class="form-control" placeholder="Score B" onkeypress='return check_int(event)' required="">
                                    </div>
                                </div>
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
    function check_int(evt) {
        var charCode = ( evt.which ) ? evt.which : event.keyCode;
        return ( charCode >= 48 && charCode <= 57 || charCode == 8 );
    }

    $(document).on('change', '#id_klub_a', function(e) {
        var klub_a = $(this).val();
        var klub_b = document.getElementById("id_klub_b").value;
        if (!klub_b) {
            console.log("klub b kosong");
        } else if (klub_a == klub_b) {
            document.getElementById('id_klub_a').value="";
            alert("Klub A tidak boleh sama Klub B !!");
        } else {
            console.log("CEEEEEK");
            $.ajax({
                url     :"{{ url('cek-data-hasil') }}",
                method  :"POST",
                data    :{
                            "_token"    : "{{ csrf_token() }}",
                            "klub_a"    : $(this).val(),
                            "klub_b"    : klub_b
                        },
                success: function (data) {
                    if (data.type === 'success') {
                        document.getElementById('id_klub_a').value="";
                        alert(data.message);
                    }
                }
            })
        }
        
    });

    $(document).on('change', '#id_klub_b', function(e) {
        var klub_b = $(this).val();
        var klub_a = document.getElementById("id_klub_a").value;
        if (!klub_b) {
            console.log("klub a kosong");
        } else if (klub_b == klub_a) {
            document.getElementById('id_klub_b').value="";
            alert("Klub B tidak boleh sama Klub A !!");
        } else {
            console.log("CEEEEEK BB");
            $.ajax({
                url     :"{{ url('cek-data-hasil') }}",
                method  :"POST",
                data    :{
                            "_token"    : "{{ csrf_token() }}",
                            "klub_b"    : $(this).val(),
                            "klub_a"    : klub_a
                        },
                success: function (data) {
                    if (data.type === 'success') {
                        document.getElementById('id_klub_b').value="";
                        alert(data.message);
                    }
                }
            })
        }
        
    });

$(function () {

    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting : [[ 8, "desc" ]],
        ajax: "{{ route('hasil.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama_klub', name: 'nama_klub', orderable: false},
            {data: 'main', name: 'main', orderable: false, searchable: false},
            {data: 'menang', name: 'menang', orderable: false, searchable: false},
            {data: 'seri', name: 'seri', orderable: false, searchable: false},
            {data: 'kalah', name: 'kalah', orderable: false, searchable: false},
            {data: 'goal_menang', name: 'goal_menang', orderable: false, searchable: false},
            {data: 'goal_kalah', name: 'goal_kalah', orderable: false, searchable: false},
            {data: 'poin', name: 'poin'},
        ]
    });

    $('#Tambahhasil').click(function () {
        $('#simpanBtn').val("create-hasil");
        $('#hasil_id').val('');
        $('#hasilForm').trigger("reset");
        $('#modelHeading').html("Tambah hasil");
        $('#ajaxModel').modal('show');
    });


    $('body').on('click', '.ubahhasil', function () {
      var hasil_id = $(this).data('id');
      $.get("{{ route('hasil.index') }}" +'/' + hasil_id +'/edit', function (data) {
          $('#modelHeading').html("Ubah hasil");
          $('#simpanBtn').val("ubah-hasil");
          $('#ajaxModel').modal('show');
          $('#hasil_id').val(data.id);
          $('#nama_hasil').val(data.nama_hasil);
          $('#kota').val(data.kota);
      })
    });

    $('#simpanBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Simpan..');
      
        $.ajax({
            data: $('#hasilForm').serialize(),
            url: "{{ route('hasil.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                if($.isEmptyObject(data.error)){
                    $('#hasilForm').trigger("reset");
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


    $('body').on('click', '.hapushasil', function () {
        var klup_id = $(this).data("id");
        if (confirm('Apakah Anda ingin menghapus data ini ?')) {
            $.ajax({
                type: "DELETE",
                url: "{{ route('hasil.store') }}"+'/'+klup_id,
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