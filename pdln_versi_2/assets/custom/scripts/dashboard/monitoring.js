var HandleDone = function () {
    var initTable = function () {
        var table = $('#tabel_monitoring'),
           save_method, row,form = $('#form_done'),
            error = $('.alert-danger', form);
        var oTable = table.dataTable({
                    scrollX: true,
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
                        { "visible": false, "targets": 0 }
                    ],
        });

		table.on('click', '#download_sp', function (e) {
			row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);

			e.preventDefault();
            App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });

			id_pdln = $("#id_pdln").val();
			pdf_url = BASE_URL+"kotak_surat/approval/print_permohonan/"+aData[0];
			App.unblockUI();
			$.fancybox({
				type : 'iframe',
                autoCenter :true,
                fitToView   : true,
				title : "SP Perjalanan Dinas Luar Negeri",
                width       : '80%',
                height      : '80%',
                maxWidth    : 800,
                maxHeight   : 600,
                transitionIn : 'fade',
                transitionOut : 'fade',
				iframe : {
                    preload: true,
                    scrolling : 'auto'
                },
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