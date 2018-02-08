var HandleSurat_Masuk = function () {
    var initTable = function () {
        var table = $('#tabel_surat_masuk_manage'),
                save_method, row, form = $('#form_surat_masuk'),
                error = $('.alert-danger', form);
        var oTable = table.dataTable({
            serverSide: true,
            processing: true,
            searching: true,
            ordering: false,
            info: true,
            paging: true,
            language: {
                url: BASE_URL + "assets/global/plugins/datatables/plugins/i18n/Indonesian-Alternative.json"
            },
            ajax: {
                url: BASE_URL + "umum/surat_masuk/surat_masuk_list", // ajax source
                type: 'POST'
            },
            lengthMenu: [
                [5, 10, 20, -1],
                [5, 10, 20, "Semua"] // change per page values here
            ],
            pageLength: 10,
            "columnDefs": [
                {"visible": false, "targets": 0, "orderable": false}
                //{ "visible": false, "targets": 0 , "orderable" : false}
            ],
        });
        $("input").change(function () {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
        $('#new_surat_masuk').on('click', function () {
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            save_method = "tambah";
            $('#form_surat_masuk')[0].reset();
            $('.form-group').removeClass('has-error');
            $("#id_instansi_asal").change();
            $("#id_instansi_asal").val('');
            $("#id_instansi_asal").trigger('change');
            $("#nama_petugas_intansi").change();
            $("#nama_petugas_intansi").val('');
            $("#nama_petugas_intansi").trigger('change');
            $('.help-block').empty();
            $('#method').val("tambah");
            $('#form_surat_masuk').validate().resetForm();
            $('#print_resi').hide();
            $('#selesai').hide();
            $('#modal_new_surat_masuk').modal('show');
            $('.modal-title .title-text').text(" Tambah Surat masuk");
        });
        $('#modal_new_surat_masuk').on('click', '#simpan', function (e) {
            if (form.valid() === false)
                return false;

            e.preventDefault();
            $('#simpan').text('Simpan...');
            $('#simpan').prop('disabled', true);
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });

            var formData = new FormData();
            formData.append('file', $('input[type=file]')[0].files[0]);

            $.ajax({
                url: BASE_URL + "umum/surat_masuk/surat_masuk_save",
                type: "POST",
                dataType: 'JSON',
                data: $('#form_surat_masuk').serialize(),
                success: function (data)
                {
                    if (data.status === true) {

                        $.ajax({
                            url: BASE_URL + "umum/surat_masuk/attachment_save/" + data.ID,
                            type: "POST",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data)
                            {

                            }
                        });

                        if (save_method === "ubah") {
                            $('#modal_new_surat_masuk').modal('hide');
                            save_method = "ubah";
							window.setTimeout(function () {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Data Surat masuk telah di simpan', {
                                    heading: 'Sukses',
                                    theme: 'teal',
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                });
                            }, 1000);
                        }
                        if (save_method === "tambah") {
                            // $('#modal_new_surat_masuk').modal('hide');
                            save_method = "tambah";
                            window.setTimeout(function () {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $('#ID').val(data.ID);
                                $('#print_resi').show();
                                $('#selesai').show();
                                $('#simpan').hide();
                                $('#batal').hide();
                                $.notific8('Data Surat masuk telah di tambah', {
                                    heading: 'Sukses',
                                    theme: 'teal', // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                }
                                );
                            }, 1000);

                        }

                    } else
                    {
                        if ((data.msg) && (data.status === false)) {
                            window.setTimeout(function () {
                                oTable.api().ajax.reload();
                                App.unblockUI();
                                $.notific8('Error , ' + data.msg, {
                                    heading: 'Info',
                                    theme: 'ruby', // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    life: 2000,
                                    horizontalEdge: 'bottom',
                                    verticalEdge: 'left'
                                }
                                );
                            }, 1000);
                        } else {
                            App.unblockUI();
                            for (var i = 0; i < (data.inputerror).length; i++)
                            {
                                $('[name="' + data.inputerror[i] + '"]')
                                        .parent().parent()
                                        .addClass('has-error');
                                $('[name="' + data.inputerror[i] + '"]')
                                        .next()
                                        .text(data.error_string[i]);
                            }
                        }
                    }
                    $('#simpan').text('Simpan');
                    $('#simpan').prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    App.unblockUI();
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
        table.on('click', '#delete_surat_masuk', function (e) {
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
                        callback: function () {
                            App.blockUI({
                                boxed: true,
                                message: 'Sedang di proses....'
                            });
                            $.ajax({
                                url: BASE_URL + "umum/surat_masuk/surat_masuk_delete",
                                type: "POST",
                                dataType: 'JSON',
                                data: {ID: aData[0]},
                                success: function (data)
                                {
                                    // another theme : teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                    if (data.success) {
                                        window.setTimeout(function () {
                                            oTable.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8('Data Terhapus', {
                                                heading: 'Info',
                                                theme: 'teal', // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
                                                life: 2000,
                                                horizontalEdge: 'bottom',
                                                verticalEdge: 'left'
                                            }
                                            );
                                        }, 1000);
                                    } else {
                                        window.setTimeout(function () {
                                            oTable.api().ajax.reload();
                                            App.unblockUI();
                                            $.notific8('Error , Data Tidak dapat Terhapus', {
                                                heading: 'Info',
                                                theme: 'ruby', // teal, amethyst,ruby, tangerine, lemon, lime, ebony, smoke
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
                                        message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                                ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                                        title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
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
        $('#print_resi').on('click', function (e) {
            e.preventDefault();
            ID = $('#ID').val();
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            window.open(BASE_URL + "umum/surat_masuk/print_resi_surat_masuk/" + ID, '_blank');
        });

        table.on('click', '#download_surat_masuk', function (e) {
            e.preventDefault();
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);

            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            $.ajax({
                url: BASE_URL + "umum/surat_masuk/get_data_surat_masuk",
                type: "POST",
                dataType: 'JSON',
                data: {ID: aData[0]},
                success: function (data)
                {
                    if (data.status === true) {
                        if ((data.path_dokumen === "") || (data.path_dokumen === "nofile") || (typeof data.path_dokumen === 'undefined') || (data.path_dokumen === "null"))
                        {
                            App.unblockUI();
                            bootbox.alert({
                                message: '<span class="font-yellow"> Mohon maaf dokumen surat masuk belum di upload.</span> <br />' +
                                        ' <strong> Silahkan upload dokumen terlebih dahulu. </strong>',
                                title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                            });
                        } else {
                            window.open(data.path_dokumen);
                        }
                    } else {
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-yellow"> Mohon maaf data bermasalah.</span> <br />' +
                                    ' <strong> Sistem tidak dapat menarik informasi dari database. </strong>',
                            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                        });
                    }
                    $('#simpan').text('Simpan');
                    $('#simpan').prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    App.unblockUI();
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

        $('#modal_new_surat_masuk').on('click', '#selesai', function (e) {
            e.preventDefault();
            $('#simpan').text('Simpan...');
            $('#simpan').prop('disabled', true);
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });

            $('#modal_new_surat_masuk').modal('hide');
            window.setTimeout(function () {
                oTable.api().ajax.reload();
                App.unblockUI();
                $.notific8('Proses pendaftaran surat masuk sudah selesai', {
                    heading: 'Sukses',
                    theme: 'teal',
                    life: 2000,
                    horizontalEdge: 'bottom',
                    verticalEdge: 'left'
                });
            }, 1000);
        });


        $('#id_instansi_asal').on('change', function () {
            id_instansi = $('#id_instansi_asal').val();
            $.post(BASE_URL + 'umum/surat_masuk/get_unit_kerja', {id_instansi: id_instansi}, function (data) {
                if (data !== '') {
                    $("#id_unit_kerja_asal").val('');
                    $("#id_unit_kerja_asal").trigger("change");
                    $("#id_unit_kerja_asal").html(data);
                }
            });
        });

        $('#nama_petugas_intansi').on('change', function () {
            id_petugas = $('#nama_petugas_intansi').val();
            $.post(BASE_URL + 'umum/surat_masuk/get_nohp_petugas', {id_petugas: id_petugas}, function (data) {
                if (data !== '') {
                    $("#no_telp_petugas_intansi").val('');
                    $("#no_telp_petugas_intansi").trigger("change");
                    $("#no_telp_petugas_intansi").val(data);
                }
            });
        });

        table.on('click', '#edit_surat_masuk', function (e) {
            e.preventDefault();
            save_method = "ubah";
            $('.form-control').parent().removeClass('has-error');
            $('.help-block').empty();
            $('.modal-title .title-text').text("Ubah Data Surat masuk");
            row = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(row);

            $('#ID').val(aData[0]);
            $('#print_resi').show();
            $('#selesai').hide();
            $('#method').val("ubah");

            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            $.ajax({
                url: BASE_URL + "umum/surat_masuk/get_data_surat_masuk",
                type: "POST",
                dataType: 'JSON',
                data: {ID: aData[0]},
                success: function (data)
                {
                    if (data.status === true) {
                        $('#id_instansi_asal').val(data.id_instansi_asal);
                        $('#id_instansi_asal').change();
                        window.setTimeout(function () {
                            App.unblockUI();
                            $("#id_jenis_kegiatan").val(data.id_jenis_kegiatan).trigger("change");
                            $("#id_jenis_kegiatan").trigger("change");
                            $("#id_unit_kerja_asal").val(data.id_unit_kerja_asal).trigger("change");
                            $("#id_unit_kerja_asal").trigger("change");
                            $('#nomor_surat').val(data.nomor_surat);
                            $('#hal').val(data.hal);
                            $('#nama_petugas_intansi').val(data.nama_petugas).trigger("change");
                            ;
                            $("#nama_petugas_intansi").trigger("change");
                            $('#no_telp_petugas_intansi').val(data.no_hp);
                            $('#tanggal_surat').val(data.tanggal_surat);
                            $('#nomor_register').val(data.nomor_register);
                            $('#user_create').val(data.user_create);
                            $('#user_create').prop('readonly', true);
                            $('#Path .fileinput-filename').text(data.dokumen);
                            $("#bagian_pemroses").val(data.bagian_pemroses).trigger("change");
                            $("#bagian_pemroses").trigger("change");
                            $('#modal_new_surat_masuk').modal('show');
                        }, 1000);
                    } else {
                        App.unblockUI();
                        bootbox.alert({
                            message: '<span class="font-yellow"> Mohon maaf data bermasalah.</span> <br />' +
                                    ' <strong> Sistem tidak dapat menarik informasi dari database. </strong>',
                            title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                        });
                    }
                    $('#simpan').text('Simpan');
                    $('#simpan').prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    App.unblockUI();
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


        $('#form_surat_masuk').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input             
            rules: {
                nomor_surat: {
                    required: true
                },
                id_jenis_kegiatan: {
                    required: true
                },
                tanggal_surat: {
                    required: true
                },
                id_instansi_asal: {
                    required: true
                },
                hal: {
                    required: true
                },
                bagian_pemroses: {
                    required: true
                }
            },
            messages: {
                id_jenis_kegiatan: {
                    required: 'Harap pilih salah satu',
                },
                nomor_surat: {
                    required: 'Nomor Surat tidak boleh kosong',
                },
                tanggal_surat: {
                    required: "Tanggal Surat tidak boleh kosong",
                },
                id_instansi_asal: {
                    required: 'Harap pilih salah satu',
                },
                hal: {
                    required: "Hal tidak boleh kosong",
                },
                bagian_pemroses: {
                    required: "Bagian Pemroses tidak boleh kosong",
                }
            },
            onkeyup: function (element, event) {
                if ($(element).prop('name') === "Name") {
                    return false; // disable for your element named as "name"
                } else { // else use the default on everything else
                    if (event.which === 9 && this.elementValue(element) === "") {
                        return;
                    } else if (element.name in this.submitted || element === this.lastElement) {
                        this.element(element);
                    }
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit                
                error.fadeTo(3000, 500).slideUp(500, function () {});
                App.scrollTo(error, -200);
            },
            errorPlacement: function (error, element) {
                if (element.is(':radio')) {
                    error.insertAfter(element.closest(".mt-radio-inline"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
        });
        $('input,select', form).change(function () {
            $('#form_surat_masuk').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
        $('#batal').on('click', function () {
            $('#form_surat_masuk').validate().resetForm();
        });

        $("#id_jenis_kegiatan, #id_instansi_asal, #id_unit_kerja_asal,#bagian_pemroses,#nama_petugas_intansi").select2({
            placeholder: "Silahkan Pilih",
            dropdownAutoWidth: true,
            width: 'auto',
            allowClear: true,
            debug: true
        });

        $('#tanggal_surat').datepicker({
            locale: 'id',
            format: 'd-m-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        var countries = new Bloodhound({
            datumTokenizer: function (d) {
                return Bloodhound.tokenizers.whitespace(d.nama_petugas);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            limit: 10,
            prefetch: {
                url: BASE_URL + 'umum/surat_masuk/get_list_petugas',
                filter: function (list) {
                    return $.map(list, function (nama_petugas) {
                        return {name: nama_petugas};
                    });
                }
            }
        });

        countries.initialize();

        if (App.isRTL()) {
            $('#nama_petugas_intansi').attr("dir", "rtl");
        }
        /*
         $('#nama_petugas_intansi').typeahead(null, {
         name: 'nama_petugas_intansi',
         displayKey: 'nama_petugas',
         hint: (App.isRTL() ? false : true),
         source: countries.ttAdapter()
         });
         */

        $('#new_petugas').on('click', function () {
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            save_method = "tambah";
            $('#form_petugas')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_new_petugas').modal('show');

        });

        $('#modal_new_petugas').on('click', '#simpan_petugas', function (e) {

            e.preventDefault();
            $('#simpan_petugas').text('Simpan...');
            $('#simpan_petugas').prop('disabled', true);
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });

            $.ajax({
                url: BASE_URL + "umum/surat_masuk/petugas_save",
                type: "POST",
                dataType: 'JSON',
                data: $('#form_petugas').serialize(),
                success: function (data)
                {
                    if (data.status === true) {
                        window.setTimeout(function () {
                            App.unblockUI();
                            $('#modal_new_petugas').modal('hide');
                            $.post(BASE_URL + 'umum/surat_masuk/get_petugas', function (data) {
                                if (data !== '') {
                                    $("#nama_petugas_intansi").val('');
                                    $("#nama_petugas_intansi").trigger("change");
                                    $("#nama_petugas_intansi").html(data);
                                }
                            });

                            bootbox.alert({
                                message: '<span class="font-green"> Berhasil.</span> <br />' +
                                        ' <strong> Data Petugas Baru Berhasil Ditambah </strong>',
                                title: '<span class="font-blue bold"> <strong> <i class="fa fa-success"> </i> Berhasil!! </strong><span>'
                            });
                        }, 1000);
                    } else
                    {
                        if ((data.msg) && (data.status === false)) {
                            window.setTimeout(function () {
                                App.unblockUI();
                                bootbox.alert({
                                    message: '<span class="font-yellow"> Mohon maaf data bermasalah.</span> <br />' +
                                            ' <strong> ' + data.msg + ' </strong>',
                                    title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                                });
                            }, 1000);
                        } else {
                            App.unblockUI();
                            for (var i = 0; i < (data.inputerror).length; i++)
                            {
                                $('[name="' + data.inputerror[i] + '"]')
                                        .parent().parent()
                                        .addClass('has-error');
                                $('[name="' + data.inputerror[i] + '"]')
                                        .next()
                                        .text(data.error_string[i]);
                            }
                        }
                    }
                    $('#simpan_petugas').text('Simpan');
                    $('#simpan_petugas').prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    App.unblockUI();
                    bootbox.alert({
                        message: '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />' +
                                ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
                        title: '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
                    });
                    $('#simpan_petugas').text('Simpan');
                    $('#simpan_petugas').prop('disabled', false);
                }
            });
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
    jQuery(document).ready(function () {
        HandleSurat_Masuk.init();
    });
}