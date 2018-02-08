var HandleSurat_Keluar = function () {
    var initTable = function () {
        var table = $('#tabel_surat_keluar_manage'),
           save_method, row,form = $('#form_surat_keluar'),form_upload = $('#form_upload_surat_keluar'),
            error = $('.alert-danger', form);        
        var oTable = table.dataTable({
                    serverSide: true,
                    processing: true,
                    searching: true,
                    ordering : false,            
                    info:true,
                    paging : true,
                    language: {
                        url: BASE_URL+"/assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                        url: BASE_URL+"umum/surat_keluar/surat_keluar_list", // ajax source
                        type: 'POST'
                    },                    
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 10, 
                    "columnDefs": [
                        { "visible": false, "targets": 0 }  
                    ],
					
        });         
		 
        $("input").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
		
        $('#new_surat_keluar').on('click',function(){
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            save_method = "tambah";
            $('#form_surat_keluar')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
			$("#nomor_register").prop('disabled',true); 
			$('#user_create').prop('readonly',true);
			$('#tanggal_surat').prop('readonly',true);
			$('#nomor_surat').prop('readonly',true);
			//$('#id_jenis_kegiatan').select2({readonly: true}); 			
			$("#instansi").change();
            $("#instansi").val('');
            $("#instansi").trigger('change'); 
			$('#simpan').prop('disabled',false);
            $('#method').val("tambah"); 
			$('#selesai').hide();			
			$('#form_surat_keluar').validate().resetForm(); 
            $('#modal_new_surat_keluar').modal('show');
            $('.modal-title .title-text').text(" Tambah Surat keluar");         
        });
        
		$('#modal_new_surat_keluar').on('click', '#simpan', function (e) {
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
                url : BASE_URL+"umum/surat_keluar/surat_keluar_save",
                type: "POST",
                dataType: 'JSON',
                data: $('#form_surat_keluar').serialize(),
                success: function(data) 
                {
                    if(data.status === true){ 
                        
                        if (save_method === "ubah"){ 
							$('#simpan').prop('disabled',false);
                            $('#modal_new_surat_keluar').modal('hide');                            
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Data Surat keluar telah di ubah', {
                                    heading:'Sukses',
                                    theme:'teal',
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);
                        }
						if (save_method === "tambah"){                   
                            //$('#modal_new_surat_keluar').modal('hide');
							$('#nomor_surat').val(data.nomor_surat); 
							$('#nomor_surat').prop('readonly',true);
							$('#modal_new_surat_keluar #simpan').hide();
							$('#modal_new_surat_keluar #batal').hide();
							$('#modal_new_surat_keluar #selesai').show();
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Data Surat keluar telah di tambah', {
                                    heading:'Sukses',
                                    theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                    }   
                                );  
                            }, 1000); 
                        }   
                    }
                    else
                    {
                        if((data.msg) && (data.status === false)){
							$('#simpan').prop('disabled',false);
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Error , '+data.msg, {
                                    heading:'Info',
                                    theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                    }
                                );
                            }, 1000);
                        }else{
							$('#simpan').prop('disabled',false);
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
		
		$('#modal_new_surat_keluar').on('click', '#selesai', function (e) { 
            e.preventDefault();
            $('#simpan').text('Simpan...');
            $('#simpan').prop('disabled',true);             
            App.blockUI({ 
                boxed: true, 
                message: 'Sedang di proses....'
            });
			
			 $('#modal_new_surat_keluar').modal('hide');                            
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Proses pendaftaran surat keluar sudah selesai', {
                                    heading:'Sukses',
                                    theme:'teal',
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);
			
			
        });    
		
        table.on('click', '#delete_surat_keluar', function (e) {
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
                                url : BASE_URL+"umum/surat_keluar/surat_keluar_delete",
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
		$('#inisiasi').on('change', function(){
            inisiasi = $('#inisiasi').val();
			if ( inisiasi == 'Internal')
                $("#nomor_register").prop('disabled',true);
            else if( inisiasi == 'Surat Masuk')
			     $("#nomor_register").prop('disabled',false);			  
        });
		$('#instansi').on('change', function(){
            id_instansi = $('#instansi').val();
            $.post(BASE_URL+'umum/surat_keluar/get_unit_kerja',{id_instansi:id_instansi}, function(data){
                if(data !== ''){
                    $("#unit_kerja").val('');
                    $("#unit_kerja").trigger("change");
                    $("#unit_kerja").html(data);
                }
            });
        });
		$('#nomor_register').on('change', function(){
            IDSurat = $('#nomor_register').val();
			$.ajax({
                    url : BASE_URL+"umum/surat_keluar/get_detail_surat_masuk",
                    type: "POST",
                    dataType: 'JSON',
    				data: {IDSurat : IDSurat},
                    success: function(data){
                        if(data !== ''){
    						$('#id_jenis_kegiatan').select2({width : 'auto'}).select2('val',data.id_jenis_kegiatan);
    					}
                    }
            });
        });		
        table.on('click', '#edit_surat_keluar', function(e) {
            e.preventDefault();
            save_method = "ubah";
            $('.form-control').parent().removeClass('has-error');
            $('.help-block').empty();
            $('.modal-title .title-text').text("Ubah Data Surat keluar");
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);

            $('#ID').val(aData[0]); 
            $('#method').val("ubah");            

            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            }); 
            $.ajax({
                url : BASE_URL+"umum/surat_keluar/get_data_surat_keluar",
                type: "POST",
                dataType: 'JSON',
                data: {ID : aData[0]},
                success: function(data)
                {
                    if(data.status === true){ 
						$('#instansi').val(data.instansi); 
                        $('#instansi').change();
                        window.setTimeout(function() {
                            App.unblockUI();
                            $('#nomor_surat').val(data.nomor_surat);
							$('#nomor_surat').prop('readonly',true);
							//$('#nomor_register').val(data.nomor_register);
							$('#nomor_register').select2({width : 'auto'}).select2('val',data.nomor_register);
							$("#unit_kerja").val(data.unit_kerja).trigger("change");                  
                            $("#unit_kerja").trigger("change");
                            //$('#inisiasi').val(data.inisiasi);
							$('#inisiasi').select2({width : 'auto'}).select2('val',data.inisiasi);
							//console.log(data.inisiasi);
							//$('#id_jenis_kegiatan').val(data.id_jenis_kegiatan);
							$('#id_jenis_kegiatan').select2({width : 'auto'}).select2('val',data.id_jenis_kegiatan);
							//$('#id_jenis_kegiatan').select2({readonly: true});
							//$('#id_jenis_kegiatan').select2({width : 'auto'}).select2('readonly',true);
							$('#user_create').val(data.user_create);  
							$('#user_create').prop('readonly',true);
							$('#tanggal_surat').val(data.tanggal_surat);  
							$('#tanggal_surat').prop('readonly',true);
							$('#jumlah_orang').val(data.jumlah_orang); 
							$('#simpan').prop('disabled',false);
							inisiasi = $('#inisiasi').val();            
							if ( inisiasi == 'Internal')
							  {
									$("#nomor_register").prop('disabled',true); 
							  }
							  else if( inisiasi == 'Surat Masuk')
							  {
									$("#nomor_register").prop('disabled',false);  
							  } 
											
                            $('#modal_new_surat_keluar').modal('show');
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
		table.on('click', '#upload_surat_keluar', function(e) {
			e.preventDefault();
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);

            $('#ID').val(aData[0]); 
            $('#method').val("upload");            
			
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            save_method = "upload";
            $('#form_upload_surat_keluar')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
			$('#method').val("upload");
			$('#modal_upload_surat_keluar').modal('show');
            $('.modal-title .title-text').text(" Upload Dokumen Surat keluar");  
        });
		
		table.on('click', '#download_surat_keluar', function(e) {
           e.preventDefault();
		   row = $(this).parents('tr')[0];
           var aData = oTable.fnGetData(row);

           App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });            
            App.unblockUI();
			$.ajax({
                url : BASE_URL+"umum/surat_keluar/get_data_surat_keluar",
                type: "POST",
                dataType: 'JSON',
                data: {ID : aData[0]},
                success: function(data)
                {
                    if(data.status === true){
						if((data.dokumen ==="")||(data.dokumen ==="nofile")||(typeof data.dokumen === 'undefined')||(data.dokumen ==="null"))
						{
							App.unblockUI();
							bootbox.alert({
								message : '<span class="font-yellow"> Mohon maaf dokumen surat keluar belum di upload.</span> <br />'+
										  ' <strong> Silahkan upload dokumen terlebih dahulu. </strong>',
								title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
							});
						}else{
							window.open(data.dokumen);  
						}
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
		
		$('#modal_upload_surat_keluar').on('click', '#simpan', function (e) { 
            e.preventDefault();
            $('#simpan').text('Simpan...');
            $('#simpan').prop('disabled',true);            
            App.blockUI({ 
                boxed: true,
                message: 'Sedang di proses....'
            });
			
			var formData = new FormData();
			formData.append('file', $('input[type=file]')[0].files[0]);
			formData.append('ID', $('#ID').val());
			
            $.ajax({
                url: BASE_URL+"umum/surat_keluar/document_save",
				type: "POST",
				data:  formData,  
				contentType: false,
				dataType: 'JSON',
				cache: false,
				processData:false,
                success: function(respon) 
                {
                    if(respon.status === true){
					       $('#modal_upload_surat_keluar').modal('hide');
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Dokumen Surat Keluar Berhasil Di Upload', {
                                    heading:'Sukses',
                                    theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                    }
                                );
                            }, 1000); 
                    } 
                    else
                    {
                        if((respon.status === false)){ 
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Error , '+respon.msg, {
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
		
		
		$('#form_surat_keluar').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input            
            rules: {                
                instansi : {
                    required : true
                },
                inisiasi : {
                    required : true
                },
                id_jenis_kegiatan : {
                    required : true
                },
                jumlah_orang : {
                    required : true
                },
            },
            messages :{
                instansi : {
                    required: 'Harap pilih salah satu',
                },
                jumlah_orang : {
                    required: 'Nomor Surat tidak boleh kosong',
                }, 
                inisiasi: { 
                    required: 'Harap pilih salah satu',
                },
                id_jenis_kegiatan: { 
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
            $('#form_surat_keluar').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
		$('input,select', form_upload).change(function () {
            $('#form_upload_surat_keluar').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
        $('#batal').on('click',function(){
            $('#form_surat_keluar').validate().resetForm();
			$('#form_upload_surat_keluar').validate().resetForm(); 
        });
		
		$('#form_upload_surat_keluar').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input            
            rules: {                
                dokumen: { 
                    required : true, 
					accept: "*/pdf"
                },
            },
            messages :{
                dokumen : {
                    required: "Dokumen tidak boleh kosong",
					accept: "Dokumen harus dalam bentuk pdf"
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
		
		$("#instansi, #unit_kerja,#nomor_register,#inisiasi").select2({
				placeholder: "Silahkan Pilih",
				dropdownAutoWidth: true,
				width : 'auto', 
				allowClear: true, 
				debug: true
		});
		
		$("#id_jenis_kegiatan").select2({
				placeholder: "Silahkan Pilih",
				dropdownAutoWidth: true,
				width : 'auto', 
				allowClear: true, 
				debug: true
		}).on('select2-selecting', function(e) {
			e.preventDefault();
			$(this).select2('close');
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
        HandleSurat_Keluar.init();
    });
}