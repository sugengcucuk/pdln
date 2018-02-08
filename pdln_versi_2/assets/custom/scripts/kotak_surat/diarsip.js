var HandleProgress = function () {
    var initTable = function () {
        var table = $('#tabel_progress_manage'),
           save_method, row,form = $('#form_progress'),
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
                        url: BASE_URL+"kotak_surat/approval/is_arsip", // ajax source
                        type: 'POST'
                    },
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 10,
                    "columnDefs": [
                        { "visible": false, "targets": 0 },
						{ "visible": false, "targets": 10 }
                    ],
					"order": [[ 10, "asc" ]],
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
        HandleProgress.init();
    });
}