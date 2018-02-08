var HandleAddRealisasi = function () {
    var initForm = function () {
        var form = $('#form_add_realisasi'),
           save_method, row,error = $('.alert-danger', form);
		var id_surat = 0;
		
		var table = $('#table_list_permohonan');
        var oTable = table.dataTable({
            destroy : true,
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
                url: BASE_URL+"layanan/realisasi/list_permohonan", // ajax source
                type: 'POST'
            },
            lengthMenu: [
                [5],
                [5]
            ],
            pageLength: 5, 
            columnDefs: [
                { visible: false, targets: 0 , orderable: false , searchable : false},
                { visible: true, targets: 9 , orderable: false , searchable : false}
            ],
        });
		
		
		//Pulsate : Tombol cari list permohonan
        if (!jQuery().pulsate) {
            return;
        }
        
		if (App.isIE8() === true) {
			return; // pulsate plugin does not support IE8 and below
		}

		if (jQuery().pulsate) {
			$('#find_surat').pulsate({
				color: "#E43A45",
				repeat: 5,
                speed: 800,
                glow: true
            });
        }
        
		
		$('#view_surat').prop("disabled",true);
		$('#file_laporan_kegiatan').prop("disabled",true);
		$('#biaya').prop("disabled",true);
		//$('#file_laporan_kegiatan').fileinput('disable');
		
		$('#kembali').on('click', function(e) {
			e.preventDefault();
            App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });            
            App.unblockUI();
			window.location.href =BASE_URL+"layanan/realisasi";
        });
		
		$('#biaya').inputmask("numeric", {
			radixPoint: ".",
			groupSeparator: ",",
			digits: 2,
			autoGroup: true,
			prefix: 'Rp ', //Space after $, this will not truncate the first character.
			rightAlign: false,
			oncleared: function () { self.Value(''); }
		});
		
		$('#view_surat').on('click', function(e) { 
			e.preventDefault();
            App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });            
            App.unblockUI();
			
			if(id_surat === 0){ 
                window.setTimeout(function() {                                    
                    App.unblockUI("#form_add_realisasi");
							bootbox.alert({
							message : '<span class="font-yellow"> Nomor Registrasi Surat Belum Di Tentukan.</span> <br />'+ "Silahkan Isi Nomor Registrasi Surat" ,
							title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
					}); 
                }, 1000);
            }
            else 
            {
				var pdf_url;
                $.ajax({
                    url : BASE_URL+"layanan/realisasi/get_sp_path/"+id_surat,
                    dataType : "json",
                    type : "post",
                    async : false,
                    success : function(data){
                        if(data.status === true){
                            pdf_url = data.path_sp;                        
                            $.fancybox({
                                href : pdf_url,
                                type : 'iframe',
                                title : "Surat Persetujuan",
                                autoCenter :true,           
                                fitToView   : false,
                                width       : '80%',
                                height      : '80%',            
                                autoSize    : false,
                                maxWidth    : 800,
                                maxHeight   : 700,
                                iframe : {
                                    preload: true,
                                    scrolling : 'auto'
                                }
                            });
                        }else{
                            bootbox.alert({
                                message : '<span class="font-yellow uppercase bold "> File SP Tidak ada.</span> <br />'+ "Silahkan Hubungi Helpdesk...!!" ,
                                title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                        });
                            return false;
                        }
                    }
                });                
			return false;
            } 
		});
		
		$('#cari_surat').on('click', function(e) {
			e.preventDefault();
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });            
			window.setTimeout(function(){
                App.unblockUI();
                 oTable.fnDraw();
                $('#modal_list_permohonan').modal('show');
                $('modal_list_permohonan .modal-title .title-text').text(" List Data Permohonan");
            },800);
        });
		
		/*
		$('#cari_surat').on('click', function(e) {
			e.preventDefault();
            App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });            
            App.unblockUI();
			
			
			$.ajax({
                    url : BASE_URL+"layanan/realisasi/cari_surat",
                    type: "POST",
                    dataType: 'JSON',
                    data: {ID : $('#nomor_surat').val()},
                    success: function(data)
                    {
                        if(data.status === true){ 
                            window.setTimeout(function() {
                                App.unblockUI("#form_add_realisasi");
                                id_surat = data.ID;
								$('#view_surat').prop("disabled",false);
								$('#file_laporan_kegiatan').prop("disabled",false);
								$('#biaya').prop("disabled",false); 		 						
								$("#file_laporan_kegiatan").fileinput({
									'language' : 'id',
									'showPreview' : true,
									'allowedFileExtensions' : ['pdf'],
									'elErrorContainer': '#errorBlock',
									'maxFileSize': 10000, 
									'maxFilesNum': 1,
									'uploadAsync': false,
									'showUpload':true,
									'enable':true,
									'dropZoneEnabled':false,
									'uploadLabel': 'Submit Laporan', 			
									'uploadUrl': BASE_URL+"layanan/realisasi/upload_laporan", 
									uploadExtraData: function() {
										return {
											id_surat_keluar : id_surat,
											biaya : Number($('#biaya').val().replace(/[^0-9\.]+/g,""))
										};
									}
								});
								
								
								$.notific8('Nomor Registrasi Surat Tersedia', {
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
                            if((data.msg !== '') && (data.status === false)){
                                window.setTimeout(function() {                                    
                                App.unblockUI("#form_add_realisasi");
									bootbox.alert({
										message : '<span class="font-yellow"> Nomor Surat Salah.</span> <br />'+ data.msg ,
										title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
									}); 
                                }, 1000);
                            }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        App.unblockUI("#form_add_realisasi");
                        bootbox.alert({
                            message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                                      ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                            title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                        }); 
                    }
                });
			
        });
		
		*/ 
		
		table.on('click', '#btn_set_surat', function(e) {
            e.preventDefault();
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);
			
            App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });            
            App.unblockUI();
			id_surat = aData[0];
			$('#modal_list_permohonan').modal('hide');
			$('#view_surat').prop("disabled",false);
			$('#file_laporan_kegiatan').prop("disabled",false);
			$('#biaya').prop("disabled",false); 		 						
			$("#file_laporan_kegiatan").fileinput({
				'language' : 'id',
				'showPreview' : true,
				'allowedFileExtensions' : ['pdf'],
				'elErrorContainer': '#errorBlock',
				'maxFileSize': 10000, 
				'maxFilesNum': 1,
				'uploadAsync': false,
				'showUpload':true,
				'enable':true,
				'dropZoneEnabled':false,
				'uploadLabel': 'Submit Laporan', 			
				'uploadUrl': BASE_URL+"layanan/realisasi/upload_laporan", 
				uploadExtraData: function() {
					return {
						id_pdln : id_surat,
						biaya : Number($('#biaya').val().replace(/[^0-9\.]+/g,""))
					};
				}
			});
								 
			$.notific8('Nomor Registrasi Surat Tersedia', {
                heading:'Sukses',
                theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                life: 2000,
                horizontalEdge: 'bottom',  
                verticalEdge: 'left'
            });
        }); 
		
		
		$('#file_laporan_kegiatan').on('filebatchuploadsuccess', function(event, data, previewId, index) {
		   var form = data.form, files = data.files, extra = data.extra, 
			response = data.response, reader = data.reader;
			
			if(response.status === true){ 
                window.setTimeout(function() {
                    App.unblockUI("#form_add_realisasi");
					$.notific8('Laporan PDLN Berhasil Diupload', {
                        heading:'Sukses',
                        theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                        life: 2000,
                        horizontalEdge: 'bottom',  
                        verticalEdge: 'left'
						}
                    );
                }, 1000);
				window.location.href =BASE_URL+"layanan/realisasi";
            }else
            {
                if((response.msg !== '') && (response.status === false)){
                    window.setTimeout(function() {                                    
                        App.unblockUI("#form_add_realisasi");
						bootbox.alert({
							message : '<span class="font-yellow"> Gagal Mengupload Dokumen Laporan.</span> <br />'+ data.msg ,
							title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
						}); 
                    }, 1000);
                }
            }
		});  
   };  
    return {
        //main function to initiate the module
        init: function () {            
            initForm();
        }
    };
}();

if (App.isAngularJsApp() === false) { 
    jQuery(document).ready(function() {
        HandleAddRealisasi.init();
    });
}