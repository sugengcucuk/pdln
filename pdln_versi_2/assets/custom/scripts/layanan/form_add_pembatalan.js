var HandleAddPembatalan = function () {
    var initForm = function () {
        var form = $('#form_add_pembatalan'),
            error = $('.alert-danger', form),
            success = $('.alert-success', form),
            is_pemohon;
        var view_file_step_1 = function(){
            var link_surat_pemohon;
            var link_surat_focal_point;
            $.ajax({
                url : BASE_URL+'layanan/modify/get_file_path',
                data : {id_pdln : $('#id_pdln').val()},
                type : "POST",
                dataType : "JSON",
                success : function (data){
                    if(data.status === true){
                        link_surat_pemohon = data.path_pemohon;
                        link_surat_focal_point = data.path_focal_point;
                        if(data.status_file_pemohon === false){                            
                            $(".s_pemohon_usulan").hide();                            
                            $('#edit_fsp').show();
                            $('#show_fsp').hide();
                        }
                        else{
                            $('#show_fsp').show();
                            $(".s_pemohon_usulan").show();
                            $(".s_pemohon_usulan").prop("href",link_surat_pemohon );
                            $('#edit_fsp').hide();
                        }
                        if(data.status_file_fp === false){
                            $(".s_focal_point_usulan").hide();
                            $('#show_fsp_fp').hide();
                            $('#edit_fsp').show();
                        }
                        else{
                            $('#show_fsp_fp').show();                            
                            $(".s_focal_point_usulan").show();
                            $(".s_focal_point_usulan").prop("href",link_surat_focal_point );
                            $('#edit_fsp_fp').hide();
                        }
                    }
                    else
                        App.unblockUI();

                },
                error : function(jqXHR,errorThrown,text){
                    App.unblockUI();
                }
            });
            $('.s_pemohon_usulan').fancybox({                
                type : 'iframe',
                overlayShow : true,
                title : "Surat Pemohon",
                autoCenter  : true,
                fitToView   : true,
                width       : '80%',
                height      : '80%',
                autoSize    : false,
                maxWidth    : 800,
                maxHeight   : 800,
                transitionIn : 'fade',
                transitionOut : 'fade',
                iframe : {
                    preload: true,
                    scrolling : 'auto'
                }
            });         
            $('.s_focal_point_usulan').fancybox({                
                type : 'iframe',
                overlayShow : true,
                title : "Surat Focal Point",
                autoCenter  : true,
                fitToView   : true,
                width       : '80%',
                height      : '80%',
                autoSize    : false,
                maxWidth    : 800,                
                maxHeight   : 800,
                transitionIn : 'fade',
                transitionOut : 'fade',
                iframe : {
                    preload: true,
                    scrolling : 'auto'
                }
            });
        };
        var handlePulsate = function () {
            if (!jQuery().pulsate) {
                return;
            }
            if (App.isIE8() === true) {
                return; // pulsate plugin does not support IE8 and below
            }
            if (jQuery().pulsate) {
                $('#find_surat').pulsate({
                    color: "#E43A45",
                    repeat: 5,
                    speed: 800,
                    glow: true
                });
            }
        };
        var focal_point_rules = {
            // level_pejabat : {
            //     required : true
            // },
            // no_surat_usulan_focal_point : {
            //     required : true
            // },
            // tgl_surat_usulan_fp : {
            //     required : true
            // },
            // file_surat_usulan_fp : {
            //     required : true,
            //     extension: "pdf"
            // },
            // pejabat_sign_permohonan : {
            //     required : true,
            //     minlength: 5
            // },
            // check_disclaimer : {
            //     required : true
            // }
        };
        var tabel = $('#table_list_permohonan');
        var oTable = tabel.dataTable({
            destroy : true,
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
                url: BASE_URL+"layanan/pembatalan/list_permohonan", // ajax source
                type: 'POST'
            },
            lengthMenu: [
                [5],
                [5]
            ],
            pageLength: 5, 
            columnDefs: [
                { visible: false, targets: 0 , orderable: false , searchable : false},
                { visible: true, targets: 9 , orderable: false , searchable : false}
            ],
        });
        function addRules(rulesObj){
            for (var item in rulesObj){
               $('#'+item).rules('add',rulesObj[item]);
            }
        }
        function removeRules(rulesObj){
            for (var item in rulesObj){
               $('#'+item).rules('remove');
            } 
        }       
        $('#kembali').on('click', function(e) {
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            window.setTimeout(function(){
                App.unblockUI();
                window.location.href = BASE_URL+"layanan/pembatalan";
            },300);
        });
        $('#check_disclaimer').click(function(){
            $('#form_wizard_permohonan_baru').find('.button-submit').show();
            $(".button-submit").attr("disabled", !this.checked);
        });
        $('#tgl_surat_usulan_fp').datepicker({
            locale : 'id',
            format : 'dd-mm-yyyy',        
            autoclose : true            
        });
        $("#tgl_surat_usulan_pemohon").datepicker({
            locale : 'id',
            format : 'dd-mm-yyyy',        
            autoclose : true            
            })
            .on('change', function() {
                $(this).valid();
        });
        $('#list_pemohon').select2({
            placeholder: "Silahkan Pilih",
            dropdownAutoWidth: false,
            width : 'null',
            allowClear: true        
        });     
        $('#kirim_pembatalan').on('click', function(e) { 
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });            
            App.unblockUI();
            if($('#form_add_pembatalan').valid()){
                $.ajax({
                    url : BASE_URL+"layanan/pembatalan/pembatalan_save",
                    type: "POST",
                    dataType: 'JSON',
                    data: $('#form_add_pembatalan').serialize(),
                    success: function(data){
                        if(data.status === true){ 
                            alert('Permohonan Pembatalan telah dikirim ke ' + data.msg + ', nomor registrasi anda adalah '+ data.no_register);
                            window.setTimeout(function () {
                                App.unblockUI();
                                swal({
                                    title: "Informasi",
                                    text: 'Permohonan Pembatalan telah dikirim ke ' + data.msg + '......',
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Lihat Nomor Registrasi",
                                    closeOnConfirm: false
                                },
                                function () {
                                    swal({
                                        title: "Nomor Registrasi",
                                        text: "Nomor Registrasi Anda adalah " + data.no_register,
                                        type: "info"
                                    }, function () {
                                        window.location.href = BASE_URL+"layanan/pembatalan/";
                                    });
                                });
                            }, 2000);
                        }
                        else
                        {
                            alert('Ada kesalahan');
                            if((data.msg !== '') && (data.status === false)){                                
                                window.setTimeout(function() {                                    
                                    App.unblockUI("#form_add_pembatalan");
                                    $.notific8('Error , '+data.msg, {
                                        heading:'Info',
                                        theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                        life: 2000,
                                        horizontalEdge: 'bottom',
                                        verticalEdge: 'left'
                                        }
                                    );
                                }, 1000);
                            }
                        }
                        $('#simpan').text('Simpan');
                        $('#simpan').prop('disabled',false);
                        window.location.href = BASE_URL+"layanan/pembatalan/";
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        App.unblockUI("#form_add_pembatalan");
                        bootbox.alert({
                            message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                                      ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                            title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>' 
                        }); 
                        $('#simpan').text('Simpan');
                        $('#simpan').prop('disabled',false);
                    }
                });
            }else{
                App.unblockUI("#form_add_pembatalan");
                $('#simpan').text('Simpan');
                $('#simpan').prop('disabled',false);
            }
        });
        $('#cari_surat').on('click', function(e) {
            e.preventDefault();
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });            
            window.setTimeout(function(){
                App.unblockUI();
                oTable.fnDraw();
                $('#modal_list_permohonan').modal('show');
                $('modal_list_permohonan .modal-title .title-text').text(" List Data Permohonan");
            },800);
        });
        /* Added by Datu */
        tabel.on('click', '#btn_set_surat', function () {
            App.blockUI({
                target: "#table_list_permohonan",
                overlayColor: "none",
                animate: !0
            });
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);
            $.ajax({
                url: BASE_URL + "layanan/pembatalan/get_data_surat_persetujuan",
                dataType: "json",
                type: "post",
                data: {id_pdln: aData[0]},
                success: function (data) {
                    if (data.status === true) {
                        window.setTimeout(function () {
                            $('#id_pdln').val(aData[0]);
                            $('#no_sp_lama').html(data.no_sp);
                            $('#show_fsp').removeAttr("style");
                            $('#view_file_pemohon_s_1').html(data.path_file_sp_pemohon);
                            $('#no_surat_usulan_pemohon').val(data.no_surat_usulan_pemohon);
                            $('#tgl_surat_usulan_pemohon').val(data.tgl_surat_usulan_pemohon);
                            $('#level_pejabat').val(data.id_level_pejabat);
                            $('#no_surat_usulan_focal_point').val(data.no_surat_usulan_fp);
                            $('#tgl_surat_usulan_fp').val(data.tgl_surat_usulan_fp);
                            $('#show_fsp_fp').removeAttr("style");
                            $('#view_file_fp').html(data.path_file_sp_fp);
                            $('#pejabat_sign_permohonan').val(data.pejabat_sign_up);
                            $('#list_pemohon').val(data.unit_pemohon);
                            $('#alasan_pembatalan').prop('disabled', false);
                            $('#kirim_pembatalan').prop('disabled', false);
                            $('#disclaimer').prop('disabled',false);
                            $("#disclaimer").show();
                            $("#disclaimer_aggrement").show();
                            App.unblockUI('#table_list_permohonan');
                            $('#modal_list_permohonan').modal('hide');
                        }, 300);
                    }
                    if (data.status === false) {
                        window.setTimeout(function () {
                            App.unblockUI('#table_list_permohonan');
                            $('#modal_list_permohonan').modal('hide');
                            $.notific8('' + data.msg, {
                                heading: 'Informasi',
                                theme: 'ruby',
                                life: 2000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'left'
                            }
                            );
                        }, 400);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    App.unblockUI('#table_list_permohonan');
                    bootbox.alert({
                        message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                        title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                    });
                    $('#simpan').text('Simpan');
                    $('#simpan').prop('disabled', false);
                }
            });
        });
        /* End Added By Datu */

        $('#form_add_pembatalan').validate({
            doNotHideMessage: false, //this option enables to show the error/success messages on tab switch.        
            errorClass: 'help-block help-block-error', // default input error message class
            errorElement: "em",            
            focusInvalid: false, // do not focus the last invalid input
            ignore: ':hidden, [readonly=true],[disabled=true]',
            rules: {                
                alasan_pembatalan : {
                    required : true 
                },
                check_disclaimer : {
                    required : true 
                },
                id_pdln : {
                    required : true 
                }
            },
            messages :{
                alasan_pembatalan : {
                    required: 'Alasan pembatalan harus di isi!!!!',
                },
                check_disclaimer : {
                    required: 'Disclaimer harus di centang!',
                },
                id_pdln : {
                    required : 'Silahkan Pilih Permohonan Yang Ingin Dibatalkan' 
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit                
                error.fadeTo(3000, 500).slideUp(500, function(){});
                    App.scrollTo(error, -200);
            },
            errorPlacement: function(error, element) {
                if (element.is(':radio')) {
                    error.insertAfter(element.closest(".mt-radio-inline"));
                }else if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                }else if(element.is('input:file')) {
                    error.insertAfter(element.closest(".file"));
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
        $('input,select',form).change(function () {
            $('#form_add_pembatalan').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
        $('#kembali').on('click',function(){
            $('#form_add_pembatalan').validate().resetForm();
        });
        window.onload = function(e) {
            e.preventDefault();            
            $.ajax({
                url : BASE_URL+"layanan/pembatalan/is_pemohon",
                dataType: "JSON",
                type:"POST",
                success : function (data){
                    if(data.status === true){
                        is_pemohon = true;                        
                        $('#disclaimer').prop('disabled',true);
                        $("#disclaimer").hide();
                        $("#disclaimer_aggrement").hide();
                        removeRules(focal_point_rules);                  
                    }else{
                        is_pemohon = false;
                        $('#disclaimer').prop('disabled',true);
                        $("#disclaimer").hide();
                        $("#disclaimer_aggrement").hide();
                        $('#pemohon_form_set').prop("disabled",true);
                        addRules(focal_point_rules);                        
                    }
                    $('#focal_point_form_set').prop("disabled",true);
                    $('#list_pemohon').prop('disabled',true);
                    $('#user_id_pemohon').val(data.user_id_pemohon);
                    $('#user_id_fp').val(data.user_id_fp);                    
                    $('#kirim_pembatalan').prop("disabled",true);
                    $('#alasan_pembatalan').prop("disabled",true);
                    handlePulsate();
                },
                error: function (jqXHR, textStatus, errorThrown){
                    window.setTimeout(function() {                        
                        $.notific8('Mohon maaf koneksi gagal.....', {
                            heading:'Error',
                            theme:'ebony',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                            life: 2000,
                            horizontalEdge: 'bottom',
                            verticalEdge: 'left'
                            }
                        );
                    }, 1000);
                }
            });            
        };
    };
    return {
        //main function to initiate the module
        init: function () {
            initForm();
        }
    };
}();
jQuery(document).ready(function() {
    HandleAddPembatalan.init();
});