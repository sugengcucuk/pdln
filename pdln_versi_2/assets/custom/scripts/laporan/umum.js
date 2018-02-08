var HandleLaporan = function () {
    var initTable = function () {
        var table = $('#tabel_laporan_umum');
        var oTable = table.dataTable({
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
                url: BASE_URL + "laporan/laporan/search_laporan", // ajax source
                type: 'POST',
                data: function (d) {
                    return $.extend({}, d, {
                        "tgl_from": $('#tgl_from').val(),
                        "tgl_to": $('#tgl_to').val(),
                        "instansi": $('#instansi').val(),
                        "kegiatan": $('#kegiatan').val(),
                        "status": $('#status').val()
                    });
                }
            },
            lengthMenu: [
                [5, 10, 20, -1],
                [5, 10, 20, "Semua"] // change per page values here
            ],
            pageLength: 10,
            "columnDefs": [
                {"visible": false, "targets": 0}
            ],
        });

    };
    return {
        //main function to initiate the module
        init: function () {
            initTable();

            $('#kegiatan').select2({
                placeholder: 'Pilih Kegiatan',
                dropdownAutoWidth: false,
                width: 'null',
                allowClear: true,
                debug: true
            });

            $('#instansi').select2({
                placeholder: 'Pilih Instansi',
                dropdownAutoWidth: false,
                width: 'null',
                allowClear: true,
                debug: true
            });

            $('#status').select2({
                placeholder: 'Pilih Status',
                dropdownAutoWidth: false,
                width: 'null',
                allowClear: true,
                debug: true
            });

            $('#tgl_from').datepicker({
                locale: 'id',
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('#tgl_to').datepicker({
                locale: 'id',
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('#btn-apply').on('click', function () {
                App.blockUI({
                    boxed: true,
                    message: "Sedang di proses.."
                });

                $('#tabel_laporan_umum').DataTable().ajax.reload();
                App.unblockUI();

            });

            $('#btn-export').on('click', function () {
                App.blockUI({
                    boxed: true,
                    message: "Sedang di proses.."
                });
                var url = BASE_URL + "laporan/laporan/export_umum";
                url = url + "?tgl_from=" + $('#tgl_from').val();
                url = url + "&tgl_to=" + $('#tgl_to').val();
                url = url + "&instansi=" + $('#instansi').val();
                url = url + "&kegiatan=" + $('#kegiatan').val();
                url = url + "&status=" + $('#status').val();

                window.location = url;
                App.unblockUI();

            });
        }
    };
}();
if (App.isAngularJsApp() === false) {
    jQuery(document).ready(function () {
        HandleLaporan.init();
    });
}
