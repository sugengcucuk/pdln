var HandleTask = function () {
    var initTable = function () {
        var table = $('#tabel_task_manage'),
           save_method, row,form = $('#form_task'),
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
                        url: BASE_URL+"kotak_surat/approval/task_list", // ajax source
                        type: 'POST'
                    },
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 11,
                    "columnDefs": [
                        { "visible": false, "targets": 0 },
						{ "visible": false, "targets": 11 }
                    ],
					"order": [[ 11, "asc" ]],
        });


        table.on('click', '#archived', function (e){
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message : "Sedang di proses.."
            });
            $('#archived_form .total_catatan').text('');
            $('#archived_form #general-item-list').empty();
            $('#archived_form').modal('show');

            var row = $(this).parents('tr')[0],
                aData = oTable.fnGetData(row),
                id_pdln = aData[0];
            $('#archived_form #general-item-list_').html('<div class="item">'+
                                                                '<div class="item-head">'+
                                                                    '<div class="item-details">'+'<b>Document No : <b/><br>'+

                                                                        '<span class="fa fa-sticky-note"></span> '+
                                                                        '<a class="item-name primary-link"> '+aData[1]+'</a><br>'+
                                                                    '</div>'+
                                                                '</div>'+
                                                                '<div class="item-details">'+aData[7]+'</div>'+
                                                                '<div class="item-details">'+
                                                                // '<label class="control-label"> Catatan : </label>'+
                                                                //     '<div class="col-md-4">'+
                                                                //         '<textarea id="note" name="note" rows="1" cols="40"></textarea>'+
                                                                // '</div> </div>'+
                                                                // '<div class="item-details"> Akan Ter-Arsipkan <br><b> "TIDAK DAPAT DITERBITKAN/DIAJUKAN KEMBALI"</b><br></div>'+
                                                                '<div class="item-details"><button type="button" id="archived"   onclick="archived('+aData[0]+')" name="edit_pdln" title="archived" class="button primary">Arsipkan Sekarang</button>'
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
        HandleTask.init();
    });
}

function archived(id_pdln) {
    console.log(id_pdln);
    $('#archived_form #general-item-list').empty();
    $('#archived_form #general-item-list_').html('<div class="item">'+
                    '<div class="item-body"> Mohon Menunggu ...</div>'+'</div>');

    $.ajax({
        url: BASE_URL+'kotak_surat/approval/do_archiv/'+id_pdln,
        dataType: "JSON",
        type : "POST",
        success: function(data){
            if (data.status == true) {
                $('#archived_form #general-item-list_').html('<div class="item">'+
                    '<div class="item-body">'+data.msg+'</div>'+'</div>'+
                    '<div class="item-body"><button type="button"  title="reload" id="reload" onclick="reload()" class="button primary">Refresh Data !!</button></div>'+'</div>'+
                    // '<div class="item-body"><a href="'+BASE_URL+'layanan/modify/edit_task/'+data.id_pdln+'"target="_blank" ><button type="button"  title="Permohonan Baru" class="button primary">Lihat Document Arsip</button></a></div>'+
                    '</div>');

            }
        }
    });
}

function reload() {
     window.location.reload();
    }