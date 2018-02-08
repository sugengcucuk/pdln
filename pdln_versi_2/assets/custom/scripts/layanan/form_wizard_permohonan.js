var FormWizardPermohonanBaru = function () {
    return {
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }
            var form = $('#submit_form_permohonan_baru'),
                    error = $('.alert-danger', form),
                    success = $('.alert-success', form),
                    warning = $('.alert-warning', form),
                    is_pemohon,
                    min_date_kegiatan,
                    max_date_kegiatan,
                    is_first_click_draft = true,
                    is_first_add_peserta = true,
                    save_draft, table_list_peserta_confirm = $('#table_list_peserta_confirm'),
                    start_date_penugasan,
                    end_date_penugasan,
                    oTablePesertaConfirm = table_list_peserta_confirm.DataTable(),
                    tabel_peserta = $('#table_list_peserta'),
                    oTablePeserta = tabel_peserta.dataTable(),
                    unit_pemohon_rules = {
                        file_surat_usulan_pemohon: {
                            required: true
                        },
                        no_surat_usulan_pemohon: {
                            required: true
                        },
                        tgl_surat_usulan_pemohon: {
                            required: true
                        }
                    }
                    focal_point_rules = {
                        level_pejabat: {
                            required: true
                        },
                        no_surat_usulan_focal_point: {
                            required: true
                        },
                        tgl_surat_usulan_fp: {
                            required: true
                        },
                        file_surat_usulan_fp: {
                            required: true,
                            extension: "pdf"
                        },
                        pejabat_sign_permohonan: {
                            required: true,
                            minlength: 5
                        },
                        check_disclaimer: {
                            required: true
                        }
                    },
            biaya_tunggal_rules = {
                instansi_tunggal: {
                    required: true
                }
            };
            function addRules(rulesObj) {
                for (var item in rulesObj) {
                    $('#' + item).rules('add', rulesObj[item]);
                }
            }
            function removeRules(rulesObj) {
                for (var item in rulesObj) {
                    $('#' + item).rules('remove');
                }
            }
            form.validate({
                doNotHideMessage: false,
                errorClass: 'help-block help-block-error',
                focusInvalid: false,
                ignore: '.ignore,:hidden, [readonly=true],[disabled=true]',
                errorElement: "em",
                rules: {
                    file_surat_usulan_pemohon: {
                        extension: "pdf"
                    },
                    jenis_kegiatan: {
                        required: true
                    },
                    kegiatan: {
                        required: true
                    },
                    opt_pendanaan: {
                        required: true
                    },
                    waktu_penugasan: {
                        required: true
                    }
                },
                messages: {
                    tgl_surat_usulan_pemohon:{
                        required: "Harap isi tanggal surat"
                    },
                    no_surat_usulan_pemohon:{
                        required: "Harap isi nomor surat"
                    },
                    file_surat_usulan_pemohon: {
                        required: "Mohon unggah file",
                        extension: "Maaf berkas yang di izinkan hanya extensi [.pdf]"
                    },
                    level_pejabat: {
                        required: "Harap pilih salah satu level pejabat, ini menentukan flow permohonan anda."
                    },
                    no_surat_usulan_focal_point: {
                        required: "Harap isi nomor surat"
                    },
                    tgl_surat_usulan_fp: {
                        required: "Silahkan tentukan tanggal "
                    },
                    file_surat_usulan_fp: {
                        required: "Mohon unggah file ",
                        extension: "Maaf berkas yang di izinkan hanya extensi [.pdf]"
                    },
                    pejabat_sign_permohonan: {
                        required: "Harap isi pejabat penandatanganan",
                        minlength: " Harap gunakan nama lengkap min 5 karakter atau huruf "
                    },
                    jenis_kegiatan: {
                        required: "Harap pilih salah satu"
                    },
                    kegiatan: {
                        required: "Harap pilih salah satu"
                    },
                    waktu_penugasan: {
                        required: "Harap tentukan waktu penugasan"
                    },
                    opt_pendanaan: {
                        required: "Pilih Opsi Pendanaan"
                    },
                    instansi_tunggal: {
                        required: "Anda harus memilih instansi."
                    }
                },
                errorPlacement: function (error, element) {
                    if (element.is(':radio')) {
                        error.insertAfter(element.closest(".mt-radio-inline"));
                    } else if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if (element.is('input:file')) {
                        error.insertAfter(element.closest(".file"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                invalidHandler: function (event, validator) {
                    success.hide();
                    error.show();
                    App.scrollTo(error, -200);
                },
                highlight: function (element) {
                    $(element)
                            .closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element)
                            .closest('.form-group').removeClass('has-error');
                },
                success: function (label) {
                    label
                            .addClass('valid')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                submitHandler: function (form) {
                    success.show();
                    error.hide();
                }
            });
            window.onload = function (e) {
                e.preventDefault();
                $("#btn-save-peserta-det").prop("disabled", true);
                $('#panel_tunggal').prop('disabled', true);
                $('#panel_campuran').prop('disabled', true);
                $('#panel_tunggal').hide();
                $('#panel_campuran').hide();
                $('#durasi').hide();
                $(".panel_peserta input").prop('disabled', true);
                $.ajax({
                    url: BASE_URL + "layanan/baru/is_pemohon",
                    dataType: "json",
                    type: "post",
                    success: function (data) {
                        if (data.status === true) {
                            is_pemohon = true;
                            $('#focal_point_form_set').prop("disabled", true);
                            $('#list_pemohon').prop('disabled', false);
                            $('#user_id_pemohon').val(data.user_id_pemohon);
                            $('#user_id_fp').val(data.user_id_fp);
                            addRules(unit_pemohon_rules);
                            // removeRules(focal_point_rules);
                        } else {
                            is_pemohon = false;
                            $('#list_pemohon').prop('disabled', false);
                            $('#pemohon_form_set').prop("disabled", true);
                            addRules(focal_point_rules);
                            $('#user_id_pemohon').val(data.user_id_pemohon);
                            $('#user_id_fp').val(data.user_id_fp);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        window.setTimeout(function () {
                            App.unblockUI();
                            $.notific8('Mohon maaf koneksi gagal.....', {
                                heading: 'Error',
                                theme: 'ebony',
                                life: 2000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'left'
                            }
                            );
                        }, 1000);
                    }
                });
            };
            $('#biaya_tunggal').inputmask("numeric", {
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true,
                prefix: 'Rp ',
                rightAlign: false
            });
            $('#biaya_campuran').inputmask("numeric", {
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true,
                prefix: 'Rp ',
                rightAlign: false
            });
            $('#tgl_surat_usulan_fp').datepicker({
                locale: 'id',
                format: 'd-m-yyyy',
                autoclose: true,
                todayHighlight: true
            });
            $("#tgl_surat_usulan_pemohon").datepicker({
                locale: 'id',
                format: 'd-m-yyyy',
                autoclose: true,
                todayHighlight: true
            })
            .on('change', function () {
                $(this).valid();
            });
            $('#jenis_kegiatan').select2({
                placeholder: 'Pilih Jenis Kegiatan',
                dropdownAutoWidth: false,
                width: 'null',
                allowClear: true
            });
            $('#kegiatan').select2({
                placeholder: 'Pilih Kegiatan',
                dropdownAutoWidth: false,
                width: 'null',
                allowClear: true,
                debug: true
            });
            $('#instansi_tunggal').select2({
                placeholder: 'Pilih Instansi',
                minimumInputLength: 3,
                dropdownAutoWidth: false,
                width: '500',
                allowClear: true
            });
            $('#instansi_campuran_gov').select2({
                placeholder: 'Pilih Instansi',
                minimumInputLength: 3,
                dropdownAutoWidth: false,
                width: '500',
                allowClear: true
            });
            $('#instansi_campuran_donor').select2({
                placeholder: 'Pilih Instansi',
                dropdownAutoWidth: false,
                minimumInputLength: 3,
                width: '500',
                allowClear: true
            });
            $('#jenis_kegiatan').on('change', function () {
                id_jenis = $('#jenis_kegiatan').val();
                $.post(BASE_URL + 'layanan/baru/get_kegiatan', {id_jenis: id_jenis}, function (data) {
                    if (data) {
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
                    url: BASE_URL + 'layanan/baru/get_file_kegiatan',
                    async: false,
                    dataType: "json",
                    type: "post",
                    data: {id_jenis: id_jenis},
                    success: function (response) {
                        $('#file_doc_require :input:file').each(function () {
                            $(this).rules("remove");
                        });
                        if (response.length > 0) {
                            $('#file_doc_require').empty();
                            $.each(response, function (index, value) {
                                span_require = '';
                                if ((response[index].is_require) === true)
                                    span_require = '<span class="required" aria-required="true"> * </span>';
                                // Create Element HTML
                                $('#file_doc_require').append('<div class="form-group">' +
                                        '<label class="control-label col-md-3">' + response[index].nama_full_doc +
                                        span_require +
                                        '</label>' +
                                        '<div class="col-md-8">' +
                                        '<input type="file" class="form-control" name="file_' + (response[index].nama_doc).replace(/\s+/g, '') + '" id="file_' + (response[index].nama_doc).replace(/\s+/g, '') + '" />' +
                                        '<div id="errorBlock_file_' + (response[index].nama_doc).replace(/\s+/g, '') + '" class="help-block"></div>' +
                                        '</div>' +
                                        '</div>');

                                $('#file_doc_require #file_' + (response[index].nama_doc).replace(/\s+/g, '') + '').fileinput({
                                    language: 'id',
                                    showPreview: true,
                                    allowedPreviewTypes: ['pdf'],
                                    allowedFileExtensions: ['pdf'],
                                    elErrorContainer: '#errorBlock_file_' + (response[index].nama_doc).replace(/\s+/g, '') + '',
                                    maxFileSize: 2000,
                                    maxFileCount: 1,
                                    autoReplace: true,
                                    maxFilesNum: 1,
                                    showUpload: true,
                                    uploadAsync: false,
                                    showRemove: true,
                                    uploadUrl: BASE_URL + "layanan/baru/upload_file_kegiatan",
                                    dropZoneEnabled: false,
                                    'uploadExtraData': function () {
                                        return {
                                            jenis_doc: response[index].nama_doc,
                                            name_attr: "file_" + response[index].nama_doc,
                                            id_pdln: $('#id_pdln').val(),
                                            id_jenis_doc: response[index].id_jenis_doc,
                                            kategori_doc: 1,
                                            type_file: ".pdf"
                                        };
                                    }
                                });
                                if ((response[index].is_require) === true) {
                                    $('#file_doc_require #file_' + (response[index].nama_doc).replace(/\s+/g, '') + '').rules("add", {
                                        required: true,
                                        extension: "pdf",
                                        messages: {
                                            required: "Harap Unggah File"
                                        }
                                    });
                                    $('#file_doc_require #file_' + (response[index].nama_doc).replace(/\s+/g, '') + '').on('filebatchuploadsuccess', function (event, data, previewId, index) {
                                        var form = data.form, files = data.files, extra = data.extra,
                                                response = data.response, reader = data.reader;
                                        $(this).rules('remove');
                                    });
                                }
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        window.setTimeout(function () {
                            App.unblockUI();
                            $.notific8('Mohon maaf koneksi gagal.....', {
                                heading: 'Error',
                                theme: 'lemon',
                                life: 2000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'left'
                            }
                            );
                        }, 1000);
                    }
                });
                $.ajax({
                    url: BASE_URL + 'layanan/baru/get_file_pemohon',
                    dataType: "json",
                    type: "post",
                    data: {id_jenis: id_jenis},
                    success: function (data) {
                        $('#file_doc_require_pemohon :input:file').each(function () {
                            $(this).rules("remove");
                        });
                        if (data.length > 0) {
                            $('#file_doc_require_pemohon').empty();
                            $.each(data, function (index, value) {
                                span_require = '';
                                if ((data[index].is_require) === true)
                                    span_require = '<span class="required" aria-required="true"> * </span>';
                                $('#file_doc_require_pemohon').append('<div class="form-group">' +
                                        '<label class="control-label col-md-3">' + data[index].nama_doc +
                                        span_require +
                                        '</label>' +
                                        '<div class="col-md-8">' +
                                        '<input type="file" class="form-control" name="file_' + data[index].nama_doc + '" id="file_' + data[index].nama_doc + '" />' +
                                        '<div id="errorBlock_file_' + data[index].nama_doc + '" class="help-block"></div>' +
                                        '</div>' +
                                        '</div>');

                                $('#file_doc_require_pemohon #file_' + data[index].nama_doc + '').fileinput({
                                    language: 'id',
                                    showPreview: true,
                                    allowedPreviewTypes: ['pdf'],
                                    allowedFileExtensions: ['pdf'],
                                    elErrorContainer: '#errorBlock_file_' + data[index].nama_doc + '',
                                    maxFileSize: 2000,
                                    maxFileCount: 1,
                                    autoReplace: true,
                                    maxFilesNum: 1,
                                    showUpload: true,
                                    uploadAsync: false,
                                    showCaption: true,
                                    uploadUrl: BASE_URL + "layanan/baru/upload_file_peserta",
                                    dropZoneEnabled: false,
                                    'uploadExtraData': function () {
                                        return {
                                            jenis_doc: data[index].nama_doc,
                                            name_attr: "file_" + data[index].nama_doc,
                                            id_pdln: $('#id_pdln').val(),
                                            id_jenis_doc: data[index].id_jenis_doc,
                                            id_peserta: $('#id_peserta').val(),
                                            kategori_doc: 2,
                                            type_file: ".pdf"
                                        };
                                    }
                                });
                                if ((data[index].is_require) === true) {
                                    $('#file_doc_require_pemohon #file_' + data[index].nama_doc + '').rules("add", {
                                        required: true,
                                        extension: "pdf",
                                        messages: {
                                            required: "Harap Unggah File"
                                        }
                                    });
                                    $('#file_doc_require_pemohon #file_' + data[index].nama_doc + '').on('filebatchuploadsuccess', function (event, data, previewId, index) {
                                        var form = data.form, files = data.files, extra = data.extra,
                                                response = data.response, reader = data.reader;
                                        $(this).rules('remove');
                                    });
                                }
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        window.setTimeout(function () {
                            App.unblockUI();
                            $.notific8('Mohon maaf koneksi gagal.....', {
                                heading: 'Error',
                                theme: 'lemon',
                                life: 2000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'left'
                            }
                            );
                        }, 1000);
                    }
                });
            });
            $('#kegiatan').on('change', function () {
                $('#durasi').hide();
                var id_keg = $('#kegiatan').val();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang diproses....'
                });
                $.ajax({
                    url: BASE_URL + 'layanan/baru/get_detail_keg',
                    data: {id_keg: id_keg},
                    type: "post",
                    async: false,
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status === true) {
                            window.setTimeout(function () {
                                App.unblockUI();
                                $.each(data, function (index, value) {
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
                                            separator: " s/d ",
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
                                    $('#waktu_penugasan').on('apply.daterangepicker', function (ev, picker) {
                                        start = picker.startDate;
                                        end = picker.endDate;
                                        start_date_penugasan = picker.startDate.format('YYYY/MM/DD');
                                        end_date_penugasan = picker.endDate.format('YYYY/MM/DD');
                                        var days = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
                                        if ($('#instansi_tunggal').val() > 0 && $('#id_peserta').val() > 0 ) {
                                            $('#btn-save-peserta-det').prop('disabled',false);
                                        }
                                        $('#durasi').text("Durasi : " + days + ' Hari').show().append(" (* Termasuk Hari libur)");
                                    });
                                });
                            }, 1000);
                        } else {
                            App.unblockUI();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        window.setTimeout(function () {
                            App.unblockUI();
                            $.notific8('Mohon maaf koneksi gagal.....', {
                                heading: 'Error',
                                theme: 'ruby',
                                life: 2000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'left'
                            }
                            );
                        }, 1000);
                    }
                });
            });
            $('input:radio[name="opt_pendanaan"]').change(function () {
                if (this.checked && this.value == '0') {
                    $('#panel_campuran').prop('disabled', true);
                    $('#panel_campuran').hide();
                    $('#panel_tunggal').prop('disabled', false);
                    $('#panel_tunggal').show();
                    addRules(biaya_tunggal_rules);
                } else {
                    $('#panel_tunggal').prop('disabled', true);
                    $('#panel_tunggal').hide();
                    $('#panel_campuran').prop('disabled', false);
                    $('#panel_campuran').show();
                    removeRules(biaya_tunggal_rules);
                }
            });
            $('#Description_level').on('click', function(){
                    $('#desc_level').empty();
                    var selected = $("#level_pejabat option:selected");
                    var extra = selected.data('desc');
                    var res = extra.replace("<br>", "*");
                    var sp = res.split("*");
                    if (sp.length > 1) {
                        for (var i = 0; i < sp.length; i++) {
                            $('#desc_level').append('<div class="item">'+
                                                                '<div class="item-head">'+
                                                                        '<i class="fa fa-star" aria-hidden="true"></i>'+
                                                                        '<a class="item-name primary-link"> '+sp[i]+'</a>'+
                                                                '</div>'+
                                                            '</div>');
                        }
                    }
                    $('#list_level').modal('show');

            });
            $("#tabel_pembiayaan input:checkbox").on('click', function () {
                var $box = $(this);
                if ($box.is(":checked")) {
                    var group = "input:checkbox[id='" + $box.attr("id") + "']";
                    $(group).prop("checked", false);
                    $box.prop("checked", true);
                } else {
                    $box.prop("checked", false);
                }
            });
            $('#check_disclaimer').click(function () {
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
            var displayPeserta = function () {
                oTablePeserta = tabel_peserta.dataTable({
                    dom: '<"top"lf<"top_p"ip><"clear">>rt<"bottom"ip<"clear">>',
                    scrollX:true,
                    destroy: true,
                    processing: true,
                    searching: false,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        url: BASE_URL + "assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                        url: BASE_URL + "layanan/baru/get_list_peserta/" + $('#id_pdln').val(),
                    },
                    columnDefs: [
                        {visible: false, targets: 0, searchable: false},
                        {visible: true, targets: 10, searchable: false},
                    ],
                    pageLength: -1
                }).fnDraw();
            };
            var displayConfirm = function () {
                var link_surat_pemohon;
                var link_surat_focal_point;
                $.ajax({
                    url: BASE_URL + 'layanan/baru/get_file_path',
                    data: {
                        id_pdln: $('#id_pdln').val()
                    },
                    type: "post",
                    dataType: "json",
                    success: function (data) {
                        if (data.status === true) {
                            link_surat_pemohon = data.path_pemohon;
                            link_surat_focal_point = data.path_focal_point;
                            if (data.status_file_pemohon === false)
                                $(".s_pemohon_usulan").hide();
                            else {
                                $(".s_pemohon_usulan").show();
                                $(".s_pemohon_usulan").prop("href", link_surat_pemohon);
                            }
                            if (data.status_file_fp === false)
                                $(".s_focal_point_usulan").hide();
                            else {
                                $(".s_focal_point_usulan").show();
                                $(".s_focal_point_usulan").prop("href", link_surat_focal_point);
                            }
                        }
                        App.unblockUI();
                    },
                    error: function (jqXHR, errorThrown, text) {
                        $(".s_focal_point_usulan").append('<span class="label label-info">Request not available. Please contact IT Helpdesk KTLN.<a href="mailto:info.siktln@setneg.go.id">info.siktln@setneg.go.id</a></span>');
                        $(".s_focal_point_usulan").remove();
                    }
                });
                $('.s_pemohon_usulan').fancybox({
                    type: 'iframe',
                    title: "Surat Pemohon",
                    autoCenter: true,
                    fitToView: false,
                    width: '80%',
                    height: '80%',
                    autoSize: false,
                    maxWidth: 800,
                    maxHeight: 700,
                    iframe: {
                        preload: true,
                        scrolling: 'auto'
                    }
                });
                $('.s_focal_point_usulan').fancybox({
                    type: 'iframe',
                    title: "Surat Focal Point",
                    autoCenter: true,
                    fitToView: false,
                    width: '70%',
                    height: '80%',
                    autoSize: false,
                    maxWidth: 800,
                    maxHeight: 600,
                    iframe: {
                        preload: true,
                        scrolling: 'auto'
                    }
                });
                $('#tab4 .form-control-static', form).each(function () {
                    var input = $('[name="' + $(this).attr("data-display") + '"]', form);
                    if (input.is(":radio")) {
                        input = $('[name="' + $(this).attr("data-display") + '"]:checked', form);
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
                    dom: '<"top"lf<"top_p"ip><"clear">>rt<"bottom"ip<"clear">>',
                    scrollX:true,
                    destroy: true,
                    processing: true,
                    searching: true,
                    ordering: true,
                    paging: true,
                    info: true,
                    language: {
                        url: BASE_URL + "assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
                    },
                    ajax: {
                        url: BASE_URL + "layanan/baru/get_list_peserta/" + $('#id_pdln').val(),
                    },
                    columnDefs: [
                        {visible: false, targets: 0, searchable: false},
                        {visible: false, targets: 10, searchable: false},
                    ],
                    pageLength: -1
                }).draw();
            };
            var handleTitle = function (tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                $('.step-title', $('#form_wizard_permohonan_baru')).text('Langkah ' + (index + 1) + ' dari ' + total);
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
                    if (is_first_click_draft) {
                        $('#form_wizard_permohonan_baru').find('.button-next').hide();
                    } else {
                        $('#form_wizard_permohonan_baru').find('.button-next').show();
                    }
                    $('#form_wizard_permohonan_baru').find('.button-draft').show();
                    $('#form_wizard_permohonan_baru').find('.button-submit').hide();
                    if (current == (total - 1)) {
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
                    if (form.valid() === false)
                        return false;
                    handleTitle(tab, navigation, clickedIndex);
                },
                onNext: function (tab, navigation, index) {
                    success.hide();
                    error.hide();
                    if (form.valid() === false)
                        return false;
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
            $('#btn-save-peserta-det', form).on('click', function () {
                if ($('#submit_form_permohonan_baru').valid() === false)
                    return false;
                App.blockUI({
                    boxed: true,
                    message: 'Sedang diproses....'
                });
                $('#btn-save-peserta-det').prop('disabled', true);
                var extraData = $('#submit_form_permohonan_baru').serializeArray();
                extraData.push({name: 'start_date_penugasan', value: start_date_penugasan});
                extraData.push({name: 'end_date_penugasan', value: end_date_penugasan});
                $.ajax({
                    url: BASE_URL + 'layanan/baru/add_peserta',
                    data: extraData,
                    type: "post",
                    async: false,
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status === true) {
                            window.setTimeout(function () {
                                App.unblockUI();
                                $.notific8($('#id_log_peserta').val() ==''?'Data Peserta telah di tambah':'Data Peserta telah di update', {
                                    heading: 'Sukses',
                                    theme: 'teal',
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);
                            $('input:radio[name=opt_jenis_peserta]').prop("checked", false);
                            $('input:radio[name=opt_pendanaan]').prop("checked", false);
                            $('#nip_peserta').text('');
                            $('#nik_peserta').text('');
                            $('#paspor_peserta').text('');
                            $('#nama_peserta').text('');
                            $('#jabatan_peserta').text('');
                            $('#telp_peserta').text('');
                            $('#instansi_peserta').text('');
                            $('#email_peserta').text('');
                            $('#durasi').text('');
                            $(".panel_peserta :input:file").fileinput('clear');
                            $(".panel_peserta :input:file").fileinput('disable');
                            $(".panel_peserta input").prop('disabled', true);
                            $('#waktu_penugasan').val('');
                            $('#instansi_tunggal').val('').trigger('change');
                            $('#instansi_campuran_gov').val('').trigger('change');
                            $('#instansi_campuran_donor').val('').trigger('change');
                            $('#biaya_tunggal').val('');
                            $('#biaya_campuran').val('');
                            $('input[name="check_jb[]"]').each(function () {
                                $(this).attr("checked", false);
                            });
                            $('#panel_tunggal').prop('disabled', true);
                            $('#panel_campuran').prop('disabled', true);
                            $('#panel_tunggal').hide();
                            $('#panel_campuran').hide();
                            $('#durasi').hide();
                            $('#id_log_peserta').val('');
                            displayPeserta();
                            handlePulsate();
                        } else {
                            App.unblockUI();
                            $.notific8('Gagal simpan ke dalam sistem...', {
                                heading: 'Error',
                                theme: 'amethyst',
                                life: 2000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'right'
                            }
                            );
                            displayPeserta();
                            $('#panel_tunggal').prop('disabled', true);
                            $('#panel_campuran').prop('disabled', true);
                            $('#panel_tunggal').hide();
                            $('#panel_campuran').hide();
                        }
                    }
                });
            });
            $('.button-draft', form).on('click', function () {
                if ($('#submit_form_permohonan_baru').valid() === false)
                    return false;
                App.blockUI({
                    boxed: true,
                    message: "Sedang di proses.."
                });
                var formData = new FormData();
                if (is_pemohon === true) {
                    formData.append('file_surat_usulan_pemohon', $('input[type=file]')[0].files[0]);
                } else {
                    // formData.append('file_surat_usulan_pemohon', $('input[type=file]')[0].files[0]);
                    formData.append('file_surat_usulan_fp', $('input[type=file]')[0].files[0]);
                }
                formData.append('no_surat_usulan_pemohon', $('#no_surat_usulan_pemohon').val());
                formData.append('tgl_surat_usulan_pemohon', $('#tgl_surat_usulan_pemohon').val());
                formData.append('level_pejabat', $('#level_pejabat').val());
                formData.append('no_surat_usulan_focal_point', $('#no_surat_usulan_focal_point').val());
                formData.append('tgl_surat_usulan_fp', $('#tgl_surat_usulan_fp').val());
                formData.append('pejabat_sign_permohonan', $('#pejabat_sign_permohonan').val());
                formData.append('list_pemohon', $('#list_pemohon').val());
                formData.append('kegiatan', $('#kegiatan').val());
                if (is_first_click_draft) {
                    save_draft = "insert";
                    is_first_click_draft = false;
                    $('#form_wizard_permohonan_baru').find('.button-next').show();
                    //ajax insert
                    $.ajax({
                        url: BASE_URL + 'layanan/baru/save_draft_insert',
                        type: "post",
                        contentType: false,
                        data: formData,
                        processData: false,
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status === true) {
                                window.setTimeout(function () {
                                    App.unblockUI();
                                    $('#id_pdln').val(data.id_pdln);
                                    $.notific8('Data Permohonan disimpan.', {
                                        heading: 'Sukses',
                                        theme: 'teal',
                                        life: 1000,
                                        horizontalEdge: 'bottom',
                                        verticalEdge: 'left'
                                    }
                                    );
                                    $('.button-next').click();
                                }, 1000);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            App.unblockUI();
                            $.notific8('Mohon maaf koneksi gagal.....', {
                                heading: 'Error',
                                theme: 'tangerine',
                                life: 1000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'left'
                            }
                            );
                        }
                    });
                } else {
                    formData.append('id_pdln', $('#id_pdln').val());
                    save_draft = "update";
                    is_first_click_draft = false;
                    //ajax update in every step
                    $.ajax({
                        url: BASE_URL + 'layanan/baru/save_draft_update',
                        type: "post",
                        contentType: false,
                        data: formData,
                        processData: false,
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status === true) {
                                window.setTimeout(function () {
                                    App.unblockUI();
                                    $.notific8("Data berhasil di update", {
                                        heading: 'Sukses',
                                        theme: 'teal',
                                        life: 2000,
                                        horizontalEdge: 'bottom',
                                        verticalEdge: 'left'
                                    }
                                    );
                                }, 1000);
                            } else {
                                window.setTimeout(function () {
                                    App.unblockUI();
                                    $.notific8("Data berhasil di update", {
                                        heading: 'Sukses',
                                        theme: 'teal',
                                        life: 2000,
                                        horizontalEdge: 'bottom',
                                        verticalEdge: 'left'
                                    }
                                    );
                                }, 1000);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            App.unblockUI();
                            $.notific8('Mohon maaf koneksi gagal.....', {
                                heading: 'Error',
                                theme: 'lemon',
                                life: 2000,
                                horizontalEdge: 'bottom',
                                verticalEdge: 'left'
                            }
                            );
                        }
                    });
                }
            });
            $('#print_register').on('click', function () {
                var id_pdln = $('#id_pdln').val();
                window.open(BASE_URL + "layanan/modify/print_resi_register/"+id_pdln);
                window.location.href = BASE_URL;
            });


            $('#kembali_ke_home').on('click', function () {
                window.location.href = BASE_URL;
            });

            var counter =1;

            $('#kembali_ke_home').on('click', function () {
                window.location.href = BASE_URL;
            });
            $('#submit_form_permohonan_baru .button-submit').on('click', function () {
                var id_pdln = $('#id_pdln').val();
                App.blockUI({
                    boxed: true,
                    message: "Sedang diproses..."
                });
                $('#deskripsi_register').empty();
                $.ajax({
                    url: BASE_URL + 'layanan/baru/submit_permohonan',
                    dataType: "json",
                    type: "post",
                    data: $('#submit_form_permohonan_baru').serialize(),
                    success: function (res) {
                        if (res.status === true) {
                            
                                App.unblockUI();
                                $('#bukti_register').modal('show');
                                $('#deskripsi_register').append('<div class="item">'+
                                    '<div class="item-head">'+
                                    '<i class="fa fa-envelope" aria-hidden="true"></i>'+
                                    '<a class="item-name"> Permohonan Anda telah Dikirim Ke - '+res.msg+'</a><br>'+
                                    '<i class="fa fa-star" aria-hidden="true"></i>'+
                                    '<a class="item-name primary-link"> NO REGISTER :'+res.no_register+'</a>'+
                                    '</div>');
                                window.setTimeout(function(){ 
                                    window.location.href = BASE_URL;
                                }, 4000);
                        } else {
                            window.setTimeout(function () {
                                App.unblockUI();
                                $.notific8('' + res.msg, {
                                    heading: 'Error',
                                    theme: 'ruby',
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                }
                                );
                            }, 1000);
                        }
                    },
                    error: function () {
                        App.unblockUI();
                    }
                });
            });
            $('#submit_form_permohonan_baru input,select').change(function () {
                $('#submit_form_permohonan_baru').validate().element($(this));
            });
            $('#form_wizard_permohonan_baru').find('.button-previous').hide();
            $('#form_wizard_permohonan_baru').find('.button-next').hide();
        }
    };
}();
var HandlePeserta = function () {
    var handleLog = function () {
        var tabel_list_pemohon = $('#table_list_pemohon');
        var tabel_peserta = $('#table_list_peserta');
        var OTableListPeserta = tabel_peserta.dataTable();
        var OTable_list_pemohon = tabel_list_pemohon.dataTable({
            dom: '<"top"lf<"top_p"ip><"clear">>rt<"bottom"ip<"clear">>',
            scrollX:true,
            destroy: true,
            serverSide: true,
            processing: true,
            searching: true,
            ordering: true,
            info: true,
            paging: true,
            language: {
                url: BASE_URL + "assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
            },
            ajax: {
                url: BASE_URL + "layanan/baru/list_pemohon",
                type: 'POST'
            },
            lengthMenu: [
                [5],
                [5]
            ],
            pageLength: 5,
            columnDefs: [
                {visible: false, targets: 0, orderable: false, searchable: false},
                {visible: true, targets: 8, orderable: false, searchable: false}
            ],
        });

        tabel_peserta.on('click', '#edit_peserta', function () {
            $('#btn-find-peserta-det').prop("disabled", false);
            row = $(this).parents('tr')[0];
            var aData = OTableListPeserta.fnGetData(row);
            App.blockUI({
                boxed: true,
                message: "Sedang mencari data..."
            });
            $('#id_log_peserta').val(aData[0]);
            $.ajax({
                url: BASE_URL + "layanan/baru/get_data_peserta",
                data: {id_log_peserta: aData[0]},
                dataType: "json",
                type: "post",
                success: function (resp) {
                    $("input:radio[name=opt_pendanaan][value=" + resp.log_peserta.id_kategori_biaya + "]").prop('checked', true).trigger('change');
                    if(resp.log_peserta.id_kategori_biaya == "0"){
                      $('#instansi_tunggal').val(resp.biaya.id_instansi).trigger('change');
                      var log_uang =resp.biaya.biaya;
                      var uang_tunggal = log_uang.split('.')
                      $('#biaya_tunggal').val(uang_tunggal[0]);
                    }else if(resp.log_peserta.id_kategori_biaya == "1"){
                      $('#instansi_campuran_gov').val(resp.biaya.instansi_gov).trigger('change');
                      var log_uang_apbn =resp.biaya.biaya_apbn;
                      var uang_ganda = log_uang_apbn.split('.')
                      $('#biaya_tunggal').val(uang_ganda[0])
                      $('#biaya_campuran').val(resp.biaya.biaya_apbn);
                      $('#instansi_campuran_donor').val(resp.biaya.instansi_donor).trigger('change');
                      $.each(resp.detail, function (index, value) {
                          var val = value.id_jenis_biaya + "_" + value.by;
                          $('input:checkbox[name="check_jb[]"][value="' + val + '"]').prop('checked',true);
                      });
                    }
                    $('#waktu_penugasan').prop('disabled', false);
                    if(resp.log_peserta.start_date != ''){
                      var start = new Date(parseInt(resp.log_peserta.start_date*1000));
                      var end = new Date(parseInt(resp.log_peserta.end_date*1000));
                      $('#waktu_penugasan').val(start.getDate()  + "/" + (start.getMonth()+1) + "/" + start.getFullYear() + ' s/d ' +
                        end.getDate()  + "/" + (end.getMonth()+1) + "/" + end.getFullYear());
                      var days = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
                      $('#durasi').text("Durasi : " + days + ' Hari').show().append(" (* Termasuk Hari libur)");
                      $('#durasi').show();
                    }
                    $.ajax({
                        url: BASE_URL + "layanan/baru/get_data_pemohon",
                        dataType: "json",
                        type: "post",
                        data: {id_pemohon: resp.log_peserta.id_pemohon},
                        success: function (data) {
                            if (data.status === true) {
                                window.setTimeout(function () {
                                    $(".panel_peserta :input").prop('disabled', false);
                                    $('#jenis_peserta').html(data.jenis_peserta);
                                    $('#nip_peserta').html(data.nip_peserta);
                                    $('#nik_peserta').html(data.nik_peserta);
                                    $('#paspor_peserta').html(data.paspor_peserta);
                                    $('#nama_peserta').html(data.nama_peserta);
                                    $('#jabatan_peserta').html(data.jabatan_peserta);
                                    $('#instansi_peserta').html(data.instansi);
                                    $('#email_peserta').html(data.email_peserta);
                                    $('#telp_peserta').html(data.telp_peserta);
                                    $("#accordion-toggle-peserta").removeClass("collapsed");
                                    $("#collapse_data_peserta").addClass( "in" ).removeAttr("style");
                                    $("#accordion-toggle-wp").removeClass("collapsed");
                                    $("#collapse_waktu_penugasan").addClass("in").removeAttr("style");
                                    $("#accordion-toggle-biaya").removeClass("collapsed");
                                    $("#collapse_pembiayaan_peserta").addClass("in").removeAttr("style");
                                    $("#btn-save-peserta-det").prop("disabled", false);
                                    $(".panel_peserta :input:file").fileinput('enable');
                                    $('#id_peserta').val(resp.log_peserta.id_pemohon);
                                    $('#modal_list_pemohon').modal('hide');
                                    App.unblockUI('#table_list_pemohon');
                                }, 300);
                            }
                            if (data.status === false) {
                                window.setTimeout(function () {
                                    App.unblockUI('#table_list_pemohon');
                                    $('#modal_list_pemohon').modal('hide');
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
                            App.unblockUI('#table_list_pemohon');
                            bootbox.alert({
                                message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                        ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                                title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                            });
                            $('#simpan').text('Simpan');
                            $('#simpan').prop('disabled', false);
                        }
                    });
                    $.ajax({
                        url: BASE_URL + 'layanan/baru/get_saved_file_pemohon',
                        dataType: "json",
                        type: "post",
                        data: {id_pdln: $('#id_pdln').val(), id_jenis: $('#jenis_kegiatan').val(), id_peserta:resp.log_peserta.id_pemohon},
                        success: function (data) {
                          $('#file_doc_require_pemohon :input:file').each(function () {
                              $(this).rules("remove");
                          });
                          if (data.length > 0) {
                              $('#file_doc_require_pemohon').empty();
                              $.each(data, function (index, value) {
                                  span_require = '';
                                  if ((data[index].is_require) === true)
                                      span_require = '<span class="required" aria-required="true"> * </span>';

                                  if(data[index].dir_path != ''){
                                        $('#file_doc_require_pemohon').append('<div class="form-group">' +
                                                '<label class="control-label col-md-3">' + data[index].nama_doc +
                                                span_require +
                                                '</label>' +
                                                '<div class="col-md-9" id="show_'+ data[index].id_jenis_doc +'">' +
                                                '    <div class="col-md-4">' +
                                                '        <a class="btn btn-xs purple-intense s_pemohon_'+ data[index].id_jenis_doc +'" id="view_file_s_'+data[index].id_jenis_doc+'" name="view_file_s_'+data[index].id_jenis_doc+'" href=""><i class="fa fa-file-pdf-o"> </i> '+ data[index].nama_doc +'</a>' +
                                                '    </div>' +
                                                '    <div class="col-md-1">' +
                                                '        <button type="button" class="btn btn-xs red-mint" id="btn_del_'+data[index].id_jenis_doc+'"><i class="fa fa-remove"></i>&nbsp; Ubah</button>' +
                                                '    </div>' +
                                                '</div>' +
                                                '<div id="edit_'+ data[index].id_jenis_doc +'" style="display: none;">' +
                                                ' <div class="col-md-8">' +
                                                '   <input type="file" class="form-control" name="file_' + data[index].nama_doc + '" id="file_' + data[index].nama_doc + '" />' +
                                                '   <div id="errorBlock_file_' + data[index].nama_doc + '" class="help-block"></div>' +
                                                ' </div>' +
                                                '</div>' +
                                                '</div>');

                                        $('#btn_del_'+data[index].id_jenis_doc).click(function() {
                                            $('#edit_'+ data[index].id_jenis_doc).show();
                                            $('#show_'+ data[index].id_jenis_doc).hide();
                                        });

                                        $('.s_pemohon_'+data[index].id_jenis_doc).prop("href", data[index].dir_path);
                                        $('.s_pemohon_'+data[index].id_jenis_doc).fancybox({
                                            type: 'iframe',
                                            title: "Surat Pemohon",
                                            autoCenter: true,
                                            fitToView: false,
                                            width: '80%',
                                            height: '80%',
                                            autoSize: false,
                                            maxWidth: 800,
                                            maxHeight: 700,
                                            iframe: {
                                                preload: true,
                                                scrolling: 'auto'
                                            }
                                        });

                                  }else {
                                    $('#file_doc_require_pemohon').append('<div class="form-group">' +
                                            '<label class="control-label col-md-3">' + data[index].nama_doc +
                                            span_require +
                                            '</label>' +
                                            '<div class="col-md-8">' +
                                            '<input type="file" class="form-control" name="file_' + data[index].nama_doc + '" id="file_' + data[index].nama_doc + '" />' +
                                            '<div id="errorBlock_file_' + data[index].nama_doc + '" class="help-block"></div>' +
                                            '</div>' +
                                            '</div>');
                                  }

                                  $('#file_doc_require_pemohon #file_' + data[index].nama_doc + '').fileinput({
                                      language: 'id',
                                      showPreview: true,
                                      allowedPreviewTypes: ['pdf'],
                                      allowedFileExtensions: ['pdf'],
                                      elErrorContainer: '#errorBlock_file_' + data[index].nama_doc + '',
                                      maxFileSize: 2000,
                                      maxFileCount: 1,
                                      autoReplace: true,
                                      maxFilesNum: 1,
                                      showUpload: true,
                                      uploadAsync: false,
                                      showCaption: true,
                                      uploadUrl: BASE_URL + "layanan/baru/upload_file_peserta",
                                      dropZoneEnabled: false,
                                      'uploadExtraData': function () {
                                          return {
                                              jenis_doc: data[index].nama_doc,
                                              name_attr: "file_" + data[index].nama_doc,
                                              id_pdln: $('#id_pdln').val(),
                                              id_jenis_doc: data[index].id_jenis_doc,
                                              id_peserta: $('#id_peserta').val(),
                                              kategori_doc: 2,
                                              type_file: ".pdf"
                                          };
                                      }
                                  });
                                  if ((data[index].is_require) === true) {
                                      $('#file_doc_require_pemohon #file_' + data[index].nama_doc + '').rules("add", {
                                          required: true,
                                          extension: "pdf",
                                          messages: {
                                              required: "Harap Unggah File"
                                          }
                                      });
                                      $('#file_doc_require_pemohon #file_' + data[index].nama_doc + '').on('filebatchuploadsuccess', function (event, data, previewId, index) {
                                          var form = data.form, files = data.files, extra = data.extra,
                                                  response = data.response, reader = data.reader;
                                          $(this).rules('remove');
                                      });
                                  }
                              });
                          }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            window.setTimeout(function () {
                                App.unblockUI();
                                $.notific8('Mohon maaf koneksi gagal.....', {
                                    heading: 'Error',
                                    theme: 'lemon',
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                }
                                );
                            }, 1000);
                        }
                    });
                    window.setTimeout(function () {
                        App.unblockUI();
                        console.log(resp);
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-find-peserta-det').prop("disabled", false);
                    window.setTimeout(function () {
                        App.unblockUI();
                        $.notific8('Mohon maaf koneksi gagal.....', {
                            heading: 'Error',
                            theme: 'ebony',
                            life: 2000,
                            horizontalEdge: 'bottom',
                            verticalEdge: 'left'
                        }
                        );
                    }, 1000);
                }
            });
        });
        tabel_peserta.on('click', '#remove_peserta', function () {
            row = $(this).parents('tr')[0];
            var aData = OTableListPeserta.fnGetData(row);
            bootbox.dialog({
                message: "Apakah anda yakin untuk menghapus data peserta " + '<span class="strong label label-danger">' + aData[4] + '</span>' + " ?",
                title: "Hapus Data",
                buttons: {
                    success: {
                        label: "Hapus",
                        className: "red",
                        callback: function () {
                            App.blockUI({
                                boxed: true,
                                message: "Sedang proses penghapusan...."
                            });
                            $.ajax({
                                url: BASE_URL + "layanan/baru/delete_peserta",
                                type: "post",
                                data: {id_log_peserta: aData[0], id_pdln: $('#id_pdln').val()},
                                dataType: "json",
                                success: function (data) {
                                    if (data.status === true) {
                                        window.setTimeout(function () {
                                            OTableListPeserta.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8("Data sukses terhapus....", {
                                                heading: 'Sukses',
                                                theme: 'teal',
                                                life: 2000,
                                                horizontalEdge: 'bottom',
                                                verticalEdge: 'left'
                                            }
                                            );
                                        }, 500);
                                    } else {
                                        window.setTimeout(function () {
                                            OTableListPeserta.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8("Gagal hapus data...", {
                                                heading: 'Error',
                                                theme: 'ruby',
                                                life: 2000,
                                                horizontalEdge: 'bottom',
                                                verticalEdge: 'left'
                                            }
                                            );
                                        }, 500);
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    window.setTimeout(function () {
                                        App.unblockUI();
                                        $.notific8('Mohon maaf koneksi gagal.....', {
                                            heading: 'Error',
                                            theme: 'ebony',
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

        $('#instansi_tunggal').on('change', function(){
            if ($('#instansi_tunggal').val() > 0 && $('#id_peserta').val() > 0 && $('#waktu_penugasan').val() != '') {
                $('#btn-save-peserta-det').prop('disabled',false);
            }

        });
        $('#instansi_campuran_gov').on('change', function(){
            if ($('#instansi_campuran_gov').val() > 0 && $('#id_peserta').val() > 0 && $('#waktu_penugasan').val() != '') {
                $('#btn-save-peserta-det').prop('disabled',false);
            }
        });

        $('#instansi').on('change', function(){
            $('#instansi_lainnya').prop("value",'',true);
            $('#instansi_lainnya').prop("readonly",true);
            if($(this).val() === 'any'){
                $('#instansi_lainnya').prop("readonly",false);
            }
        });
        $('#btn-find-peserta-det').click(function () {
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            window.setTimeout(function () {
                App.unblockUI();
                $('#modal_list_pemohon').modal('show');
                $('#modal_list_pemohon .modal-title .title-text').text(" List Data Pemohon");
            }, 800);
        });
        tabel_list_pemohon.on('click', '#btn_set_peserta', function () {
            App.blockUI({
                target: "#table_list_pemohon",
                overlayColor: "none",
                animate: !0
            });
            row = $(this).parents('tr')[0];
            var aData = OTable_list_pemohon.fnGetData(row);
            $.ajax({
                url: BASE_URL + "layanan/baru/get_data_pemohon",
                dataType: "json",
                type: "post",
                data: {id_pemohon: aData[0]},
                success: function (data) {
                    if (data.status === true) {
                        window.setTimeout(function () {
                            $(".panel_peserta :input").prop('disabled', false);
                            $('#jenis_peserta').html(data.jenis_peserta);
                            $('#nip_peserta').html(data.nip_peserta);
                            $('#nik_peserta').html(data.nik_peserta);
                            $('#paspor_peserta').html(data.paspor_peserta);
                            $('#nama_peserta').html(data.nama_peserta);
                            $('#jabatan_peserta').html(data.jabatan_peserta);
                            $('#instansi_peserta').html(data.instansi);
                            $('#email_peserta').html(data.email_peserta);
                            $('#telp_peserta').html(data.telp_peserta);
                            $("#accordion-toggle-peserta").removeClass("collapsed");
                            $("#collapse_data_peserta").addClass( "in" ).removeAttr("style");
                            if ($('#instansi_tunggal').val() > 0 && $('#id_peserta').val() > 0 && $('#waktu_penugasan').val() != '') {
                                $('#btn-save-peserta-det').prop('disabled',false);
                            }else if ($('#instansi_campuran_gov').val() > 0 && $('#id_peserta').val() > 0 && $('#waktu_penugasan').val() != '') {
                                $('#btn-save-peserta-det').prop('disabled',false);
                            }else{
                                $('#btn-save-peserta-det').prop('disabled',true);
                            }
                            $(".panel_peserta :input:file").fileinput('enable');
                            $('#id_peserta').val(aData[0]);
                            $('#waktu_penugasan').prop('disabled', false);
                            $('#modal_list_pemohon').modal('hide');
                            $('#kegiatan').change();
                            App.unblockUI('#table_list_pemohon');
                        }, 300);
                    }
                    if (data.status === false) {
                        window.setTimeout(function () {
                            App.unblockUI('#table_list_pemohon');
                            $('#modal_list_pemohon').modal('hide');
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
                    App.unblockUI('#table_list_pemohon');
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
    };
    return {
        init: function () {
            handleLog();
        }
    };
}();
jQuery(document).ready(function () {
    $.fn.select2.defaults.set('language', 'id');
    $.fn.datepicker.defaults.language = 'id';
    FormWizardPermohonanBaru.init();
    HandlePeserta.init();
    $('#btn_m_upload').on('click',function(e){
        e.preventDefault();
        $('#mass_upload').modal('show');
    });
});

$('#fileUpload').fileinput({
    uploadExtraData:{id_pdln:$('#id_pdln').val()},
    uploadUrl: BASE_URL+"/layanan/mass_upload/process_peserta",
    allowedFileExtensions : ['csv'],
    showPreview: false,
    uploadAsync: true,
});
$('#fileUpload').on('fileuploaderror', function(event, data, previewId, index) {
    var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
    bootbox.alert({
        message: '<span class="font-yellow"> Terjadi kesalahan, periksa kembali format data yang diunggah.</span>',
        title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
    });
    $('#mass_upload').modal('hide');
});

$('#fileUpload').on('fileuploaded', function(event, data, previewId, index) {
    var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
    displayPeserta();
    handlePulsate();
    //$("#table_list_peserta").load();
    $('#mass_upload').modal('hide');
});
$('#fileUpload').on('change', function(event) {
    var filename=$(this).val();
    if(filename.split('.').pop()!='csv'){
        bootbox.alert({
            message: '<span class="font-yellow"> Masukkan hanya file dengan ekstensi <b>.csv</b>.</span>',
            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
        });
    }
});
