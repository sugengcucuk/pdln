var HandleKegiatan = function () {
    var initTable = function () {
        var table = $('#tabel_kegiatan_manage'),
            save_method, row,form = $('#form_kegiatan'),
            error = $('.alert-danger', form);
        var oTable = table.dataTable({
            dom: '<"top"lf<"top_p"ip><"clear">>rt<"bottom"ip<"clear">>',
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
                url: BASE_URL + "page/helpdesk/kegiatan_request_list", // ajax source
                type: 'POST'
            },
            lengthMenu: [
                [5, 9, 20, -1],
                [5, 9, 20, "Semua"] // change per page values here
            ],
            pageLength: 9,
            "columnDefs": [{
                    "visible": false,
                    "targets": 0,
                    "name": "id",
                },
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": 1
                },
                {
                    "name": "judul",
                    "targets": 2
                },
                {
                    "name": "help_tipe",
                    "targets": 3
                },
                {
                    "name": "r_negara.nmnegara",
                    "targets": 4
                },
                {
                    "name": "r_kota.nmkota",
                    "targets": 5
                },
                {
                    "name": "m_kegiatan.StartDate",
                    "targets": 6
                },
                // {
                //     "name": "m_kegiatan.Status",
                //     "targets": 7
                // },
                {
                    "targets": 7,
                    "searchable": false,
                    "orderable": false
                },
            ],
        });
        $("input").change(function () {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });

         $("#Negara, #JenisKegiatan, #Tujuan").select2({
            placeholder: "Silahkan Pilih",
            dropdownAutoWidth: true,
            width : 'auto',
            allowClear: true,
            debug: true
        });

        $('#cancel').on('click',function() {
            window.location.href = BASE_URL + "page/helpdesk/";
        })
        $('#send_koment').on('click',function() {

            var coment = $('#comenting').val();
            var id_tiket = $('#ID').val();
            var judul = $('#judul').val();
            var JenisKegiatan = $('#JenisKegiatan').val();
            var form = $(this);
            var add = {comenting:coment,id_tiket:id_tiket,judul:judul,JenisKegiatan:JenisKegiatan}

            if (id_tiket != '') {

                if (coment != '') {
                    App.blockUI({
                        boxed: true,
                        message: 'Sedang di proses....'
                    });
                    $.ajax({
                        url : BASE_URL+"page/helpdesk/add_koment",
                        type: "POST",
                        dataType: 'JSON',
                        data: add,
                        success: function(data)
                        {
                            if(data.status === true){
                                $('#modal_new_kegiatan').load(document.URL +  ' #modal_new_kegiatan');
                                 $(form).fadeOut(800, function(){
                                    $('#string_judul_input').show();
                                    $('#judul').hide();
                                    $('#JenisKegiatan').val($('#jenis_ask').val()).trigger('change');
                                    App.unblockUI();
                                    form.html('Kirim').fadeIn().delay(2000);
                                    $('#comenting').val('');

                                });
                            }
                        }
                    });
                }
            }else{
                $.ajax({
                    url : BASE_URL+"page/helpdesk/add_ask",
                    type: "POST",
                    dataType: 'JSON',
                    data: add,
                    success: function(data)
                    {
                        if(data.status === true){
                            $('#modal_new_kegiatan').load(document.URL +  ' #modal_new_kegiatan');
                             $(form).fadeOut(800, function(){
                                $('#judul').hide();
                                $('#string_judul_input').show();
                                $('#JenisKegiatan').val($('#jenis_ask').val()).trigger('change');
                                App.unblockUI();
                                form.html('Kirim').fadeIn().delay(2000);
                                $('#comenting').val('');

                                window.location.href = BASE_URL + "page/helpdesk/ask/"+data.id_tiket;

                            });
                        }
                    }
                });

            }

        })

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
        $('#JenisKegiatan').val($('#jenis_ask').val()).trigger('change');
        var id_tiket = $('#ID').val();
        if (id_tiket != '') {
            $('#judul').hide();
        }else{
            $('#string_judul_input').hide();
        }

    });
}