var HandleDone = function () {
    var initTable = function () {
        var table = $('#tabel_done_manage'),
           save_method, row,form = $('#form_done'),
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
                        url: BASE_URL+"/assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                        url: BASE_URL+"kotak_surat/approval/done_list", // ajax source
                        type: 'POST'
                    },
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 10,
                    "columnDefs": [
                        { "visible": false, "targets": 0 },
						{ "visible": false, "targets": 11 }
                    ],
					"order": [[ 1, "asc" ]],
        });

		table.on('click', '#download_sp', function (e) {
			row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);

			e.preventDefault();
            App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });

			id_pdln = aData[0];
            jenis_preview = $(aData[6]).text();
            if(jenis_preview === 'Pembatalan'){
                    pdf_url = BASE_URL + "kotak_surat/approval/print_pembatalan/" + id_pdln;
            }else if(jenis_preview === 'Perpanjang'){
                    pdf_url = BASE_URL + "kotak_surat/approval/print_perpanjangan/" + id_pdln;
            }else if(jenis_preview === 'Ralat'){
                    pdf_url = BASE_URL + "kotak_surat/approval/print_ralat/" + id_pdln;
            }else{
                pdf_url = BASE_URL + "kotak_surat/approval/print_permohonan/" + id_pdln;
            }
			// pdf_url = BASE_URL+"kotak_surat/approval/download/"+aData[0];
			App.unblockUI();
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
        HandleDone.init();
    });
}