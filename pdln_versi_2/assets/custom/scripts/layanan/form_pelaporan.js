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
        
		
		$('#view_surat').prop("disabled",false);
		// $('#file_laporan_kegiatan').prop("disabled",true);
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
		
		
		
		$('#view_surat').on('click', function(e) { 
			e.preventDefault();
            App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });            
            App.unblockUI();
			var id_surat = $('#id_pdln').val();
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
        $('#sudah_dilaporkan').on('click', function(e) {
            e.preventDefault();
            // App.blockUI({
            //     boxed: true,
            //     message: 'Sedang di proses....'
            // });    
            var data_realisasi = $('#form_add_realisasi').serialize();


            var kegiatan_name = $('#kegiatan_name').val();
            var tujuan_kegiatan = $('#tujuan_kegiatan').val();
            var materi_kegiatan = $('#materi_kegiatan').val();
            var tindak_lanjut = $('#tindak_lanjut').val();
            var counter_peserta = $('#counter_peserta').val();
            var rekom = $('#rekom').val();
            var file_laporan_kegiatan = $('#file_laporan_pdf').val();
            var msg = '';
            var is_do =0;
            var is_send =0;
            var head = "Mohon Untuk isi-an";
            if (kegiatan_name === '' || kegiatan_name ==='undefined') {
                msg = 'Diwajibkan untuk mengisi kegiatan';
                is_do =1;
            }else if (tujuan_kegiatan === '' || tujuan_kegiatan ==='undefined') {
                msg = 'Diwajibkan untuk mengisi Tujuan Kegiatan';
                is_do =1;
            }else if (counter_peserta < 1) {
                head = "Peserta Wajib Ada";
                msg = 'Jumlah Peserta Tidak boleh kosong , Silakan di arsipkan !!';
                is_do =1;
            }else if (materi_kegiatan === '' || materi_kegiatan ==='undefined') {
                msg = 'Diwajibkan untuk mengisi Materi Kegiatan ';
                is_do =1;
            }else if (tindak_lanjut === '' || tindak_lanjut ==='undefined') {
                msg = 'Diwajibkan untuk mengisi Tindak Lanjut';
                is_do =1;
            }else if (rekom === '' || rekom ==='undefined') {
                msg = 'Diwajibkan untuk mengisi Dampak & Rekomendasi ';
                is_do =1;
            }else if (file_laporan_kegiatan === '' || file_laporan_kegiatan ==='undefined') {
                msg = 'Diwajibkan untuk mengisi Dokumen Laporan Kegiatan';
                is_do =1;
            }else{
                head = "Terima kasih"
                msg = 'Mohon Menunggu Laporan Anda sedang di proses !!';
                is_do =1;
                is_send =3;
            }
            // $('#validasi_lapor').htlm('<b style="color:red;">'+msg+'</b>')
            if (is_do === 1) {
                App.unblockUI("#form_add_realisasi");
                    bootbox.alert({
                    message : '<span class="font-blue">'+msg+'</span>' ,
                    title   : '<span class="font-blue bold"> <strong> <i class="fa fa-primary"> </i> '+head+'</strong><span>' 
                });
                is_do = 3;
            }
            if (is_send === 3) {
                $.ajax({
                    url : BASE_URL+"layanan/realisasi/reported",
                    type: "POST",
                    dataType: 'JSON',
                    data : $('#form_add_realisasi').serialize(),

                    success: function(data)
                    {
                        App.unblockUI();
                        oTable.fnDraw();
                            if(data.status === true){
                                
                                window.setTimeout(function() { 
                                    window.location.reload();
                                }, 200); 

                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        // App.unblockUI("#form_add_realisasi");
                        // bootbox.alert({
                        //     message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                        //               ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                        //     title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                        // }); 
                    }
                });
                window.setTimeout(function() { 
                    $.ajax({
                        url : BASE_URL+"layanan/realisasi/go_reported",
                        type: "POST",
                        dataType: 'JSON',
                        data : $('#form_add_realisasi').serialize(),
                        success: function(data)
                        {
                            window.location.reload();                                            
                            if (data.status=== true) {
                                App.unblockUI("#form_add_realisasi");
                                bootbox.alert({
                                    message : '<span class="font-green"> Pelaporan telah dikirimkan </span> <br />'+
                                              'Silakan review <strong> Terimakasih </strong>',
                                    title   : '<span class="font-red bold"> <strong> <i class="fa fa-succes"> </i> Berhasil </strong><span>' 
                                }); 

                            }

                            // DO after LAPOR
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
                }, 500); 
            }
        });


        $('#laporkan').on('click', function(e) {
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });    
            var data_realisasi = $('#form_add_realisasi').serialize();
         
            $.ajax({
                    url : BASE_URL+"layanan/realisasi/reported",
                    type: "POST",
                    dataType: 'JSON',
                    data : $('#form_add_realisasi').serialize(),

                    success: function(data)
                    {
                        App.unblockUI();
                        oTable.fnDraw();
                            if(data.status === true){
                                window.setTimeout(function() { 
                                    window.location.reload();
                                }, 200); 

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
                     id_pdln : $('#id_pdln').val(),
                     biaya : 22 //Number($('#biaya').val().replace(/[^0-9\.]+/g,""))
                 };
             }
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
		
		// table.on('click', '#btn_set_surat', function(e) {
  //           e.preventDefault();
  //           row = $(this).parents('tr')[0];
  //           var aData = oTable.fnGetData(row);
			
  //           App.blockUI({
  //                           boxed: true,
  //                           message: 'Sedang di proses....'
  //                       });            
  //           App.unblockUI();
		// 	id_surat = aData[0];
		// 	$('#modal_list_permohonan').modal('hide');
		// 	$('#view_surat').prop("disabled",false);
		// 	$('#file_laporan_kegiatan').prop("disabled",false);
		// 	$('#biaya').prop("disabled",false); 		 						
		// 	$("#file_laporan_kegiatan").fileinput({
		// 		'language' : 'id',
		// 		'showPreview' : true,
		// 		'allowedFileExtensions' : ['pdf'],
		// 		'elErrorContainer': '#errorBlock',
		// 		'maxFileSize': 10000, 
		// 		'maxFilesNum': 1,
		// 		'uploadAsync': false,
		// 		'showUpload':true,
		// 		'enable':true,
		// 		'dropZoneEnabled':false,
		// 		'uploadLabel': 'Submit Laporan', 			
		// 		'uploadUrl': BASE_URL+"layanan/realisasi/upload_laporan", 
		// 		uploadExtraData: function() {
		// 			return {
		// 				id_pdln : id_surat,
		// 				biaya : Number($('#biaya').val().replace(/[^0-9\.]+/g,""))
		// 			};
		// 		}
		// 	});
								 
		// 	$.notific8('Nomor Registrasi Surat Tersedia', {
  //               heading:'Sukses',
  //               theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
  //               life: 2000,
  //               horizontalEdge: 'bottom',  
  //               verticalEdge: 'left'
  //           });
  //       }); 

            $('#tanggal_pelaksana').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: " s/d ",
                    applyLabel: "OK",
                    cancelLabel: "Batal",
                    weekLabel: "M",
                    daysOfWeek: [
                        "Ming",
                        "Sen",
                        "Sel",
                        "Ra",
                        "Kam",
                        "Jum",
                        "Sab"
                    ],
                    monthNames: [
                        "Januari",
                        "Februari",
                        "Maret",
                        "April",
                        "Mei",
                        "Juni",
                        "Juli",
                        "Agustus",
                        "September",
                        "Oktober",
                        "November",
                        "Desember"
                    ],
                },
                startDate: $('#tgl_awal').val(),
                endDate: $('#tgl_akhir').val(),
                applyClass: "btn-primary"
            }, 
            function(start, end, label) {
                $('#tanggal_pelaksana').val(start.format('DD-MM-YYYY') + ' s/d ' + end.format('DD-MM-YYYY'));
                $('#StartDate').val(start.format('YYYY-MM-DD'));
                $('#EndDate').val(end.format('YYYY-MM-DD'));
            });

		
		
		$('#file_laporan_kegiatan').on('filebatchuploadsuccess', function(event, data, previewId, index) {
		   var form = data.form, files = data.files, extra = data.extra, 
			response = data.response, reader = data.reader;
			
			if(response.status === true){ 
                $('#file_laporan_pdf').val('files');
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
				// window.location.href =BASE_URL+"layanan/realisasi";
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

$( document ).ready(function() {
    var nama_doc = $('#file_laporan_pdf').val();
    if (nama_doc === '' || nama_doc === 'undefined') {
        $('.upload_surat').show();
        $('.view_surat').hide();
    }else{
        $('.view_surat').show();
        $('.upload_surat').hide();

    }
    if ($('#is_final_print').val() > 0) {
        $('#ganti_pdf').hide();
    }
    $('.biaya').prop("disabled",true);
    $( ".viewsaja" ).prop( "disabled", true );
    $('#laporkan').prop("disabled",true);
    $('#sudah_dilaporkan').prop("disabled",true);

    var id_pdln = $('#id_pdln').val();
    for (var i = 0; i < 1; i++) {
        var id_kategori_biaya = $('#id_kategori_biaya_'+i).val();
        $.ajax({
            url : BASE_URL+"layanan/realisasi/get_biaya_estimasi/"+id_pdln+"/"+id_kategori_biaya,
            dataType : "json",
            type : "post",
            async : false,
            success : function(data){
                if(data.status === true){
                    var estimasi = data.estimasi;
                    for (var i = 0; i < estimasi.length; i++) {
                        var estimasi_biaya = estimasi[i].estimasi_biaya
                        $('#biaya_estimasi_'+i).val(estimasi_biaya);
                    }

               }
           }
        });
    }
});
$('#ganti_pdf').on('click', function(e) {
    $('.upload_surat').show();
    $('.view_surat').hide();



});
$('#edit_realisasi').on('click', function(e) {
        $('.biaya').prop("disabled",false);
        $( ".viewsaja" ).prop( "disabled", false );
        $('#laporkan').prop("disabled",false);
        $('#sudah_dilaporkan').prop("disabled",false);

    });

$('.biaya').inputmask("numeric", {
    radixPoint: ".",
    groupSeparator: ",",
    digits: 2,
    autoGroup: true,
    prefix: 'Rp ', //Space after $, this will not truncate the first character.
    rightAlign: false           
});