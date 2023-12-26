<!DOCTYPE html>
<html>
<head>
    <title>Laravel Bola</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>  
    <div class="container">
        <form action="{{ url('/simpan-hasil') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container" style="margin-top: 50px">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center">Form Multiple</h4>
                        <div class="card border-0 shadow-sm rounded-md mt-4">
                            <div class="card-body">

                                <table class='table table-bordered' id='TabelHasil'>
                                    <thead>
                                        <tr>
                                            <th style='width:35px;'>#</th>
                                            <th>Klub A</th>
                                            <th>VS</th>
                                            <th>Klub B</th>
                                            <th>Score A</th>
                                            <th>Vs</th>
                                            <th>Score B</th>
                                            <th style='width:90px;'></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                                <div class='alert alert-info TotalBayar'>
                                    <button id='BarisBaru' class='btn btn-warning pull-left' style="margin-top: -8px;"><i class='fa fa-plus fa-fw'></i> Baris Baru</button>
                                    <button type='submit' id="saveBtn" class='btn btn-danger pull-left' style="margin-top: -8px; margin-left: 10px;" >
                                        <i class='fa fa-floppy-o'></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

<script>
    function check_int(evt) {
        var charCode = ( evt.which ) ? evt.which : event.keyCode;
        return ( charCode >= 48 && charCode <= 57 || charCode == 8 );
    }

    $(document).ready(function(){
        for(B=1; B<=1; B++){
            BarisBaru();
        }

        $('#BarisBaru').click(function(){
            BarisBaru();
        });


        $("#TabelHasil tbody").find('input[type=text],textarea,select').filter(':visible:first').focus();
    });

    function BarisBaru()
    {
        var Nomor = $('#TabelHasil tbody tr').length + 1;
        var Baris = "<tr>";
            Baris += "<td>"+Nomor+"</td>";
            Baris += "<td>";
                Baris += "<input type='hidden' id='id_klub_a'  name='id_klub_a[]' value=''>";
                Baris += "<select required name='klub_a[]' id='klub_a' class='form-control select2' data-placeholder='Pilih Klub 1'>";
                Baris += "<option value=''>Pilih Klub A</option>";
                @foreach ($klub as $row)
                    Baris += "<option value='{{ $row->id }}'>{{ $row->nama_klub }}</option>";
                @endforeach 
                Baris += "</select>";
            Baris += "</td>";
            Baris += "<td>";
                Baris += "X";
            Baris += "</td>";
            Baris += "<td>";
                Baris += "<input type='hidden' id='id_klub_b'  name='id_klub_b[]' value=''>";
                Baris += "<select required name='klub_b[]' id='klub_b' class='form-control select2' data-placeholder='Pilih Klub 2'>";
                Baris += "<option value=''>Pilih Klub B</option>";
                @foreach ($klub as $row)
                    Baris += "<option value='{{ $row->id }}'>{{ $row->nama_klub }}</option>";
                @endforeach 
                Baris += "</select>";
            Baris += "</td>";
            Baris += "<td><input required type='text' class='form-control' name='score_a[]' id='score_a' onkeypress='return check_int(event)'></td>";
            Baris += "<td><span>X</span></td>";
            Baris += "<td><input required type='text' class='form-control' name='score_b[]' id='score_b' onkeypress='return check_int(event)'></td>";
            Baris += "<td>";
                Baris += "<button class='btn btn-circle  btn-info  btn-sm' id='HapusBaris'><i class='fa fa-times' style='color:red;'></i></button>";
            Baris += "</td>";
            Baris += "</tr>";

        $('#TabelHasil tbody').append(Baris);

        $('#TabelHasil tbody tr').each(function(){
            $(this).find('td:nth-child(2) input').focus();
        });
    }

    $(document).on('click', '#HapusBaris', function(e){
        e.preventDefault();
        $(this).parent().parent().remove();

        var Nomor = 1;
        $('#TabelHasil tbody tr').each(function(){
            $(this).find('td:nth-child(1)').html(Nomor);
            Nomor++;
        });
    });

    $(document).on('change', '#klub_a', function(){
        var Indexnya = $(this).parent().parent().index();
        
        $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(2) input').val($(this).val());

        var klub_a = $(this).val();
        var klub_b = $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(4) input').val();
       
        if (!klub_b) {
            console.log("klub b kosong");
        } else if (klub_a == klub_b) {
            $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(2) input').val("");
            $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(2) select').val("");
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
                        $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(2) input').val("");
                        $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(2) select').val("");
                        alert(data.message);
                    }
                }
            })
        }
    });

    $(document).on('change', '#klub_b', function(){
        var Indexnya = $(this).parent().parent().index();
        
        $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(4) input').val($(this).val());

        var klub_b = $(this).val();
        var klub_a = $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(2) input').val();

        if (!klub_b) {
            console.log("klub a kosong");
        } else if (klub_b == klub_a) {
            $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(4) input').val("");
            $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(4) select').val("");
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
                        $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(4) input').val("");
                        $('#TabelHasil tbody tr:eq('+Indexnya+') td:nth-child(4) select').val("");
                        alert(data.message);
                    }
                }
            })
        }
    });
</script>