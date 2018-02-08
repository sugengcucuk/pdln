var HandlePembatalan = function () {
    var initTable = function () {
        var table = $('#tabel_pembatalan'),
           save_method, row,form = $('#form_pembatalan'),
            error = $('.alert-danger', form);
        var oTable = table.dataTable({
                    dom: '<"top"lf<"top_p"ip><"clear">>rt<"bottom"ip<"clear">>',
                    serverSide: true,
                    processing: true,
                    searching: true,
                    ordering : true,
                    info : true,
                    paging : true,
                    language: {
                        url: BASE_URL+"/assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                        url: BASE_URL+"layanan/pembatalan/pembatalan_list", // ajax source
                        type: 'POST'
                    },
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 10,
                    columnDefs: [
                        { visible: false, targets: 0 , orderable: false , searchable : false}
                    ],
        });
        $("#tgl_surat_usulan").datepicker();
        // $("#file_surat_usulan_fc,#file_surat_usulan").fileinput({
        //     'language' : 'id',
        //     'showPreview' : true,
        //     'allowedFileExtensions' : ['jpg', 'png','gif','docx','doc','pdf'],
        //     'elErrorContainer': '#errorBlock',
        //     'maxFileSize': 1000,
        //     'maxFilesNum': 1
        // });
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
        HandlePembatalan.init();
    });
}