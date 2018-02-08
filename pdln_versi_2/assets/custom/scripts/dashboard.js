var DashboardInit = function(){
	var handleDashboard = function (){
		var table = $('#tabel_dashboard');
		var oTable = table.dataTable({
					cache: false,
					serverSide: true,
                    processing: true,
                    searching: true,
                    ordering : true,
                    dom: '<"top"lf<"top_p"ip><"clear">>rt<"bottom"ip<"clear">>',
                    scrollX:true,
                    info:true,
                    paging : true,
                    language: {
                        url: BASE_URL+"assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                        url: BASE_URL+"dashboard/home/list_dashboard",
                        type:'POST'
                    },
                    lengthMenu: [
                        [5, 10, 20, 30, 40, 50, -1],
                        [5, 10, 20, 30, 40, 50, "Semua"]
                    ],
                    pageLength: 10,
                    columnDefs: [
                        { visible : false, targets : 0  , searchable : false, orderable : false},
                        { visible : true , targets : 13 , searchable : false, orderable : false},
                        { visible : true , targets : 14 , searchable : false, orderable : false}
                    ]
		});
// form_survey_responden
        $('#survey_wajib').on('click',function() {
            var form_survey_responden =  $('#form_survey_responden').serialize();
            console.log(form_survey_responden);
            var id_tiket = $('#ID').val();
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            $.ajax({
                url : BASE_URL+"dashboard/home/tabulasi_survey",
                type: "POST",
                dataType: 'JSON',
                data: form_survey_responden,
                success: function(data)
                {
                    if(data.status === true){
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-green">Terima Kasih Telah Mengikuti Survey Singkat kami ... </span> <br />',
                            title: '<span class="font-green bold"> <strong> <i class="fa fa-thumbs-o-up"> </i> Survey Selesai  </strong><span>'
                        });
                        window.setTimeout(function () {
                                location.href = BASE_URL;
                            }, 3000);
                    }
                    else{
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-yellow">Terimakasih Telah menggunakan SIMPEL  </span> <br />' +
                                    data.message,
                            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Survey Wajib  </strong><span>'
                        });
                        window.setTimeout(function () {
                                location.href = BASE_URL;
                            }, 3000);
                    }
                }
                // error: function (jqXHR, textStatus, errorThrown){
                    
            });


        })

		table.on('click', '#preview_sp', function (e) {
			row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);
			e.preventDefault();
            App.blockUI({
                            boxed: true,
                            message: 'Sedang di proses....'
                        });
			id_pdln = $("#id_pdln").val();
			pdf_url = BASE_URL+"dashboard/home/print_permohonan/"+aData[0];
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
        });
        table.on('click', '#view_catatan', function (e){
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message : "Sedang di proses.."
            });
            $('#modal_log_catatan .total_catatan').text('');
	        $('#modal_log_catatan #general-item-list').empty();
            var row = $(this).parents('tr')[0],
                aData = oTable.fnGetData(row),
                id_pdln = aData[0];
            $.ajax({
                url: BASE_URL+'dashboard/home/get_log_catatan/'+id_pdln,
                dataType: "JSON",
                type : "POST",
		        success: function(response){
		        	if(response.status === true){
		            	$.each(response.data, function(index,value) {
			                $('#modal_log_catatan #general-item-list').append('<div class="item">'+
					                                            '<div class="item-head">'+
					                                                '<div class="item-details">'+
					                                                    '<span class="fa fa-sticky-note"></span> '+
					                                                    '<a class="item-name primary-link">Note #'+Number(index+1)+'</a>'+
					                                                    '<span class="item-label">'+response.data[index].day_submit_catatan+'</span>'+
					                                                '</div>'+
					                                                '<span class="item-status">'+
					                                                    '<span class="badge badge-empty badge-danger"></span> '+response.data[index].time_catatan+' WIB</span>'+
					                                            '</div>'+
					                                            '<div class="item-body">'+response.data[index].note+'</div>'+
					                                        '</div>');

			            });
			            $('#modal_log_catatan .total_catatan').text(response.total_catatan);
			            $('#modal_log_catatan').modal('show');
			            App.unblockUI();
		            }else{
						App.unblockUI();
		            }
		        },
	        	error: function (jqXHR, textStatus, errorThrown){
                    window.setTimeout(function() {
                        App.unblockUI();
                        $.notific8('Mohon maaf koneksi gagal.....', {
                            heading:'Error #500@pdln_glc',
                            theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                            life: 2000,
                            horizontalEdge: 'bottom',
                            verticalEdge: 'right'
                            }
                        );
                    }, 1000);
        		}
    		});
        });

	};



	return{
		init: function () {
         	handleDashboard();
        }
	};
}();

jQuery(document).ready(function() {
    DashboardInit.init();
    if ($('#test_survey').val() > 0) {
        $('#modal_survey').modal('show');
    }

        
});
