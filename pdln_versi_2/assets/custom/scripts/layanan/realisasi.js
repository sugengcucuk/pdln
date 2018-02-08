var HandleRealisasi = function () {
    var initTable = function () {
        var table = $('#tabel_realisasi'),
           save_method, row,form = $('#form_realisasi'),
            error = $('.alert-danger', form);
        var oTable = table.dataTable({
					dom: '<"top"lf<"top_p"ip><"clear">>rt<"bottom"ip<"clear">>',
                    serverSide: true,
                    processing: true,
                    searching: true,
                    ordering : true,
                    info:true,
                    paging : true,
                    language: {
                        url: BASE_URL+"/assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                        url: BASE_URL+"layanan/realisasi/realisasi_list", // ajax source
						type: 'POST'
                    },
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 10,
                    columnDefs: [
                        { visible: false, targets: 0 , orderable: false , searchable : false},
                        { visible: true, targets: 8 , orderable: false , searchable : false}
                    ],
        });

		table.on('click', '#view_laporan', function(e) {
			e.preventDefault();
			row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);
			id_surat = aData[0];
            App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });
            App.unblockUI();
				pdf_url = "";

				$.ajax({
					url : BASE_URL+"layanan/realisasi/get_data_laporan",
					type: "POST",
					dataType: 'JSON',
					data: {ID : aData[0]},
					success: function(data)
					{
						if(data.status === true){
							pdf_url = BASE_URL+data.dokumen;

							$.fancybox({
							   type: 'html',
							   autoSize: false,
							   margin: [100, 60, 0, 60],
							   content: '<embed src="'+pdf_url+'#nameddest=self&page=1&view=FitH,0&zoom=80,0,0" type="application/pdf" height="100%" width="100%" />',
							   beforeClose: function() {
								 $(".fancybox-inner").unwrap();
							   }
							 }); //fancybox
							 return false;
						}else{
							App.unblockUI();
							bootbox.alert({
								message : '<span class="font-yellow"> Mohon maaf data bermasalah.</span> <br />'+
										  ' <strong> Sistem tidak dapat menarik informasi dari database. </strong>',
								title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
							});
						}

					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						App.unblockUI();
						bootbox.alert({
							message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
									  ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
							title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
						});
					}
				});

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
        HandleRealisasi.init();
    });
}