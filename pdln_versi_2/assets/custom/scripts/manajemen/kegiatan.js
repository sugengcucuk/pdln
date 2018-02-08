var HandleKegiatan = function () {
    var initTable = function () {
        var table = $('#tabel_kegiatan_manage'),
            save_method, row,form = $('#form_kegiatan'),
            error = $('.alert-danger', form);       
        var oTable = table.dataTable({
                    serverSide: true,
                    processing: true, 
                    searching: true,
                    ordering : true,
                    info:true,
                    paging : true,
                    language: {
                        url: BASE_URL+"assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                        url: BASE_URL+"manajemen/kegiatan/kegiatan_list", // ajax source
                        type: 'POST'
                    },                    
                    lengthMenu: [
                        [5, 10, 20, -1],
                        [5, 10, 20, "Semua"] // change per page values here
                    ],
                    pageLength: 10, 
                    columnDefs: [
                        { visible : false, targets : 0 , orderable : false, searching : false},
                        { visible : true, targets : 8 , orderable : false, searching : false}
                    ],
        });        
        $("input").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
		$('#Negara').on('change', function(){
            id_negara = $('#Negara').val();            
            $.post(BASE_URL+'manajemen/kegiatan/get_kota',{id_negara:id_negara}, function(data){
                if(data !== ''){
                    $("#Tujuan").val('');
                    $("#Tujuan").trigger("change");
                    $("#Tujuan").html(data);
                }
            });
        });
        $('#new_kegiatan').on('click',function(){
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            save_method = "tambah";
            $('#form_kegiatan')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#method').val("tambah");
			$("#negara").change();
            $("#negara").val('');
            $("#negara").trigger('change');
			$('#Nama').prop("disabled",false);
			$('#form_kegiatan').validate().resetForm();
            $('#modal_new_kegiatan').modal('show');
            $('.modal-title .title-text').text(" Tambah Kegiatan Baru ");         
        });
        $('#modal_new_kegiatan').on('click', '#simpan', function (e) { 
			if (form.valid() === false)
            return false;
		
            e.preventDefault();
            $('#simpan').text('Simpan...');
            $('#simpan').prop('disabled',true);            
            App.blockUI({ 
                boxed: true,
                message: 'Sedang di proses....'
            });
            $.ajax({
                url : BASE_URL+"manajemen/kegiatan/kegiatan_save",
                type: "POST",
                dataType: 'JSON',
                data: $('#form_kegiatan').serialize(), 
                success: function(data) 
                {
                    if(data.status === true){ 
                        if (save_method === "tambah"){                   
                            $('#modal_new_kegiatan').modal('hide');
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Data Kegiatan telah di tambah', {
                                    heading:'Sukses',
                                    theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                    } 
                                );
                            }, 1000); 
                        }
                        if (save_method === "ubah"){                            
                            $('#modal_new_kegiatan').modal('hide');                            
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Data Kegiatan telah di ubah', {
                                    heading:'Sukses',
                                    theme:'teal',
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);
                        }
                    }
                    else
                    {
                        if((data.msg) && (data.status === false)){
                            window.setTimeout(function() {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Error , '+data.msg, {
                                    heading:'Info',
                                    theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                    }
                                );
                            }, 1000);
                        }else{
                            App.unblockUI();
                            for (var i = 0; i < (data.inputerror).length; i++)
                            {
                                $('[name="'+data.inputerror[i]+'"]')
                                    .parent().parent()
                                    .addClass('has-error');
                                $('[name="'+data.inputerror[i]+'"]')
                                    .next()
                                    .text(data.error_string[i]);
                            }
                        }
                    }
                    $('#simpan').text('Simpan');
                    $('#simpan').prop('disabled',false);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    App.unblockUI();
                    bootbox.alert({
                        message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                                  ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                        title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                    }); 
                    $('#simpan').text('Simpan');
                    $('#simpan').prop('disabled',false);
                }
            });
        });    
        table.on('click', '#delete_kegiatan', function (e) {
            e.preventDefault();
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);
            bootbox.dialog({
                message: "Apakah anda yakin untuk menghapus data ?",
                    title: "Hapus Data",
                    buttons: {
                      success: {
                        label: "Hapus",
                        className: "btn-danger",
                        callback: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Sedang di proses....'
                            });
                            $.ajax({
                                url : BASE_URL+"manajemen/kegiatan/kegiatan_delete",
                                type: "POST",
                                dataType: 'JSON',
                                data: {ID : aData[0]},
                                success: function(data)
                                {
                                    // another theme : teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    if(data.success){
                                        window.setTimeout(function() {
                                            oTable.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8('Data Terhapus', {
                                                heading:'Info',
                                                theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                                life: 2000,
                                                horizontalEdge: 'bottom',
                                                verticalEdge: 'left'
                                                }
                                            );
                                        }, 1000); 
                                    }else{
                                        window.setTimeout(function() {
                                            oTable.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8('Error , Data Tidak dapat Terhapus', {
                                                heading:'Info',
                                                theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                                life: 2000,
                                                horizontalEdge: 'bottom',
                                                verticalEdge: 'left'
                                                }
                                            );
                                        }, 1000);
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrowns)
                                {
                                    App.unblockUI();
                                    bootbox.alert({
                                        message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                                                  ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                                        title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                                    });
                                }
                            });  
                        }
                      },
                      main: {
                        label: "Batal",
                        className: "blue"                        
                      }
                    }
            });
        });
        table.on('click', '#edit_kegiatan', function(e) {
            e.preventDefault();
            save_method = "ubah";
            $('.form-control').parent().removeClass('has-error');
            $('.help-block').empty();
            $('.modal-title .title-text').text("Ubah Data Kegiatan");
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);

            $('#ID').val(aData[0]);
            $('#method').val("ubah");            

            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            }); 
            $.ajax({
                url : BASE_URL+"manajemen/kegiatan/get_data_kegiatan",
                type: "POST",
                dataType: 'JSON',
                data: {ID : aData[0]},
                success: function(data)
                {
                    if(data.status === true){ 
                        window.setTimeout(function() {
						$('#Negara').val(data.Negara);
                        $('#Negara').trigger("change");
                        $('#Negara').change();
                            App.unblockUI();
                            $('#NamaKegiatan').val(data.NamaKegiatan);
							$('#JenisKegiatan').val(data.JenisKegiatan);
							$('#StartDate').val(data.StartDate);
							$('#EndDate').val(data.EndDate);
							$('#Penyelenggara').val(data.Penyelenggara);
							$("#Tujuan").val(data.Tujuan).trigger("change");                  
                            $("#Tujuan").trigger("change");
                            $("#JenisKegiatan").trigger("change");
							$("input[name=opt_status][value="+data.is_active+"]").prop('checked', true);
                            $('#modal_new_kegiatan').modal('show');
							
                            }, 1000);
                    }else{
                        App.unblockUI();
                        bootbox.alert({
                            message : '<span class="font-yellow"> Mohon maaf data bermasalah.</span> <br />'+
                                      ' <strong> Sistem tidak dapat menarik informasi dari database. </strong>',
                            title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                        }); 
                    } 
                    $('#simpan').text('Simpan'); 
                    $('#simpan').prop('disabled',false);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    App.unblockUI();
                    bootbox.alert({
                        message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                                  ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                        title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                    }); 
                    $('#simpan').text('Simpan');
                    $('#simpan').prop('disabled',false);
                }
            });
        });
		
		 $("#Negara, #JenisKegiatan, #Tujuan").select2({
            placeholder: "Silahkan Pilih",
            dropdownAutoWidth: true,
            width : 'auto',
            allowClear: true,
            debug: true
        });        
        $('#form_kegiatan').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input            
            rules: {                
                NamaKegiatan : {
                    required : true
                },
                JenisKegiatan : {
                    required : true
                },
                StartDate : {
                    required : true
                },
                EndDate : {
                    required : true
                },
                Penyelenggara : {
                    required : true
                },
                Tujuan : {
                    required : true
                },
				Negara : {
                    required : true
                },
                opt_status: {
                    required : true
                },
            },
            messages :{
                JenisKegiatan : {
                    required: 'Harap pilih salah satu',
                },
                Negara : {
                    required: 'Harap pilih salah satu',
                },
				Tujuan : {
                    required: 'Harap pilih salah satu',
                },
                NamaKegiatan : {
                    required: "Nama Kegiatan tidak boleh kosong",
                },
                EndDate : {
                    required: "Tanggal Berakhir tidak boleh kosong",
                },
                StartDate : {
                    required: "Tanggal Mulai tidak boleh kosong",
                },
                Penyelenggara : {
                    required: "Penyelenggara tidak boleh kosong",
                },
                opt_status: { 
                    required: 'Harap pilih salah satu',
                },                
            },
            onkeyup: function(element,event) {
                if ($(element).prop('name') === "Name") {
                    return false; // disable for your element named as "name"
                } else { // else use the default on everything else
                    if ( event.which === 9 && this.elementValue( element ) === "" ) {
                        return;
                    } else if ( element.name in this.submitted || element === this.lastElement ) {
                        this.element( element );
                    }
                }
            },            
            invalidHandler: function(event, validator) { //display error alert on form submit                
                error.fadeTo(3000, 500).slideUp(500, function(){});
                    App.scrollTo(error, -200);                    
            },
            errorPlacement: function(error, element) {
                if (element.is(':radio')) {
                    error.insertAfter(element.closest(".mt-radio-inline"));
                }else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },          
        });
        $('input,select', form).change(function () {
            $('#form_kegiatan').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
        $('#batal').on('click',function(){
            $('#form_kegiatan').validate().resetForm();
        });
		
		$('.input-daterange').datepicker({
			useCurrent: true,
				locale: 'id', 
				format:'dd/mm/yyyy',
				sideBySide: true,
				ignoreReadonly: true,
				showTodayButton: true,
				defaultDate :new Date(), 
				minDate:new Date()
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
        HandleKegiatan.init();
    }); 
}