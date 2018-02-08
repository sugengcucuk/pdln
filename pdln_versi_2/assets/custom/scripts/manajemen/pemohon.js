var HandlePemohon = function () {
    var initTable = function () {
        var table = $('#tabel_pemohon_manage'),
            save_method, row,form = $('#form_pemohon'),
            error = $('.alert-danger', form);
        var oTable = table.dataTable({
                    dom: '<"top"lf<"top_p"ip><"clear">>rt<"bottom"ip<"clear">>',
                    scrollX:true,
                    serverSide: true,
                    processing: true,
                    searching: true,
                    ordering : true,
                    info:true,
                    paging : true,
                    language: {
                        url: BASE_URL+"assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                        url: BASE_URL+"manajemen/pemohon/pemohon_list", // ajax source
                        type: 'POST'
                    },
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 10,
                    columnDefs: [
                        { visible : false, targets : 0 , orderable : false, searching : false},
                        { visible : true, targets : 7 , orderable : false, searching : false}
                    ],
        });
        $("input").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
        $('#new_pemohon').on('click',function(){
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            save_method = "tambah";
            $('#form_pemohon')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#method').val("tambah");
			$('#nik').prop("readonly",false);
			$('#nama').prop("readonly",false);
			$('#form_pemohon').validate().resetForm();
            $('#modal_new_pemohon').modal('show');
            $('.modal-title .title-text').text(" Tambah Pemohon Baru ");
        });
        $('#modal_new_pemohon').on('click', '#simpan', function (e) {
			if (form.valid() === false)
            return false;
            e.preventDefault();
            $('#simpan').text('Simpan...');
            $('#simpan').prop('disabled',true);
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            $.ajax({
                url : BASE_URL+"manajemen/pemohon/pemohon_save",
                type: "POST",
                dataType: 'JSON',
                data: $('#form_pemohon').serialize(),
                success: function(data)
                {
                    if(data.status === true){
                        if (save_method === "tambah"){
                            $('#modal_new_pemohon').modal('hide');
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Data Pemohon telah di tambah', {
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
                            $('#modal_new_pemohon').modal('hide');
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Data Pemohon telah di ubah', {
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
                        if((data.msg) && (data.status === false)){
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                bootbox.alert({
                                        message : '<span class="font-yellow"> Cek Kembali Data Yang Anda Masukkan!!!</span> <br />'+
                                                  ' <strong> '+ data.msg +' </strong>',
                                        title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Gagal!! </strong><span>'
                                    });
                            }, 1000);
                        }else{
                            App.unblockUI();
                            for (var i = 0; i < (data.inputerror).length; i++)
                            {
                                $('[name="'+data.inputerror[i]+'"]')
                                    .parent().parent()
                                    .addClass('has-error');
                                $('[name="'+data.inputerror[i]+'"]')
                                    .next()
                                    .text(data.error_string[i]);
                            }
                        }
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
        table.on('click', '#delete_pemohon', function (e) {
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
                                url : BASE_URL+"manajemen/pemohon/pemohon_delete",
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
        table.on('click', '#edit_pemohon', function(e) {
            e.preventDefault();
            save_method = "ubah";
            $('.form-control').parent().removeClass('has-error');
            $('.help-block').empty();
            $('.modal-title .title-text').text("Ubah Data Pemohon");
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);
			$('#form_pemohon').validate().resetForm();
            $('#ID').val(aData[0]);
            $('#method').val("ubah");


            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            $.ajax({
                url : BASE_URL+"manajemen/pemohon/get_data_pemohon",
                type: "POST",
                dataType: 'JSON',
                data: {ID : aData[0]},
                success: function(data)
                {
                    if(data.status === true){
                        window.setTimeout(function() {
                            App.unblockUI();
                            $('#nama').val(data.nama);
							$('#nama').prop("readonly",true);
							$('#nik').val(data.nik);
							$('#nik').prop("readonly",true);
							$('#nip_nrp').val(data.nip_nrp);
							$('#jabatan').val(data.jabatan);
							$('#email').val(data.email);
							$('#instansi').val(data.instansi);
                            $("#instansi").trigger("change");
							$('#instansi_lainnya').val(data.instansi_lainnya);
							$('#telp').val(data.telp);
							$("input[name=opt_status][value="+data.is_active+"]").prop('checked', true);
                            $('#modal_new_pemohon').modal('show');
                            }, 1000);
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

		$('#instansi').on('change', function(){
			$('#instansi_lainnya').prop("value",'',true);
			$('#instansi_lainnya').prop("readonly",true);
			if($(this).val() === '0'){
				$('#instansi_lainnya').prop("readonly",false);
			}
		});

		$('#instansi').select2({
			placeholder: 'Pilih Instansi',
			//minimumInputLength: 3,
			dropdownAutoWidth: false,
			width : '500',
			allowClear: true
		});

		 $('#form_pemohon').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                nik : {
                    required : true
                },
                nama : {
                    required : true
                },
                jabatan : {
                    required : true
                },
                email : {
                    required : true
                },
                telp : {
                    required : true
                },
                opt_status: {
                    required : true
                },
				instansi : {
					required : true
				}
            },
            messages :{

                nama : {
                    required: "Nama Pemohon tidak boleh kosong",
                },
                nik : {
                    required: "NIK tidak boleh kosong",
                },
                jabatan : {
                    required: "Jabatan tidak boleh kosong",
                },
				email : {
                    required: "Email tidak boleh kosong",
                },
				telp : {
                    required: "Telepon tidak boleh kosong",
                },
                opt_status: {
                    required: 'Harap pilih salah satu',
                },
				instansi : {
					required : "Harap Pilih Instansi "
				}
            },
            onkeyup: function(element,event) {
                if ($(element).prop('name') === "name") {
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
            $('#form_pemohon').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
        $('#batal').on('click',function(){
            $('#form_pemohon').validate().resetForm();
        });


    };
    return {
        //main function to initiate the module
        init: function () {
            initTable();
        }
    };
}();
if (App.isAngularJsApp() === false) {
    jQuery(document).ready(function() {
        HandlePemohon.init();
        $('#btn_m_upload').on('click',function(e){
            e.preventDefault();
            $('#mass_upload').modal('show');
        });
        $('#btn_m_download').on('click',function(e){
            e.preventDefault();
            window.location.href=BASE_URL+"/assets/template/pdln_template_pemohon_01.csv";
        });
    });
}
$('#fileUpload').fileinput({
    uploadUrl: BASE_URL+"/manajemen/pemohon/process_mass_upload_pemohon",
    allowedFileExtensions : ['csv'],
    showPreview: false,
    uploadAsync: true,
    maxFileCount: 1,
});
$('#fileUpload').on('fileuploaderror', function(event, data, msg) {
    var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
    console.log('File upload error :'+msg);
});

$('#fileUpload').on('fileuploaded', function(event, data, previewId, index) {
    var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
    console.log('File uploaded triggered');
});

$('#fileUpload').on('filebatchpreupload', function(event, data) {
    var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
    console.log('File batch pre upload');
});

$('#fileUpload').on('filebatchuploadcomplete', function(event, files, extra) {
    console.log('File batch upload complete');
    $("#tabel_pemohon_manage").DataTable().ajax.reload();
    $('#mass_upload').modal('hide');
});

$('#fileUpload').on('change', function(event) {
    var filename=$(this).val();
    if(filename.split('.').pop()!='csv'){
        $(this).fileinput('reset');
        bootbox.alert({
            message: '<span class="font-yellow"> Masukkan hanya file dengan ekstensi <b>.csv</b>.</span>',
            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
        });
    }
});