var FormEditTask = function () {
    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }
            var form = $('#submit_form_permohonan_baru');
            var table_list_peserta_confirm = $('#table_list_peserta_confirm');
            var oTablePesertaConfirm = table_list_peserta_confirm.DataTable();
            var table_list_peserta = $('#table_list_peserta');
            var oTablePeserta = table_list_peserta.DataTable();
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form),
                    data_table_peserta;
            oTablePesertaConfirm = table_list_peserta_confirm.DataTable({
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
                    url: BASE_URL + "kotak_surat/approval/get_list_peserta_view/" + $('#id_pdln').val(),
                },
                columnDefs: [
                    {visible: false, targets: 0, searchable: false},
                ],
                pageLength: -1
            }).draw();
            oTablePeserta = table_list_peserta.DataTable({
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
                    url: BASE_URL + "kotak_surat/approval/get_list_peserta_view/" + $('#id_pdln').val(),
                },
                columnDefs: [
                    {visible: false, targets: 0, searchable: false},
                ],
                pageLength: -1
            }).draw();
            $('#disclaimer_aggrement').on('click' ,function(e) {

                $("#pembatalan").prop('disabled',false);

            });

            $('#submit_form_permohonan_baru').on('click', '#cancel', function (e) {
                window.setTimeout(function () {
                    window.location.href = BASE_URL + "layanan/pembatalan";
                }, 300);
            });
            $('#submit_form_permohonan_baru').on('click', '#pembatalan', function (e) {

                var id_pdln = $('#id_pdln').val();
                App.blockUI({
                    boxed: true,
                    message: "Sedang diproses..."
                });

                $.ajax({
                    url: BASE_URL + 'layanan/modify/submit_pembatalan/'+id_pdln,
                    dataType: "json",
                    type: "post",
                    data: $('#submit_form_permohonan_baru').serialize(),
                    success: function (res) {
                        if (res.status === true) {
                            $('#pembatalan').prop("disabled", true);
                            App.unblockUI();

                            bootbox.alert({
                                message: '<span class="font-blue"> Permohonan Berhasil diajukan</span> <br />' +
                                        'Nomer Registri : <strong>'+res.no_register+' </strong>  Permohonan telah dikirim ke <strong> '+res.msg+'  </strong>',
                                title: '<span class="font-blue bold"> <strong> <i class="fa fa-info"> </i> Terimakasih </strong><span>'
                            });
                            window.setTimeout(function () {
                                window.location.href = BASE_URL + "layanan/pembatalan";
                            }, 2000);
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
            $('#submit_form_permohonan_baru').on('click', '#lanjutkan', function (e) {
                e.preventDefault();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang di proses....'
                });
                $.ajax({
                    url: BASE_URL + "kotak_surat/approval/lanjutkan",
                    type: "POST",
                    dataType: 'JSON',
                    data: $('#submit_form_permohonan_baru').serialize(),
                    success: function (data)
                    {
                        if (data.status === true) {
                            window.setTimeout(function () {
                                App.unblockUI();
                                $.notific8('Approval Berhasil Dilakukan', {
                                    heading: 'Sukses',
                                    theme: 'teal',
                                    life: 500,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);

                            window.setTimeout(function () {
                                location.href = BASE_URL + "kotak_surat/approval/task";
                            }, 2000);

                        } else
                        {
                            App.unblockUI();
                            bootbox.alert({
                                message: '<span class="font-yellow"> Gagal submit persetujuan.</span> <br />' +
                                        data.message,
                                title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                    ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                        });
                    }
                });
            });

            //Karo setuju
            $('#submit_form_permohonan_baru').on('click', '#setuju', function (e) {
                e.preventDefault();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang di proses....'
                });
                $.ajax({
                    url: BASE_URL + "kotak_surat/approval/setuju",
                    type: "POST",
                    dataType: 'JSON',
                    data: $('#submit_form_permohonan_baru').serialize(),
                    success: function (data)
                    {
                        if (data.status === true) {
                            window.setTimeout(function () {
                                App.unblockUI();
                                $.notific8('Surat Persetujuan Berhasil Di Approve', {
                                    heading: 'Sukses',
                                    theme: 'teal',
                                    life: 500,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);
                            $('#g_setujui_pengajuan').hide();
                            $('#g_catatan').hide();
                            $('#g_kirim_persetujuan').show();
                        } else
                        {
                            App.unblockUI();
                            bootbox.alert({
                                message: '<span class="font-yellow"> Gagal submit persetujuan.</span> <br />' +
                                        data.message,
                                title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                    ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                        });
                    }
                });
            });

            //Karo setuju
            $('#submit_form_permohonan_baru').on('click', '#kirim_persetujuan', function (e) {
                e.preventDefault();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang di proses....'
                });
                window.setTimeout(function () {
                    App.unblockUI();
                    $.notific8('Surat Persetujuan Dikirimkan', {
                        heading: 'Sukses',
                        theme: 'teal',
                        life: 500,
                        horizontalEdge: 'bottom',
                        verticalEdge: 'left'
                    });
                }, 1000);

                window.setTimeout(function () {
                    location.href = BASE_URL + "kotak_surat/approval/task";
                }, 2000);

            });


            $('#tanggal_surat').datepicker({
                locale: 'id',
                format: 'd MM yyyy',
                autoclose: true,
                todayHighlight: true
            });

            $('#submit_form_permohonan_baru').on('click', '#lanjutketu', function (e) {
                e.preventDefault();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang di proses....'
                });
                $.ajax({
                    url: BASE_URL + "kotak_surat/approval/lanjutketu",
                    type: "POST",
                    dataType: 'JSON',
                    data: $('#submit_form_permohonan_baru').serialize(),
                    success: function (data)
                    {
                        if (data.status === true) {
                            window.setTimeout(function () {
                                App.unblockUI();
                                $.notific8('Surat Perssetujuan Berhasil Di Approve', {
                                    heading: 'Sukses',
                                    theme: 'teal',
                                    life: 500,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);

                            window.setTimeout(function () {
                                location.href = BASE_URL + "kotak_surat/approval/edit_task/" + data.id_pdln;
                            }, 2000);
                        } else
                        {
                            App.unblockUI();
                            bootbox.alert({
                                message: '<span class="font-yellow"> Gagal submit persetujuan.</span> <br />' +
                                        data.message,
                                title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                    ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                        });
                    }
                });
            });

            $('#submit_form_permohonan_baru').on('click', '#tu_setuju', function (e) {
                e.preventDefault();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang di proses....'
                });
                $.ajax({
                    url: BASE_URL + "kotak_surat/approval/tu_setuju",
                    type: "POST",
                    dataType: 'JSON',
                    data: $('#submit_form_permohonan_baru').serialize(),
                    success: function (data)
                    {
                        if (data.status === true) {
                            window.setTimeout(function () {
                                App.unblockUI();
                                $.notific8('Approval Berhasil Dilakukan', {
                                    heading: 'Sukses',
                                    theme: 'teal',
                                    life: 500,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);
                            $('#g_kirim_persetujuan').show();
                        } else
                        {
                            App.unblockUI();
                            bootbox.alert({
                                message: '<span class="font-yellow"> Gagal submit persetujuan.</span> <br />' +
                                        data.message,
                                title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                    ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                        });
                    }
                });
            });

            $('#submit_form_permohonan_baru').on('click', '#tolak', function (e) {
                e.preventDefault();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang di proses....'
                });

                $.ajax({
                    url: BASE_URL + "kotak_surat/approval/tolak",
                    type: "POST",
                    dataType: 'JSON',
                    data: $('#submit_form_permohonan_baru').serialize(),
                    success: function (data)
                    {
                        if (data.status === true) {
                            window.setTimeout(function () {
                                App.unblockUI();
                                $.notific8('Persetujuan Berhasil Di Tolak , Dan dikembalikan ke Unit Pemohon !!! ', {
                                    heading: 'Sukses',
                                    theme: 'teal',
                                    life: 500,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);

                            window.setTimeout(function () {
                                location.href = BASE_URL + "kotak_surat/approval/task";
                            }, 2000);

                        } else
                        {
                            App.unblockUI();
                            bootbox.alert({
                                message: '<span class="font-yellow"> Gagal tolak persetujuan.</span> <br />' +
                                        data.message,
                                title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                    ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                        });
                    }
                });

            });

            $('#submit_form_permohonan_baru').on('click', '#detail_tembusan', function (e) {
                e.preventDefault();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang di proses....'
                });
                $.ajax({
                    url: BASE_URL + "kotak_surat/approval/get_detail_tembusan",
                    type: "POST",
                    dataType: 'JSON',
                    data: $('#submit_form_permohonan_baru').serialize(),
                    success: function (data)
                    {
                        if (data.status === true) {
                            App.unblockUI();
                            bootbox.alert({
                                message: '<span class="font-yellow"> ' + data.nama_format + '</span> <br />' +
                                        data.message,
                                title: '<span class="font-red bold"> <strong> <i class="fa fa-success"> </i> Detail Format Tembusan </strong><span>'
                            });

                        } else
                        {
                            App.unblockUI();
                            bootbox.alert({
                                message: '<span class="font-yellow"> Gagal menampilkan detail.</span> <br />' +
                                        data.message,
                                title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Kosong!! </strong><span>'
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                    ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                        });
                    }
                });
            });

            $('#view_surat').on('click', function (e) {
                e.preventDefault();
                App.blockUI({
                    boxed: true,
                    message: 'Sedang di proses....'
                });

                id_pdln = $("#id_pdln").val();
                jenis_preview = $("#jenis_preview").val();
                if(jenis_preview === 'Pembatalan'){
                    	pdf_url = BASE_URL + "kotak_surat/approval/print_pembatalan/" + id_pdln;
                }else if(jenis_preview === 'Perpanjangan'){
                    	pdf_url = BASE_URL + "kotak_surat/approval/print_perpanjangan/" + id_pdln;
                }else if(jenis_preview === 'Ralat'){
                    	pdf_url = BASE_URL + "kotak_surat/approval/print_ralat/" + id_pdln;
                }else{
               		pdf_url = BASE_URL + "kotak_surat/approval/print_permohonan/" + id_pdln;
                }
                // pdf_url = BASE_URL + "kotak_surat/approval/download/" + id_pdln;
                App.unblockUI();
                window.open(pdf_url, "SP Permohonan PDLN");
                return false;

            });

            var link_surat_pemohon;
            var link_surat_focal_point;
            $.ajax({
                url: BASE_URL + 'kotak_surat/approval/get_file_path',
                data: {
                    id_pdln: $('#id_pdln').val()
                },
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    if (data.status === true) {
                        link_surat_pemohon = data.path_pemohon;
                        link_surat_focal_point = data.path_focal_point;
                        if (data.status_file_pemohon === false) {
                            $(".s_pemohon_usulan").hide();
                            $('#edit_fsp').show();
                            $('#show_fsp').hide();

                            $(".s_pemohon_usulan_c").hide();
                            $('#edit_fsp_c').show();
                            $('#show_fsp_c').hide();

                            if (data.status_file_fp === false) {
                                $(".s_focal_point_usulan").hide();
                                $('#show_fsp_fp').hide();
                                $('#edit_fsp_fp').show();

                                $(".s_focal_point_usulan_c").hide();
                                $('#show_fsp_fp_c').hide();
                                $('#edit_fsp_fp_c').show();
                            } else {
                                $('#show_fsp_fp').show();
                                $(".s_focal_point_usulan").show();
                                $(".s_focal_point_usulan").prop("href", link_surat_focal_point);
                                $('#edit_fsp_fp').hide();

                                $('#show_fsp_fp_c').show();
                                $(".s_focal_point_usulan_c").show();
                                $(".s_focal_point_usulan_c").prop("href", link_surat_focal_point);
                                $('#edit_fsp_fp_c').hide();
                            }
                        } else {
                            $('#edit_fsp').hide();
                            $('#show_fsp').show();
                            $(".s_pemohon_usulan").show();
                            $(".s_pemohon_usulan").prop("href", link_surat_pemohon);

                            $('#edit_fsp_c').hide();
                            $('#show_fsp_c').show();
                            $(".s_pemohon_usulan_c").show();
                            $(".s_pemohon_usulan_c").prop("href", link_surat_pemohon);

                            if (data.status_file_fp === false) {
                                $(".s_focal_point_usulan").hide();
                                $('#show_fsp_fp').hide();
                                $('#edit_fsp_fp').show();

                                $(".s_focal_point_usulan_c").hide();
                                $('#show_fsp_fp_c').hide();
                                $('#edit_fsp_fp_c').show();
                            } else {
                                $('#show_fsp_fp').show();
                                $(".s_focal_point_usulan").show();
                                $(".s_focal_point_usulan").prop("href", link_surat_focal_point);
                                $('#edit_fsp_fp').hide();

                                $('#show_fsp_fp_c').show();
                                $(".s_focal_point_usulan_c").show();
                                $(".s_focal_point_usulan_c").prop("href", link_surat_focal_point);
                                $('#edit_fsp_fp_c').hide();
                            }
                        }
                    } else
                        App.unblockUI();

                },
                error: function (jqXHR, errorThrown, text) {
                    App.unblockUI();
                }
            });
            $('.s_pemohon_usulan,.s_pemohon_usulan_c').fancybox({
                type: 'iframe',
                overlayShow: true,
                title: "Surat Pemohon",
                autoCenter: true,
                fitToView: true,
                width: '80%',
                height: '80%',
                autoSize: false,
                maxWidth: 800,
                maxHeight: 800,
                transitionIn: 'fade',
                transitionOut: 'fade',
                iframe: {
                    preload: true,
                    scrolling: 'auto'
                }
            });
            $('.s_focal_point_usulan,.s_focal_point_usulan_c').fancybox({
                type: 'iframe',
                overlayShow: true,
                title: "Surat Focal Point",
                autoCenter: true,
                fitToView: true,
                width: '80%',
                height: '80%',
                autoSize: false,
                maxWidth: 800,
                maxHeight: 800,
                transitionIn: 'fade',
                transitionOut: 'fade',
                iframe: {
                    preload: true,
                    scrolling: 'auto'
                }
            });

             var displayConfirm = function () {
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
                    } else if ($(this).attr("data-display") == 'payment[]') {
                        var payment = [];
                        $('[name="payment[]"]:checked', form).each(function () {
                            payment.push($(this).attr('data-title'));
                        });
                        $(this).html(payment.join("<br>"));
                    }
                });
            };

            var handleTitle = function (tab, navigation, index) {
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
                }
                App.scrollTo($('.page-title'));
            };

            // default form wizard
            $('#form_wizard_permohonan_baru').bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                onTabClick: function (tab, navigation, index, clickedIndex) {
                    success.hide();
                    error.hide();
                    if (form.valid() === false) {
                        return false;
                    } else
                        return true;

                    handleTitle(tab, navigation, clickedIndex);
                },
                onNext: function (tab, navigation, index) {
                    var currents = index;
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

jQuery(document).ready(function () {
    FormEditTask.init();
    $("#pembatalan").prop('disabled',true);
});

view_peserta=function(id){
    $('#txt_nama').html('');
    $('#txt_nik').html('');
    $('#txt_nip_nrp').html('');
    $('#txt_paspor').html('');
    $('#txt_jabatan').html('');
    $('#txt_instansi').html('');
    $('#txt_tgl').html('');
    $('#txt_biaya').html('');
    $('#txt_jenis').html('');
    $.post(
        BASE_URL +'kotak_surat/approval/get_data_peserta',
        {id_log_peserta:id},
        function(data){
            var d=JSON.parse(data);
            $('#txt_nama').html(d.peserta.nama_peserta);
            $('#txt_nik').html(d.peserta.nik);
            $('#txt_nip_nrp').html(d.peserta.nip_nrp);
            $('#txt_paspor').html(d.peserta.paspor);
            $('#txt_jabatan').html(d.peserta.jabatan);
            $('#txt_instansi').html(d.peserta.Nama);
            $('#txt_tgl').html(d.peserta.tgl);
            $('#txt_biaya').html('Rp. '+d.biaya);
            $('#txt_jenis').html(d.peserta.id_kategori_biaya==1?'Campuran':'Tunggal');
        }
    );
    $('#view_detail_peserta').modal('show');
};