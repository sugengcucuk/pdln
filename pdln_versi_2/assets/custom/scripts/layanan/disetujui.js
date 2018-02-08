var HandlePerpanjangan = function () {
    var initTable = function () {
        var table = $('#tabel_perpanjangan'),
           save_method, row,form = $('#form_perpanjangan'),
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
                        url: BASE_URL+"layanan/perpanjangan/disetujui_list", // ajax source  
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
                 var txt = $(this).text();
                 if (txt == 'Perpanjangan') {
                    val_doc = '20';

                 }else{
                    val_doc = '30';
                 }
                $('#modal_renew .total_catatan').text('');
                $('#modal_renew #general-item-list').empty();
                $('#modal_renew').modal('show');

                var row = $(this).parents('tr')[0],
                aData = oTable.fnGetData(row),
                id_pdln = aData[0];
                $('#modal_renew #nomer_register').html('<i class="fa fa-th-list"> </i> Nomer Registrasi <a href="'+BASE_URL+'/kotak_surat/approval/view_disetujui/'+aData[0]+'" target="_blank" class="item-name primary-link"> '+aData[2]+'</a><br>');
                $('#modal_renew #general-item-list_').html('<div class="table">'+
                                                '<table class="table table-hover table-bordered">'+
                                                '<tr>'+
                                                    '<td>kegiatan</td>'+
                                                    '<td>:</td>'+
                                                    '<td> '+aData[5]+'</td>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td>Tanggal Register</td>'+
                                                    '<td>:</td>'+
                                                    '<td>'+aData[3]+'</td>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td>Nomer surat</td>'+
                                                    '<td>:</td>'+
                                                    '<td>'+aData[4]+'</td>'+
                                                '</tr>'+
                                                '</table>'+
                                            '</div>'+
                                            '<div class="item-details"><button type="button" id="re_new_document"   onclick="renew_mydocument('+aData[0]+','+val_doc+')" name="edit_pdln" title="Permohonan Baru" class="btn submit btn-primary">Terbitkan Permohonan Pembatalan</button>'
                                                                +''+'</div>');


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
function renew_mydocument(id_pdln,is_event) {
    $('#modal_renew #general-item-list').empty();
    $('#trims').hide();
    $('#modal_renew #general-item-list_').html('<div class="item">'+
                    '<div class="item-body"> Mohon Menunggu ...</div>'+'</div>');
    var text_urai = "";
    if (is_event =='20') {
        text_urai = "perpanjangan";
    }else if (is_event =='30') {
        text_urai = "Ralat";

    }

    $.ajax({
        url: BASE_URL+'layanan/modify/re_new/'+id_pdln+'/'+is_event,
        dataType: "JSON",
        type : "POST",
        success: function(data){
            if (data.status == true) {
                $('#modal_renew #general-item-list_').html('<div class="item">'+
                    '<div class="item-details">Permohonan '+text_urai+' Telah Dibuat Silakan Melengkapi Permohonan ! </div>'+'</div>'+
                    
                    '<div class="item-details" id="trims" >Terima kasih ..'+
                    '</div>'+
                    '<div class="item-body"><a href="'+BASE_URL+'kotak_surat/modify/edit_wizard/'+data.id_pdln+'"target="_blank" ><button type="button" id="genered_draf"  title="Permohonan Baru" class="btn submit btn-primary">Lihat Document Baru</button></a></div></div>');
            }
        }
    });

    
}
$('#genered_draf').on('click', function(e){
        $('#modal_renew').modal('hide');
        $('#genered_draf').prop('disabled',true);

});