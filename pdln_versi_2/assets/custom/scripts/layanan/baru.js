var HandleBaru = function () {
    var initTable = function () {
        var table = $('#tabel_permohonan_baru'),
           save_method, row,form = $('#form_baru'),
            error = $('.alert-danger', form);
            var oTable = table.dataTable({
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
                        url: BASE_URL+"layanan/baru/baru_list", // ajax source  
                        type: 'POST'
                    },                    
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 10, 
                    columnDefs: [
                        { visible: false, targets: 0 , orderable: false , searchable : false},
                        { visible: true, targets: 7 , orderable: false , searchable : false}
                    ],
        });
        $('#btn-add-pdln').on('click', function(e){
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message : "Sedang di proses.."
            });
            window.setTimeout(function(){
                App.unblockUI();
                window.location.href = BASE_URL+'layanan/baru/add';
            },1100);
        });
        table.on('click', '#edit_pdln', function (e){
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message : "Sedang di proses.."
            });
            var row = $(this).parents('tr')[0],
                aData = oTable.fnGetData(row),
                id_pdln = aData[0];
                window.setTimeout(function(){
                    window.location.href = BASE_URL+'layanan/modify/edit_wizard/'+id_pdln;
                },200);
                
            // $.post(BASE_URL+'layanan/modify/edit_wizard',{id_pdln:id_pdln}, function(data){
            //     App.unblockUI();
            // });
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
        HandleBaru.init();
    });
}