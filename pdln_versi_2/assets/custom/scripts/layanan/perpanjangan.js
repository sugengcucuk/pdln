var HandlePerpanjangan = function () {
    var initTable = function () {
        var table = $('#tabel_perpanjangan'),
           save_method, row,form = $('#form_perpanjangan'),
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
                        url: BASE_URL+"layanan/perpanjangan/perpanjangan_list", // ajax source
                        type: 'POST'
                    },
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 11,
                    columnDefs: [
                        { visible: false, targets: 0 , orderable: false , searchable : false},
                        { visible: true, targets: 7 , orderable: false , searchable : false}
                    ],
        });
        // $("#tgl_surat_usulan,#tgl_mulai,#tgl_akhir").datepicker();
        // $("#file_surat_usulan_fc,#file_surat_usulan,#file_surat_2_1,#file_surat_2_2,#file_surat_2_3,#file_surat_2_4,#file_surat_2_5,#file_surat_2_6").fileinput({
        //     'language' : 'id',
        //     'showPreview' : true,
        //     'allowedFileExtensions' : ['jpg', 'png','gif','docx','doc','pdf'],
        //     'elErrorContainer': '#errorBlock',
        //     'maxFileSize': 1000,
        //     'maxFilesNum': 1
        // });
        // $("#file_pas_foto,#file_karpeg,#file_ktp,#file_npwp").fileinput({
        //     'language' : 'id',
        //     'showPreview' : true,
        //     'allowedFileExtensions' : ['jpg', 'png','gif'],
        //     'elErrorContainer': '#errorBlock',
        //     'maxFileSize': 300,
        //     'maxFilesNum': 1
        // });
        $("#nip_peserta").maxlength({
            threshold: 18,
            warningClass: "label label-danger",
            limitReachedClass: "label label-info",
            placement: 'top',
            validate: true
        });
        $("#nik_peserta").maxlength({
            threshold: 16,
            warningClass: "label label-danger",
            limitReachedClass: "label label-info",
            placement: 'top',
            validate: true
        });

        table.on('click', '#form_renew', function (e){
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message : "Sedang di proses.."
            });
            $('#modal_renew .total_catatan').text('');
            $('#modal_renew #general-item-list').empty();
            $('#modal_renew').modal('show');

            var row = $(this).parents('tr')[0],
                aData = oTable.fnGetData(row),
                id_pdln = aData[0];
            $('#modal_renew #general-item-list_').html('<div class="item">'+
                                                                '<div class="item-head">'+
                                                                    '<div class="item-details">'+'<b>Document No :'+aData[0]+'<b/><br>'+

                                                                        '<span class="fa fa-sticky-note"></span> '+
                                                                        '<a class="item-name primary-link"> '+aData[2]+'</a><br>'+
                                                                    '</div>'+
                                                                '</div>'+
                                                                '<div class="item-details">'+aData[11]+'</div>'+
                                                                '<div class="item-details">'+aData[3]+'</div>'+
                                                                '<div class="item-details">'+aData[4]+'</div><br>'+
                                                                '<div class="item-details"><button type="button" id="re_new_document"   onclick="renew_mydocument('+aData[0]+')" name="edit_pdln" title="Permohonan Baru" class="button primary">Terbitkan Permohonan Baru</button>'
                                                                +''+'</div>'+
                                                            '</div>');
            App.unblockUI();
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
        HandlePerpanjangan.init();
    });
}

function renew_mydocument(id_pdln) {
    $('#modal_renew #general-item-list').empty();
     $('#modal_renew #general-item-list_').html('<div class="item">'+
                    '<div class="item-body"> Mohon Menunggu ...</div>'+'</div>');

    $.ajax({
        url: BASE_URL+'layanan/modify/re_new/'+id_pdln,
        dataType: "JSON",
        type : "POST",
        success: function(data){
            if (data.status == true) {
                $('#modal_renew #general-item-list_').html('<div class="item">'+
                    '<div class="item-body">'+data.msg+'</div>'+'</div>'+
                    '<div class="item-body">'+data.no_register+'</div>'+'</div>'+
                    '<div class="item-body"><a href="'+BASE_URL+'kotak_surat/modify/edit_wizard/'+data.id_pdln+'"target="_blank" ><button type="button"  title="Permohonan Baru" class="button primary">Lihat Document Baru</button></a></div>'+'</div>');
            }
        }
    });


}