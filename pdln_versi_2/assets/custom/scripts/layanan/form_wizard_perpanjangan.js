var FormWizardPermohonanPerpanjangan = function () {
    return {
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }
        var form = $('#submit_form_permohonan_baru'),
            error = $('.alert-danger', form),
            success = $('.alert-success', form),
            is_pemohon,min_date_kegiatan,max_date_kegiatan,
            table_list_peserta_confirm = $('#table_list_peserta_confirm'),
            start_date_penugasan,end_date_penugasan,
            oTablePesertaConfirm = table_list_peserta_confirm.DataTable();
            var tabel_peserta = $('#table_list_peserta');
            var oTablePeserta = tabel_peserta.dataTable();
            var focal_point_rules = {
                level_pejabat : {
                    required : true
                },
                no_surat_usulan_focal_point : {
                    required : true
                },
                tgl_surat_usulan_fp : {
                    required : true
                },
                file_surat_usulan_fp : {
                    required : true,
                    extension: "pdf"
                },
                pejabat_sign_permohonan : {
                    required : true,
                    minlength: 5
                },
                check_disclaimer : {
                    required : true
                }
            },
            biaya_tunggal_rules = {
                instansi_tunggal : {
                    required : true
                }
            };
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
        var showCatatan = function(){
            var msg = $('#catatan').val();
            App.alert({
                place: 'append', // append or prepent in container
                type: 'warning',  // alert's type
                message: "Penting!!! , Catatan Perbaikan : "+''+msg,  // alert's message
                close: false, // make alert closable
                reset: false, // close all previouse alerts first
                focus: true, // auto scroll to the alert after shown
                closeInSeconds : 0,
                icon: 'fa fa-warning' // put icon before the message
            });
        };
        $('#submit_form_permohonan_baru').validate({
            doNotHideMessage: false, //this option enables to show the error/success messages on tab switch.
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ':hidden, [readonly=true],[disabled=true]',
            errorElement: "em",
            rules: {
                file_surat_usulan_pemohon: {
                    extension: "pdf"
                },
                jenis_kegiatan : {
                    required : true
                },
                kegiatan : {
                    required : true
                },
                opt_pendanaan:{
                    required : true
                },
                waktu_penugasan : {
                    required : true
                }
            },
            messages: { // custom messages for radio buttons and checkboxes
                file_surat_usulan_pemohon : {
                    extension: "Maaf berkas yang di izinkan hanya extensi [.pdf]"
                },
                level_pejabat : {
                    required: "Harap pilih salah satu level pejabat, ini menentukan flow permohonan anda."
                },
                no_surat_usulan_focal_point : {
                    required: "Harap isi nomor surat"
                },
                tgl_surat_usulan_fp : {
                    required : "Silahkan tentukan tanggal "
                },
                file_surat_usulan_fp : {
                    required : "Mohon unggah file ",
                    extension: "Maaf berkas yang di izinkan hanya extensi [.pdf]"
                },
                pejabat_sign_permohonan : {
                    required : "Harap isi pejabat penandatanganan",
                    minlength : " Harap gunakan nama lengkap min 5 karakter atau huruf "
                },
                jenis_kegiatan : {
                    required : "Harap pilih salah satu"
                },
                kegiatan : {
                    required : "Harap pilih salah satu"
                },
                waktu_penugasan : {
                    required : "Harap tentukan waktu penugasan"
                },
                opt_pendanaan:{
                    required : "Pilih Opsi Pendanaan"
                },
                instansi_tunggal : {
                    required : "Anda harus memilih instansi."
                }
            },
            errorPlacement: function (error, element) { // render error placement for each input type
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
            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
                error.show();
                App.scrollTo(error, -200);
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label
                    .addClass('valid') // mark the current input as valid and display OK icon
                .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
            },
            submitHandler: function (form) {
                success.show();
                error.hide();
                // Submit function

            }
        });
        window.onload = function(e) {
            App.blockUI({
                boxed : true,
                message : "Harap tunggu...."
            });
            e.preventDefault();
            $("#btn-save-peserta-det").prop("disabled",true);
            $('#panel_tunggal').prop('disabled',true);
            $('#panel_campuran').prop('disabled',true);
            $('#panel_tunggal').hide();
            $('#panel_campuran').hide();
            $('#durasi').hide();
            $(".panel_peserta input").prop('disabled',true);
            $.ajax({
                url : BASE_URL+"layanan/modify/is_pemohon",
                dataType: "JSON",
                type:"POST",
                success : function (data){
                    if(data.status === true){
                        is_pemohon = true;
                        $('#focal_point_form_set').prop("disabled",true);
                        $('#list_pemohon').prop('disabled',true);
                        $('#user_id_pemohon').val(data.user_id_pemohon);
                        $('#user_id_fp').val(data.user_id_fp);
                        $('#disclaimer').prop('disabled',true);
                        $("#disclaimer").hide();
                        $("#disclaimer_aggrement").hide();
                        $(".button-submit").attr("disabled", false);
                        // removeRules(focal_point_rules);
                    }else{
                        is_pemohon = false;
                        // $('#pemohon_form_set').prop("disabled",true);
                        $('#user_id_pemohon').val(data.user_id_pemohon);
                        $('#user_id_fp').val(data.user_id_fp);
                        // addRules(focal_point_rules);
                    }
                    App.unblockUI();
                },
                error: function (jqXHR, textStatus, errorThrown){
                    window.setTimeout(function() {
                        App.unblockUI();
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

        $('#submit_form_permohonan_baru .button-submit').on('click',function(){
            var id_pdln = $('#id_pdln').val();
            App.blockUI({
                    boxed : true,
                    message : "Sedang diproses..."
                });
            $.ajax({
                url : BASE_URL+'layanan/modify/submit_permohonan',
                dataType: "JSON",
                type : "POST",
                data : $('#submit_form_permohonan_baru').serialize(),
                success : function (res){
                    if(res.status === true){
                        window.setTimeout(function(){
                            App.unblockUI();
                            if(res.status === true){
                                $.notific8('Permohonan telah dikirim ke '+res.msg+'......', {
                                    heading:'Sukses',
                                    theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 1000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                    }
                                );
                            }
                        },2000);
                        window.setTimeout(function(){
                            window.location.href = BASE_URL+"layanan/baru";
                        },2000);
                    }else{
                        window.setTimeout(function(){
                            App.unblockUI();
                            if(res.status === true){
                                $.notific8(''+res.msg, {
                                    heading:'Error',
                                    theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                    }
                                );
                            }
                        },1000);
                    }
                },
                error : function(){
                    App.unblockUI();
                }
            });
        });
        $('#submit_form_permohonan_baru input,select').change(function () {
            $('#submit_form_permohonan_baru').validate().element($(this));
        });
        /*
        $('#biaya_tunggal').inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            prefix: 'Rp ', //Space after $, this will not truncate the first character.
            rightAlign: false
        });
        $('#biaya_campuran').inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            prefix: 'Rp ', //Space after $, this will not truncate the first character.
            rightAlign: false
        });*/
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
        $('#jenis_kegiatan').select2({
            placeholder: 'Pilih Jenis Kegiatan',
            dropdownAutoWidth: false,
            width : 'null',
            allowClear: true
        });
        $('#kegiatan').select2({
            placeholder: 'Pilih Kegiatan',
            dropdownAutoWidth: false,
            width : 'null',
            allowClear: true,
            debug: true
        });
        $('#instansi_tunggal').select2({
            placeholder: 'Pilih Instansi',
            minimumInputLength: 3,
            dropdownAutoWidth: false,
            width : '500',
            allowClear: true
        });
        $('#instansi_campuran_gov').select2({
            placeholder: 'Pilih Instansi',
            minimumInputLength: 3,
            dropdownAutoWidth: false,
            width : '500',
            allowClear: true
        });
        $('#instansi_campuran_donor').select2({
            placeholder: 'Pilih Instansi',
            dropdownAutoWidth: false,
            minimumInputLength: 3,
            width : '500',
            allowClear: true
        });
        $('input:radio[name="opt_pendanaan"]').on('change',function(){
            if (this.checked && this.value == '0') {
                removeRules(biaya_tunggal_rules);
            }else{
                addRules(biaya_tunggal_rules);
            }
        });
        $('#btn-save-peserta-det',form).on('click',function(){
            $('#btn-save-peserta-det').prop('disabled',true);
            if (form.valid() === false)
                return false;
            App.blockUI({
                boxed: true,
                message: 'Sedang diproses....'
            });
            var extraData = $('#submit_form_permohonan_baru').serializeArray();
            extraData.push({name: 'start_date_penugasan', value: start_date_penugasan});
            extraData.push({name: 'end_date_penugasan', value: end_date_penugasan});
            $.ajax({
                url : BASE_URL+'layanan/modify/add_peserta',
                data : extraData,
                type : "POST",
                async : false,
                dataType: 'JSON',
                success : function(data){
                    if(data.status === true){
                        window.setTimeout(function() {
                            App.unblockUI();
                            $.notific8('Data Peserta telah di tambah', {
                                heading:'Sukses',
                                theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                life: 2000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'left'
                            });
                        }, 1000);
                        $('input:radio[name=opt_jenis_peserta]').prop("checked",false);
                        $('input:radio[name=opt_pendanaan]').prop("checked",false);
                        $('#nip_peserta').text('');
                        $('#nik_peserta').text('');
                        $('#nama_peserta').text('');
                        $('#jabatan_peserta').text('');
                        $('#telp_peserta').text('');
                        $('#instansi_peserta').text('');
                        $('#email_peserta').text('');
                        $('#durasi').text('');
                        $(".panel_peserta :input:file").fileinput('clear');
                        $(".panel_peserta :input:file").fileinput('disable');
                        $(".panel_peserta input").prop('disabled',true);
                        $('#waktu_penugasan').val('');
                        $('#instansi_tunggal').val('').trigger('change');
                        $('#instansi_campuran_gov').val('').trigger('change');
                        $('#instansi_campuran_donor').val('').trigger('change');
                        $('#biaya_tunggal').val('');
                        $('#biaya_campuran').val('');
                        $('input[name="check_jb[]"]').each(function(){
                            $(this).attr("checked", false);
                        });
                        $('#panel_tunggal').prop('disabled',true);
                        $('#panel_campuran').prop('disabled',true);
                        $('#panel_tunggal').hide();
                        $('#panel_campuran').hide();
                        $('#durasi').hide();
                        displayPeserta();
                        handlePulsate();
                    }else {
                        App.unblockUI();
                        $.notific8('Gagal simpan ke dalam sistem...', {
                            heading:'Error',
                            theme:'amethyst',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                            life: 2000,
                            horizontalEdge: 'bottom',
                            verticalEdge: 'right'
                            }
                        );
                        displayPeserta();
                        $('#panel_tunggal').prop('disabled',true);
                        $('#panel_campuran').prop('disabled',true);
                        $('#panel_tunggal').hide();
                        $('#panel_campuran').hide();
                    }
                }
            });
        });
        $('#instansi').on('change', function(){
            $('#instansi_lainnya').prop("value",'',true);
            $('#instansi_lainnya').prop("readonly",true);
            if($(this).val() === 'any'){
                $('#instansi_lainnya').prop("readonly",false);
            }
        });
        $('input:radio[name="opt_pendanaan"]').change(function(){
            if (this.checked && this.value == '0') {
                $('#panel_campuran').prop('disabled',true);
                $('#panel_campuran').hide();
                $('#panel_tunggal').prop('disabled',false);
                $('#panel_tunggal').show();
            }else{
                $('#panel_tunggal').prop('disabled',true);
                $('#panel_tunggal').hide();
                $('#panel_campuran').prop('disabled',false);
                $('#panel_campuran').show();
            }
        });
        $('#kegiatan').on('change', function(){
            $('#durasi').hide();
            var id_keg = $('#kegiatan').val();
            App.blockUI({
                    boxed: true,
                    message: 'Sedang diproses....'
            });
            $.ajax({
                url : BASE_URL+'layanan/modify/get_detail_keg',
                data : {id_keg:id_keg},
                type : "POST",
                async : false,
                dataType: 'JSON',
                success : function(data){
                    if(data.status === true){
                        window.setTimeout(function() {
                            App.unblockUI();
                            $.each(data, function(index,value) {
                                $("#penyelenggara").val(data.penyelenggara);
                                $("#negara").val(data.negara);
                                $('#kota').val(data.kota);
                                $('#tgl_mulai_kegiatan').val(data.tgl_mulai_kegiatan);
                                $('#tgl_akhir_kegiatan').val(data.tgl_akhir_kegiatan);
                                min_date_kegiatan = data.min_date;
                                max_date_kegiatan = data.max_date;
                                $('#waktu_penugasan').daterangepicker({
                                    locale: {
                                            format: 'DD/MM/YYYY',
                                            separator : " s/d ",
                                            applyLabel: "OK",
                                            cancelLabel: "Batal",
                                            weekLabel: "M",
                                            daysOfWeek: [
                                                        "Ming",
                                                        "Sen",
                                                        "Sel",
                                                        "Ra",
                                                        "Kam",
                                                        "Jum",
                                                        "Sab"
                                                        ],
                                            monthNames: [
                                                        "Januari",
                                                        "Februari",
                                                        "Maret",
                                                        "April",
                                                        "Mei",
                                                        "Juni",
                                                        "Juli",
                                                        "Agustus",
                                                        "September",
                                                        "Oktober",
                                                        "November",
                                                        "Desember"
                                            ],
                                    },
                                    startDate: data.min_date,
                                    endDate: data.max_date,
                                    applyClass: "btn-primary",
                                    opens: "center",
                                    minDate: data.min_date,
                                    maxDate: data.max_date
                                });
                                $('#waktu_penugasan').on('apply.daterangepicker', function(ev, picker) {
                                    start = picker.startDate;
                                    end = picker.endDate;
                                    start_date_penugasan = picker.startDate.format('YYYY/MM/DD');
                                    end_date_penugasan = picker.endDate.format('YYYY/MM/DD');
                                    var days = Math.floor((end - start) / (1000 * 60 * 60 * 24))+1;
                                    $('#durasi').text("Durasi : "+days+' Hari').show().append(" (* Termasuk Hari libur)");
                                 });
                            });
                        },1000);
                    }else {
                        App.unblockUI();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown){
                    window.setTimeout(function() {
                        App.unblockUI();
                        $.notific8('Mohon maaf koneksi gagal.....', {
                            heading:'Error',
                            theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                            life: 2000,
                            horizontalEdge: 'bottom',
                            verticalEdge: 'left'
                            }
                        );
                    }, 1000);
                }
            });
        });
        $('.button-draft',form).on('click', function(){
            if (form.valid() === false)
                return false;
            App.blockUI({
                boxed: true,
                message : "Sedang di proses.."
            });
            var formData = new FormData();
                if(is_pemohon === true){
                    formData.append('file_surat_usulan_pemohon', $('input[type=file]')[0].files[0]);
                }else{
                    formData.append('file_surat_usulan_pemohon', $('input[type=file]')[0].files[0]);
                    formData.append('file_surat_usulan_fp', $('input[type=file]')[1].files[0]);
                }
                formData.append('no_surat_usulan_pemohon',  $('#no_surat_usulan_pemohon').val());
                formData.append('tgl_surat_usulan_pemohon',  $('#tgl_surat_usulan_pemohon').val());
                formData.append('level_pejabat',  $('#level_pejabat').val());
                formData.append('no_surat_usulan_focal_point',  $('#no_surat_usulan_focal_point').val());
                formData.append('tgl_surat_usulan_fp',  $('#tgl_surat_usulan_fp').val());
                formData.append('pejabat_sign_permohonan',  $('#pejabat_sign_permohonan').val());
                formData.append('list_pemohon',  $('#list_pemohon').val());
                formData.append('kegiatan',  $('#kegiatan').val());
                formData.append('id_pdln',  $('#id_pdln').val());

                //ajax update in every step
                $.ajax({
                    url : BASE_URL+'layanan/modify/save_draft_update',
                    type : "POST",
                    contentType: false,
                    data : formData,
                    processData: false,
                    dataType: 'JSON',
                    success : function(data){
                        if(data.status === true){
                            window.setTimeout(function(){
                                view_file_step_1();
                                App.unblockUI();
                                $.notific8("Data berhasil disimpan...", {
                                    heading:'Sukses',
                                    theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                    }
                                );
                                $('#form_wizard_permohonan_baru').find('.button-next').click();
                            },1000);
                        }else{
                            window.setTimeout(function(){
                                App.unblockUI();
                                $.notific8(''+data.msg, {
                                    heading:'Sukses',
                                    theme:'tangerine',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                    }
                                );
                            },1000);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        App.unblockUI();
                        $.notific8('Mohon maaf koneksi gagal.....', {
                            heading:'Error',
                            theme:'lemon',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                            life: 2000,
                            horizontalEdge: 'bottom',
                            verticalEdge: 'left'
                        }
                    );
                }
            });
        });
        $('#jenis_kegiatan').on('change', function(){
            id_jenis = $('#jenis_kegiatan').val();
            $.post(BASE_URL+'layanan/modify/get_kegiatan',{id_jenis:id_jenis}, function(data){
                if(data){
                    $("#kegiatan").val('');
                    $("#kegiatan").trigger("change");
                    $("#kegiatan").html(data);
                    $("#penyelenggara").val('');
                    $("#negara").val('');
                    $('#kota').val('');
                    $('#tgl_mulai_kegiatan').val('');
                    $('#tgl_akhir_kegiatan').val('');
                }
            });
            $.ajax({
                url: BASE_URL+'layanan/modify/get_file_kegiatan',
                async : false,
                dataType: "JSON",
                type : "POST",
                data : {id_jenis : id_jenis, id_pdln : $('#id_pdln').val()},
                success: function(response){
                    $('#file_doc_require :input:file').each(function(){
                        $(this).rules("remove");
                    });
                    if(response.length > 0){
                        $('#file_doc_require').empty();
                        $.each(response, function(index,value) {
                            span_require = '';
                            if((response[index].is_require) === true)
                                span_require = '<span class="required" aria-required="true"> * </span>';
                            // Create Element HTML
                            $('#file_doc_require').append('<div class="form-group">'+
                                                            '<label class="control-label col-md-3">'+response[index].nama_full_doc+
                                                            span_require+
                                                            '</label>'+
                                                            '<div class="col-md-6">'+
                                                            '<input type="file" class="form-control" name="file_'+response[index].nama_doc+'" id="file_'+response[index].nama_doc+'" />'+
                                                            '<div id="errorBlock_file_'+response[index].nama_doc+'" class="help-block"></div>'+
                                                            '</div>'+
                                                            '</div>');
                            if(response[index].is_exist === true){
                                $('#file_doc_require #file_'+response[index].nama_doc+'').fileinput({
                                    language : 'id',
                                    showPreview : true,
                                    allowedPreviewTypes: ['pdf'],
                                    allowedFileExtensions : ['pdf'],
                                    overwriteInitial: true,
                                    initialPreview: [
                                        response[index].path_file
                                    ],
                                    initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
                                    initialPreviewFileType: 'pdf',
                                    elErrorContainer: '#errorBlock_file_'+response[index].nama_doc+'',
                                    maxFileSize: 2000,
                                    maxFileCount: 1,
                                    autoReplace:true,
                                    maxFilesNum: 1,
                                    showUpload:true,
                                    uploadAsync: false,
                                    showRemove: true,
                                    uploadUrl: BASE_URL+"layanan/modify/upload_file_kegiatan",
                                    dropZoneEnabled: false,
                                    'uploadExtraData': function() {
                                        return {
                                            jenis_doc : response[index].nama_doc,
                                            name_attr: "file_"+response[index].nama_doc,
                                            id_pdln: $('#id_pdln').val(),
                                            id_jenis_doc:response[index].id_jenis_doc,
                                            kategori_doc:1,
                                            type_file : ".pdf"
                                        };
                                    }
                                });
                            }
                            else{
                                $('#file_doc_require #file_'+response[index].nama_doc+'').fileinput({
                                    language : 'id',
                                    showPreview : true,
                                    allowedPreviewTypes: ['pdf'],
                                    allowedFileExtensions : ['pdf'],
                                    overwriteInitial: true,
                                    elErrorContainer: '#errorBlock_file_'+response[index].nama_doc+'',
                                    maxFileSize: 2000,
                                    maxFileCount: 1,
                                    autoReplace:true,
                                    maxFilesNum: 1,
                                    showUpload:true,
                                    uploadAsync: false,
                                    showRemove: true,
                                    uploadUrl: BASE_URL+"layanan/modify/upload_file_kegiatan",
                                    dropZoneEnabled: false,
                                    'uploadExtraData': function() {
                                        return {
                                            jenis_doc : response[index].nama_doc,
                                            name_attr: "file_"+response[index].nama_doc,
                                            id_pdln: $('#id_pdln').val(),
                                            id_jenis_doc:response[index].id_jenis_doc,
                                            kategori_doc:1,
                                            type_file : ".pdf"
                                        };
                                    }
                                });
                            }
                            if((response[index].is_require) === true ){
                                if(response[index].is_exist === true){
                                    $('#file_doc_require #file_'+response[index].nama_doc+'').on('change', function(event) {
                                        $(this).rules("add",{
                                            required: true,
                                            extension : "pdf",
                                            messages: {
                                                required: "Harap Unggah File"
                                            }
                                        });
                                        $(this).on('filebatchuploadsuccess', function(event, data, previewId, index) {
                                            $(this).rules('remove');
                                        });
                                    });
                                }else{
                                    $('#file_doc_require #file_'+response[index].nama_doc+'').rules("add",{
                                        required: true,
                                        extension : "pdf",
                                        messages: {
                                            required: "Harap Unggah File"
                                        }
                                    });
                                    $('#file_doc_require #file_'+response[index].nama_doc+'').on('filebatchuploadsuccess', function(event, data, previewId, index) {
                                        $(this).rules('remove');
                                    });
                                }
                            }
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown){
                    window.setTimeout(function() {
                        App.unblockUI();
                        $.notific8('Mohon maaf koneksi gagal.....', {
                            heading:'Error',
                            theme:'lemon',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                            life: 2000,
                            horizontalEdge: 'bottom',
                            verticalEdge: 'left'
                            }
                        );
                    }, 1000);
                }
            });
            $.ajax({
                url: BASE_URL+'layanan/modify/get_file_pemohon',
                dataType: "JSON",
                type : "POST",
                data : {id_jenis : id_jenis},
                success: function(data){
                    $('#file_doc_require_pemohon :input:file').each(function(){
                        $(this).rules("remove");
                    });
                    if(data.length > 0){
                        $('#file_doc_require_pemohon').empty();
                        $.each(data, function(index,value) {
                            span_require = '';
                            if((data[index].is_require) === true)
                                span_require = '<span class="required" aria-required="true"> * </span>';
                            $('#file_doc_require_pemohon').append('<div class="form-group">'+
                                                            '<label class="control-label col-md-3">'+data[index].nama_doc+
                                                            span_require+
                                                            '</label>'+
                                                            '<div class="col-md-8">'+
                                                            '<input type="file" class="form-control" name="file_'+data[index].nama_doc+'" id="file_'+data[index].nama_doc+'" />'+
                                                            '<div id="errorBlock_file_'+data[index].nama_doc+'" class="help-block"></div>'+
                                                            '</div>'+
                                                            '</div>');

                            $('#file_doc_require_pemohon #file_'+data[index].nama_doc+'').fileinput({
                                language : 'id',
                                showPreview : true,
                                allowedPreviewTypes: ['pdf'],
                                allowedFileExtensions : ['pdf'],
                                elErrorContainer: '#errorBlock_file_'+data[index].nama_doc+'',
                                maxFileSize: 2000,
                                maxFileCount: 1,
                                autoReplace:true,
                                maxFilesNum: 1,
                                showUpload:true,
                                uploadAsync: false,
                                showCaption: true,
                                uploadUrl: BASE_URL+"layanan/modify/upload_file_peserta",
                                dropZoneEnabled: false,
                                'uploadExtraData': function() {
                                    return {
                                            jenis_doc : data[index].nama_doc,
                                            name_attr: "file_"+data[index].nama_doc,
                                            id_pdln: $('#id_pdln').val(),
                                            id_jenis_doc:data[index].id_jenis_doc,
                                            id_peserta : $('#id_peserta').val(),
                                            kategori_doc:2,
                                            type_file : ".pdf"
                                    };
                                }
                            });
                            if((data[index].is_require) === true ){
                                $('#file_doc_require_pemohon #file_'+data[index].nama_doc+'').rules("add",{
                                    required: true,
                                    extension : "pdf",
                                    messages: {
                                        required: "Harap Unggah File"
                                    }
                                });
                                $('#file_doc_require_pemohon #file_'+data[index].nama_doc+'').on('filebatchuploadsuccess', function(event, data, previewId, index) {
                                    var form = data.form, files = data.files, extra = data.extra,
                                        response = data.response, reader = data.reader;
                                    $(this).rules('remove');
                                });
                            }
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown){
                    window.setTimeout(function() {
                        App.unblockUI();
                        $.notific8('Mohon maaf koneksi gagal.....', {
                            heading:'Error',
                            theme:'lemon',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                            life: 2000,
                            horizontalEdge: 'bottom',
                            verticalEdge: 'left'
                            }
                        );
                    }, 1000);
                }
            });
        });
        $("#tabel_pembiayaan input:checkbox").on('click', function() {
            var $box = $(this);
                if ($box.is(":checked")) {
                    var group = "input:checkbox[id='" + $box.attr("id") + "']";
                    $(group).prop("checked", false);
                    $box.prop("checked", true);
                } else {
                    $box.prop("checked", false);
            }
        });
        $('#check_disclaimer').click(function(){
            $('#form_wizard_permohonan_baru').find('.button-submit').show();
            $(".button-submit").attr("disabled", !this.checked);
        });
        var handlePulsate = function () {
            if (!jQuery().pulsate) {
                return;
            }
            if (App.isIE8() === true) {
                return; // pulsate plugin does not support IE8 and below
            }
            if (jQuery().pulsate) {
                $('#find_peserta').pulsate({
                    color: "#fdbe41",
                    repeat: 5,
                    speed: 800,
                    glow: true
                });
            }
        };
        var displayPeserta = function() {
            oTablePeserta = tabel_peserta.dataTable({
                            destroy : true,
                            serverSide: true,
                            processing : true,
                            searching: false,
                            ordering : true,
                            paging : true,
                            info: true,
                            language: {
                                url: BASE_URL+"assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                            },
                            ajax: {
                                    url: BASE_URL+"layanan/modify/get_list_peserta/"+$('#id_pdln').val(),
                                    type: 'POST'
                            },
                            columnDefs: [
                                { visible: false, targets: 0, searchable: false},
                                { visible: true, targets: 10, searchable: false},
                            ],
                            pageLength: -1
                    }).fnDraw();
        };
        $('#btn_del_file_surat_fp').on('click',function(){
            $('#show_fsp_fp').hide();
            $('#edit_fsp_fp').show();
        });
        $('#btn_del_file_sp').on('click',function(){
            $('#show_fsp').hide();
            $('#edit_fsp').show();
        });
        var view_file_step_1 = function(){
            var link_surat_pemohon;
            var link_surat_focal_point;
            $.ajax({
                url : BASE_URL+'layanan/modify/get_file_path',
                data : {
                        id_pdln : $('#id_pdln').val()
                        },
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
                            if(data.status_file_fp === false){
                                $(".s_focal_point_usulan").hide();
                                $('#show_fsp_fp').hide();
                                $('#edit_fsp_fp').show();
                            }
                            else{
                                $('#show_fsp_fp').show();
                                $(".s_focal_point_usulan").show();
                                $(".s_focal_point_usulan").prop("href",link_surat_focal_point );
                                $('#edit_fsp_fp').hide();
                            }
                        }
                        else{
                            $('#edit_fsp').hide();
                            $('#show_fsp').show();
                            $(".s_pemohon_usulan").show();
                            $(".s_pemohon_usulan").prop("href",link_surat_pemohon );

                            if(data.status_file_fp === false){
                                $(".s_focal_point_usulan").hide();
                                $('#show_fsp_fp').hide();
                                $('#edit_fsp_fp').show();
                            }
                            else{
                                $('#show_fsp_fp').show();
                                $(".s_focal_point_usulan").show();
                                $(".s_focal_point_usulan").prop("href",link_surat_focal_point );
                                $('#edit_fsp_fp').hide();
                            }
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
        var displayConfirm = function() {
            view_file_step_1();
            $('#tab4 .form-control-static', form).each(function(){
                var input = $('[name="'+$(this).attr("data-display")+'"]', form);
                if (input.is(":radio")) {
                    input = $('[name="'+$(this).attr("data-display")+'"]:checked', form);
                }
                if (input.is(":text") || input.is("textarea")) {
                    $(this).html(input.val());
                } else if (input.is("select")) {
                    $(this).html(input.find('option:selected').text());
                } else if (input.is(":radio") && input.is(":checked")) {
                    $(this).html(input.attr("data-title"));
                }
            });
                oTablePesertaConfirm = table_list_peserta_confirm.DataTable({
                    destroy: true,
                    processing : true,
                    searching: true,
                    ordering : true,
                    paging : true,
                    info: true,
                    language: {
                        url: BASE_URL+"assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                            url: BASE_URL+"layanan/modify/get_list_peserta/"+$('#id_pdln').val(),
                    },
                    columnDefs: [
                        { visible: false, targets: 0, searchable: false},
                        { visible: false, targets: 10, searchable: false},
                    ],
                    pageLength: -1
                }).draw();
        };
        var handleTitle = function(tab, navigation, index) {
            var total = navigation.find('li').length;
            var current = index + 1;
            // set wizard title
            $('.step-title', $('#form_wizard_permohonan_baru')).text('Langkah ' + (index + 1) + ' dari ' + total);
            // set done steps
            jQuery('li', $('#form_wizard_permohonan_baru')).removeClass("done");
            var li_list = navigation.find('li');
            for (var i = 0; i < index; i++) {
                jQuery(li_list[i]).addClass("done");
            }
            if (current == 1) {
                $('#form_wizard_permohonan_baru').find('.button-previous').hide();
                $('#form_wizard_permohonan_baru').find('.button-draft').show();
            } else {
                $('#form_wizard_permohonan_baru').find('.button-previous').show();
                $('#form_wizard_permohonan_baru').find('.button-draft').show();
            }
            if (current >= total) {
                $('#form_wizard_permohonan_baru').find('.button-next').hide();
                $('#form_wizard_permohonan_baru').find('.button-submit').show();
                $('#form_wizard_permohonan_baru').find('.button-draft').show();
                displayConfirm();
            } else {
                $('#form_wizard_permohonan_baru').find('.button-next').show();
                $('#form_wizard_permohonan_baru').find('.button-draft').show();
                $('#form_wizard_permohonan_baru').find('.button-submit').hide();
                if(current == (total-1)){
                    displayPeserta();
                    handlePulsate();
                }
            }
            App.scrollTo($('.page-title'));
        };
        $('#form_wizard_permohonan_baru').bootstrapWizard({
            'nextSelector': '.button-next',
            'previousSelector': '.button-previous',
            onTabClick: function (tab, navigation, index, clickedIndex) {
                return false; // Make can't click tab coz impact for validate if pemohon is not focal point
                success.hide();
                error.hide();
                if (form.valid() === false) {
                    return false;
                }
                handleTitle(tab, navigation, clickedIndex);
            },
            onNext: function (tab, navigation, index) {
                success.hide();
                error.hide();
                if (form.valid() === false) {
                    return false;
                }
                handleTitle(tab, navigation, index);
            },
            onPrevious: function (tab, navigation, index) {
                success.hide();
                error.hide();

                handleTitle(tab, navigation, index);
            },
            onTabShow: function (tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                var $percent = (current / total) * 100;
                $('#form_wizard_permohonan_baru').find('.progress-bar').css({
                    width: $percent + '%'
                });
            }
        });
        $('#form_wizard_permohonan_baru').find('.button-previous').hide();
        }
    };
}();
var HandlePeserta = function(){
    var handleLog = function(){
        var tabel_list_pemohon = $('#table_list_permohonan');
        var tabel_peserta = $('#table_list_peserta');
        var OTableListPeserta = tabel_peserta.dataTable();
        var OTable_list_pemohon = tabel_list_pemohon.dataTable({
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
                url: BASE_URL+"layanan/perpanjangan/list_pemohon", // ajax source
                type: 'POST'
            },
            lengthMenu: [
                [5],
                [5]
            ],
            pageLength: 5,
            columnDefs: [
                { visible: false, targets: 0 , orderable: false , searchable : false},
                { visible: true, targets: 8 , orderable: false , searchable : false}
            ],
        });

        $('#btn-find-reg').on('click', function(e) {
          e.preventDefault();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang di proses....'
                });
          window.setTimeout(function(){
                    App.unblockUI();
                    $('#table_list_permohonan').dataTable().fnDraw();
                    $('#modal_list_permohonan').modal('show');
                    $('modal_list_permohonan .modal-title .title-text').text(" List Data Permohonan");
                },800);
        });

        tabel_peserta.on('click','#edit_peserta',function(){
            $('#btn-find-peserta-det').prop("disabled",true);
            row = $(this).parents('tr')[0];
            var aData = OTableListPeserta.fnGetData(row);
            App.blockUI({
                boxed : true,
                message : "Sedang mencari data..."
            });
            $.ajax({
                url : BASE_URL+"layanan/modify/get_data_peserta",
                data : {id_log_peserta : aData[0]},
                dataType : "JSON",
                type : "POST",
                success : function(data){
                    if(data.status === true){
                        window.setTimeout(function(){

                        },400);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown){
                    $('#btn-find-peserta-det').prop("disabled",false);
                    window.setTimeout(function() {
                        App.unblockUI();
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
        });
        tabel_peserta.on('click','#remove_peserta',function(){
            row = $(this).parents('tr')[0];
            var aData = OTableListPeserta.fnGetData(row);
            bootbox.dialog({
                message: "Apakah anda yakin untuk menghapus data peserta "+'<span class="strong label label-danger">'+aData[4]+'</span>'+" ?",
                    title: "Hapus Data",
                    buttons: {
                      success: {
                        label: "Hapus",
                        className: "red",
                        callback: function() {
                            App.blockUI({
                                boxed : true,
                                message: "Sedang proses penghapusan...."
                            });
                            $.ajax({
                                url : BASE_URL+"layanan/modify/delete_peserta",
                                type: "POST",
                                data : {id_log_peserta : aData[0],id_pdln : $('#id_pdln').val()},
                                dataType: "JSON",
                                success: function(data){
                                    if(data.status === true){
                                        window.setTimeout(function(){
                                            OTableListPeserta.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8("Data sukses terhapus....", {
                                                heading:'Sukses',
                                                theme:'teal',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                                life: 2000,
                                                horizontalEdge: 'bottom',
                                                verticalEdge: 'left'
                                                }
                                            );
                                        },500);
                                    }else{
                                        window.setTimeout(function(){
                                            OTableListPeserta.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8("Gagal hapus data...", {
                                                heading:'Error',
                                                theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                                life: 2000,
                                                horizontalEdge: 'bottom',
                                                verticalEdge: 'left'
                                                }
                                            );
                                        },500);
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown){
                                    window.setTimeout(function() {
                                        App.unblockUI();
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
                        }
                      },
                      main: {
                        label: "Batal",
                        className: "blue"
                      }
                    }
                });
        });
        $('#btn-find-peserta-det').click(function(){
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            window.setTimeout(function(){
                App.unblockUI();
                $('#modal_list_permohonan').modal('show');
                $('#modal_list_permohonan .modal-title .title-text').text(" List Data Pemohon");
            },800);
        });
        tabel_list_pemohon.on('click','#btn_set_peserta',function(){
            App.blockUI({
                target : "#table_list_permohonan",
                overlayColor:"none",
                animate:!0
            });
            row = $(this).parents('tr')[0];
            var aData = OTable_list_pemohon.fnGetData(row);

            $.ajax({
                url : BASE_URL+"layanan/perpanjangan/get_data_pemohon",
                dataType : "JSON",
                type : "POST",
                data : {id_pdln : aData[0]},
                success : function(data){
                    if(data.status === true){
                        window.setTimeout(function(){

                            $('#no_reg').val(data.no_register);
                            $('#modal_list_permohonan').modal('hide');
                            App.unblockUI('#table_list_permohonan');
                            console.log("here");
                        },300);
                    }
                    if(data.status === false){
                        window.setTimeout(function() {
                            App.unblockUI('#table_list_permohonan');
                            $('#modal_list_permohonan').modal('hide');
                            $.notific8(''+data.msg, {
                                heading:'Informasi',
                                theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                life: 2000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'left'
                                }
                            );
                        }, 400);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown){
                    App.unblockUI('#table_list_permohonan');
                    $('#modal_list_permohonan').modal('hide');
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
    };

    return {
       init :function(){
            handleLog();
       }
    };
}();
var HandleKegiatan = function(){
    var KegiatanInit = function (){
        var save_method,form = $('#form_kegiatan');
        $("#modal_new_kegiatan input").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
        $('#Negara').on('change', function(){
            id_negara = $('#Negara').val();
            $.post(BASE_URL+'kegiatan/get_kota',{id_negara:id_negara}, function(data){
                if(data !== ''){
                    $("#Tujuan").val('');
                    $("#Tujuan").trigger("change");
                    $("#Tujuan").html(data);
                }
            });
        });
        $('#btn-add-kegiatan').on('click',function(){
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
            $("#Negara").change();
            $("#Negara").val('');
            $("#Negara").trigger('change');
            $('#Nama').prop("disabled",false);
            $('#form_kegiatan').validate().resetForm();
            $('#modal_new_kegiatan').modal('show');
            $('.modal-title .title-text').text(" Tambah Kegiatan Baru");
        });
        $('#modal_new_kegiatan').on('click', '#simpan', function (e) {
            e.preventDefault();
            $('#simpan').text('Simpan...');
            $('#simpan').prop('disabled',true);
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            $.ajax({
                url : BASE_URL+"kegiatan/kegiatan_save",
                type: "POST",
                dataType: 'JSON',
                data: $('#form_kegiatan').serialize(),
                success: function(data)
                {
                    if(data.status === true){
                        if (save_method === "tambah"){
                            $('#modal_new_kegiatan').modal('hide');
                            window.setTimeout(function() {
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
                    }
                    else
                    {
                        if((data.msg) && (data.status === false)){
                            window.setTimeout(function() {
                                App.unblockUI();
                                $.notific8('Error , '+data.msg, {
                                    heading:'Info',
                                    theme:'ruby',   // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 1000,
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
        $('#form_kegiatan input,select', form).change(function () {
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
            KegiatanInit();
        }
    };
}();
jQuery(document).ready(function() {
    $.fn.select2.defaults.set('language', 'id');
    $.fn.datepicker.defaults.language = 'id';
    FormWizardPermohonanPerpanjangan.init();
    HandleKegiatan.init();
    HandlePeserta.init();
});
