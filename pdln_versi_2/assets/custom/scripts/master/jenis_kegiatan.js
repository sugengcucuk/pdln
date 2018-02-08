var HandleJenisKegiatan = function () {
    var initTable = function () {
        var table = $('#tabel_jenis_kegiatan_manage'),
            save_method, row,form = $('#form_jenis_kegiatan'),
            error = $('.alert-danger', form);            
        var oTable = table.dataTable({
            serverSide: true,
            processing: false,
            searching: true,
            ordering: true,
            info: true,
            paging: true,
            language: {
                url: BASE_URL + "assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
            },
            ajax: {
                url: BASE_URL + "master/kegiatan/jenis_kegiatan_list", // ajax source
                type: 'POST'
            },
            lengthMenu: [
                [5, 10, 20, 50, -1],
                [5, 10, 20, 50, "Semua"] // change per page values here
            ],
            pageLength: 10,
            "columnDefs": [{
                    "visible": false,
                    "targets": 0,
                    "name": "r_jenis_kegiatan.ID"
                },
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": 1
                },
                {
                    "name": "r_jenis_kegiatan.Nama",
                    "targets": 2
                },
                {
                    "name": "r_subkategori_kegiatan.Nama",
                    "targets": 3
                },
                {
                    "name": "r_kategori_kegiatan.Nama",
                    "targets": 4
                },
                {
                    "name": "r_jenis_kegiatan.Kodifikasi",
                    "targets": 5
                },
                {
                    "name": "r_jenis_kegiatan.Status",
                    "targets": 6
                },
                {
                    "targets": 7,
                    "searchable": false,
                    "orderable": false
                }
            ],
        });
        $('#reload_data').on('click',function(e){
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });     
            window.setTimeout(function() {
                oTable.api().ajax.reload();
                App.unblockUI();    
            },200);
        });
        
        $('#Kategori').on('change', function(){
            id_kategori = $('#Kategori').val();            
            $.post(BASE_URL+'master/kegiatan/get_sub_kategori',{id_kategori:id_kategori}, function(data){
                if(data !== ''){
                    $("#SubKategori").val('');
                    $("#SubKategori").trigger("change");
                    $("#SubKategori").html(data);
                }
            });
        });        
        $('#new_jenis_kegiatan').on('click',function(){
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            save_method = "tambah";
            $('#form_jenis_kegiatan')[0].reset();
            $("#Kategori").change();
            $("#Kategori").val('');
            $("#Kategori").trigger('change');
            $('#Nama').prop("readonly",false);            
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#method').val("tambah");
            $('#form_jenis_kegiatan').validate().resetForm();
            $('#modal_new_jenis_kegiatan').modal('show');
            $('.modal-title .title-text').text(" Tambah Jenis Kegiatan Baru");
            App.unblockUI(); 
        });
        $('#modal_new_jenis_kegiatan').on('click', '#simpan', function (e) { 
            e.preventDefault();
            $('#simpan').text('Simpan...');
            $('#simpan').prop('disabled',true);            
            App.blockUI({ 
                target : "#form_jenis_kegiatan",
                overlayColor:"none",
                animate:!0
            });            
            if($('#form_jenis_kegiatan').valid()){
                $.ajax({
                    url : BASE_URL+"master/kegiatan/jenis_kegiatan_save",
                    type: "POST",
                    dataType: 'JSON',
                    data: $('#form_jenis_kegiatan').serialize(),
                    success: function(data)
                    {
                        if(data.status === true){ 
                            if (save_method === "tambah"){                                
                                window.setTimeout(function() {                                    
                                    $('#modal_new_jenis_kegiatan').modal('hide');
                                    App.unblockUI("#form_jenis_kegiatan");
                                    oTable.api().ajax.reload();
                                    $.notific8('Data Jenis Kegiatan telah di tambah', {
                                        heading:'Sukses',
                                        theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                        life: 2000,
                                        horizontalEdge: 'bottom',
                                        verticalEdge: 'left'
                                        }
                                    );
                                }, 1000);
                            }
                            if (save_method === "ubah"){                                
                                window.setTimeout(function() {
                                    $('#modal_new_jenis_kegiatan').modal('hide');
                                    App.unblockUI("#form_jenis_kegiatan");
                                    oTable.api().ajax.reload();                                    
                                    $.notific8('Data Jenis Kegiatan telah di ubah', {
                                        heading:'Sukses',
                                        theme:'teal',
                                        life: 2000,
                                        horizontalEdge: 'bottom',
                                        verticalEdge: 'left'
                                    });
                                }, 1000);
                            }
                        }
                        else
                        {
                            if((data.msg !== '') && (data.status === false)){                                
                                window.setTimeout(function() {                                    
                                    $('#modal_new_jenis_kegiatan').modal('hide');
                                    App.unblockUI("#form_jenis_kegiatan");
                                    oTable.api().ajax.reload();
                                    $.notific8('Error , '+data.msg, {
                                        heading:'Info',
                                        theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                        life: 2000,
                                        horizontalEdge: 'bottom',
                                        verticalEdge: 'left'
                                        }
                                    );
                                }, 1000);
                            }
                        }
                        $('#simpan').text('Simpan');
                        $('#simpan').prop('disabled',false);
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        App.unblockUI("#form_jenis_kegiatan");
                        bootbox.alert({
                            message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                                      ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                            title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                        }); 
                        $('#simpan').text('Simpan');
                        $('#simpan').prop('disabled',false);
                    }
                });
            }else{
                App.unblockUI("#form_jenis_kegiatan");
                $('#simpan').text('Simpan');
                $('#simpan').prop('disabled',false);
            }
        });
        table.on('click', '#delete_jenis_kegiatan', function (e) {
            e.preventDefault();
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);
            bootbox.dialog({
                message: "Apakah anda yakin untuk menghapus data ?",
                    title: "Hapus Data",
                    buttons: {
                      success: {
                        label: "Hapus",
                        className: "btn-danger",
                        callback: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Sedang di proses....'
                            });
                            $.ajax({
                                url : BASE_URL+"master/kegiatan/jenis_kegiatan_delete",
                                type: "POST",
                                dataType: 'JSON',
                                data: {ID : aData[0]},
                                success: function(data)
                                {
                                    // another theme : teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    if(data.success){
                                        window.setTimeout(function() {
                                            oTable.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8('Data Terhapus', {
                                                heading:'Info',
                                                theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                                life: 2000,
                                                horizontalEdge: 'bottom',
                                                verticalEdge: 'left'
                                                }
                                            );
                                        }, 1000); 
                                    }else{
                                        window.setTimeout(function() {
                                            oTable.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8('Error , Data Tidak dapat Terhapus', {
                                                heading:'Info',
                                                theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                                life: 2000,
                                                horizontalEdge: 'bottom',
                                                verticalEdge: 'left'
                                                }
                                            );
                                        }, 1000);
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrowns)
                                {
                                    App.unblockUI();
                                    bootbox.alert({
                                        message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                                                  ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                                        title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                                    });
                                }
                            });  
                        }
                      },
                      main: {
                        label: "Batal",
                        className: "blue"                        
                      }
                    }
            });
        });
        $.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
            options.async = true;
        });
        table.on('click', '#edit_jenis_kegiatan', function(e) {
            e.preventDefault();
            save_method = "ubah";
            $('.modal-title .title-text').text("Ubah Data Jenis Kegiatan");
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);
            
            $('#ID').val(aData[0]);
            $('#method').val("ubah");

            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
			
			$.ajax({
				url : BASE_URL+"master/kegiatan/get_doc_req_kegiatan",
                type: "POST",
                dataType: 'JSON',
                data: {ID : aData[0]},
                success: function(data)
                {
                     $.each(data, function(index) { 
						$(':checkbox[id="kegiatan'+data[index].IDJenisDokumen+'required"]').prop('checked', true);
						if(data[index].Required==1){$(':checkbox[id="kegiatan'+data[index].IDJenisDokumen+'mandatory"]').prop('checked', true);}else{$(':checkbox[id="kegiatan'+data[index].IDJenisDokumen+'mandatory"]').prop('checked', false);}
					});
                }  
            });
			
			$.ajax({
				url : BASE_URL+"master/kegiatan/get_doc_req_pemohon", 
                type: "POST",
                dataType: 'JSON',
                data: {ID : aData[0]},
                success: function(data)
                {
                     $.each(data, function(index) { 
						$(':checkbox[id="pemohon'+data[index].IDJenisDokumen+'required"]').prop('checked', true);
						if(data[index].Required==1){$(':checkbox[id="pemohon'+data[index].IDJenisDokumen+'mandatory"]').prop('checked', true);}else{$(':checkbox[id="pemohon'+data[index].IDJenisDokumen+'mandatory"]').prop('checked', false);}
					});
                }  
            });
			
            $.ajax({
                url : BASE_URL+"master/kegiatan/get_data_jenis_kegiatan",
                // async: false, // set false async to handle change function priority ?, if true make possible paralel run method                
                type: "POST",
                dataType: 'JSON',
                data: {ID : aData[0]},
                success: function(data)
                {
                    if(data.status === true){ 
                        $('#Kategori').val(data.Kategori);
                        $('#Kategori').trigger("change");
                        $('#Kategori').change(); 
                            window.setTimeout(function() {
                                App.unblockUI();
                                $("#SubKategori").val(data.SubKategori).trigger("change");                  
                                $("#SubKategori").trigger("change");
								$('#Kodifikasi').val(data.Kodifikasi);
                                $('#Nama').val(data.Nama);
                                $('#Nama').prop("readonly",true);                       
                                $("input[name=opt_status][value="+data.is_active+"]").prop('checked', true);
                                $('#modal_new_jenis_kegiatan').modal('show');
                                },2000);
                    }else{
                        App.unblockUI();
                        bootbox.alert({
                            message : '<span class="font-yellow"> Mohon maaf data bermasalah.</span> <br />'+
                                      ' <strong> Sistem tidak dapat menarik informasi dari database. </strong>',
                            title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                        }); 
                    }
                    $('#simpan').text('Simpan');
                    $('#simpan').prop('disabled',false);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    App.unblockUI();
                    bootbox.alert({
                        message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                                  ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                        title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                    }); 
                    $('#simpan').text('Simpan');
                    $('#simpan').prop('disabled',false);
                }
            });
        });
        
        $("#Kategori, #SubKategori").select2({
            placeholder: "Silahkan Pilih",
            dropdownAutoWidth: true,
            width : 'auto',
            allowClear: true,
            debug: true
        });        
        $('#form_jenis_kegiatan').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input            
            rules: {                
                Kategori : {
                    required : true
                },
                SubKategori : {
                    required : true
                },
                Nama : {
                    required : true
                },
                opt_status: {
                    required : true
                },
            },
            messages :{
                Kategori : {
                    required: 'Harap pilih salah satu',
                },
                SubKategori : {
                    required: 'Harap pilih salah satu',
                },
                Nama : {
                    required: "Nama Jenis Kegiatan tidak boleh kosong",
                },
				opt_status: {
                    required: 'Harap pilih salah satu',
                },                
            },
            onkeyup: function(element,event) {
                if ($(element).prop('name') === "Name") {
                    return false; // disable for your element named as "name"
                } else { // else use the default on everything else
                    if ( event.which === 9 && this.elementValue( element ) === "" ) {
                        return;
                    } else if ( element.name in this.submitted || element === this.lastElement ) {
                        this.element( element );
                    }
                }
            },            
            invalidHandler: function(event, validator) { //display error alert on form submit                
                error.fadeTo(3000, 500).slideUp(500, function(){});
                    App.scrollTo(error, -200);                    
            },
            errorPlacement: function(error, element) {
                if (element.is(':radio')) {
                    error.insertAfter(element.closest(".mt-radio-inline"));
                }else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },          
        });
        $('input,select', form).change(function () {
            $('#form_jenis_kegiatan').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
        $('#batal').on('click',function(){
            $('#form_jenis_kegiatan').validate().resetForm();
        });
    };  
    return {
        //main function to initiate the module
        init: function () {            
            initTable();
        }
    };
}();
jQuery(document).ready(function() {
    HandleJenisKegiatan.init();
});