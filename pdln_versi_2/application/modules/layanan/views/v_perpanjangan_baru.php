<div class="row step-no-background">
    <div class="col-md-12">
        <!-- BEGIN PORTLET WIZARD PERPANJANGAN/RALAT BARU -->
        <div class="portlet light bordered" id="form_wizard_perpanjangan_baru" name="form_wizard_perpanjangan_baru">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-red"></i>
                    <span class="caption-subject font-red bold uppercase"><span class="step-title"> Langkah 1 dari 4 </span> </span>
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-horizontal" action="javascript:;" id="submit_form_perpanjangan_baru" name="submit_form_perpanjangan_baru" autocomplete="off">
                    <input id="id_pdln" name="id_pdln" type="hidden" value="">
                    <input id="id_pdln_lama" name="id_pdln_lama" type="hidden" value="">
                    <input id="tmp_kegiatan" name="tmp_kegiatan" type="hidden" value="">
                    <!-- BEGIN FORM WIZARD PERPANJANGAN/RALAT BARU -->
                    <div class="form-wizard">
                        <!-- BEGN FORM BODY -->
                        <div class="form-body">
                            <ul class="nav nav-pills nav-justified steps">
                                <li class="active">
                                    <a href="#tab1" data-toggle="tab" class="step">
                                        <span class="number"> 1 </span>
                                        <span class="desc">
                                            <i class="fa fa-check"></i> Detail Umum </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab2" data-toggle="tab" class="step">
                                        <span class="number"> 2 </span>
                                        <span class="desc">
                                            <i class="fa fa-check"></i> Detail Kegiatan </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab3" data-toggle="tab" class="step">
                                        <span class="number"> 3 </span>
                                        <span class="desc">
                                            <i class="fa fa-check"></i> Detail Peserta </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab4" data-toggle="tab" class="step">
                                        <span class="number"> 4 </span>
                                        <span class="desc">
                                            <i class="fa fa-check"></i> Konfirmasi </span>
                                    </a>
                                </li>
                            </ul>
                            <div id="bar" class="progress progress-striped" role="progressbar">
                                <div class="progress-bar progress-bar-success" style="width: 25%;"> </div>
                            </div>
                            <!-- BEGIN TAB CONTENT -->
                            <div class="tab-content">
                                <div class="alert alert-danger display-none">
                                    <button class="close" data-dismiss="alert"></button> Data anda bermasalah, harap cek kembali.. </div>
                                <div class="alert alert-success display-none">
                                    <button class="close" data-dismiss="alert"></button> Form Perpanjangan/Ralat anda valid </div>
                                <div class="tab-pane active" id="tab1">
                                    <h4 class="form-section">Detail Umum</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Cari Nomor Register sebelumnya yang akan diperpanjang / diralat</label>
                                        <div class="col-md-4">
                                            <input type="text" name="no_reg" id="no_reg" class="form-control" placeholder="<Nomor Register>" />
                                        </div>
                                        <div class="col-md-5">
                                            <button type="button" id="btn-find-reg" name="btn-find-reg" class="btn blue" title="Cari">
                                                <i class="fa fa-search"> </i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Jenis Permohonan</label>
                                        <div class="control col-md-6">
                                            <div class="mt-radio-inline">
                                                <label class="mt-radio">
                                                    <input type="radio" name="opt_jenis_permohonan" id="opt_jenis_permohonan" value="0" /> Perpanjangan
                                                    <span></span>
                                                </label>
                                                <label class="mt-radio">
                                                    <input type="radio" name="opt_jenis_permohonan" id="opt_jenis_permohonan" value="1" /> Ralat
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr/>
                                    <p>
                                        <span class="label label-danger"> * Diisi Oleh Pemohon (Satker) </span>
                                    </p>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Surat Usulan Satker (Opsional)</label>
                                        <div class="col-md-5">
                                            <input type="file" class="form-control" name="file_surat_usulan" id="file_surat_usulan" />
                                        </div>
                                        <div class="col-md-4">
                                            <a href="<?php echo base_url(); ?>assets" target="_blank">View Surat Usulan Satker</a>
                                        </div>
                                    </div>
                                    <p>
                                        <span class="label label-danger">* Diisi Oleh Unit Focal Point (K/L) </span>
                                    </p>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Level Pejabat
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <select name="level_pejabat" id="level_pejabat" class="form-control" >
                                                <option value="">Pilih Level Pejabat</option>
                                                <?php foreach ($level_pejabat as $level) { ?>
                                                    <option value="<?php echo $level->id; ?>"><?php echo $level->nama; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">No.Surat Usulan Focal Point
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="no_surat_usulan_focal_point" id="no_surat_usulan_focal_point" placeholder="<No Surat Usulan Unit Focal Point>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Tanggal Surat Usulan
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="datepicker" class="form-control" id="tgl_surat_usulan" name="tgl_surat_usulan" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Surat Usulan Focal Point
                                            <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-9" id="show_fsp_fp" style="display: none;">
                                            <div class="col-md-3">
                                                <a class="btn btn-xs purple-intense s_focal_point_usulan" id="view_file_pemohon" name="view_file_pemohon" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Focal Point </a>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-xs red-mint" id="btn_del_file_surat_fp"><i class="fa fa-remove"></i>&nbsp; Ubah</button>
                                            </div>
                                        </div>
                                        <!-- Edit again -->
                                        <div id="edit_fsp_fp" style="display: none;">
                                            <div class="col-md-4">
                                                <input type="file" name="file_surat_usulan_fp" id="file_surat_usulan_fp" accept=".pdf" class="form-control" placeholder="Pilih File" />
                                            </div>
                                            <span class="label label-info">*Note<span class="small bold font-white">  Extensi File yang diizinkan [.pdf]</span></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Pejabat Penandatangan Surat Permohonan
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="pejabat_sign_permohonan" id="pejabat_sign_permohonan" placeholder="<Pejabat Penandatangan>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <h4 class="form-section">Detail Kegiatan</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Jenis Kegiatan
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-control">
                                                <option value="">Pilih</option>
                                                <?php foreach ($jenis_kegiatan as $jenis) { ?>
                                                    <option value="<?php echo $jenis->ID; ?>"><?php echo $jenis->Nama; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Nama Kegiatan
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <select name="kegiatan" id="kegiatan" class="form-control">
                                                <option value="">Pilih Kegiatan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <button type="button" class="btn btn-info" id="btn-add-kegiatan" name="btn-add-kegiatan" title="Tambah Kegiatan">
                                                <i class="fa fa-plus"></i> Tambah Kegiatan
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Penyelenggara
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" name="penyelenggara" id="penyelenggara" class="form-control" placeholder="Penyelenggara" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Negara Tujuan
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" name="negara" id="negara" class="form-control" placeholder="Negara" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Kota Tujuan
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" name="kota" id="kota" class="form-control" placeholder="Nama Kota" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"> Mulai
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <div class='input-group'>
                                                <input type="text" class="form-control" id="tgl_mulai_kegiatan" name="tgl_mulai_kegiatan" readonly />

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"> Selesai
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <div class='input-group'>
                                                <input type="text" class="form-control" id="tgl_akhir_kegiatan" name="tgl_akhir_kegiatan" readonly />

                                            </div>
                                        </div>
                                    </div>					

                                    <h4 class="form-section file_doc">Kelengkapan Dokumen Kegiatan</h4>
                                    <div id="file_doc_require">
                                        <!-- based on database automate generate element via jquery script -->
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab3">
                                    <div class="note note-danger">
                                        <h4 class="block">Informasi</h4>
                                        <p>Silahkan Lengkapi Data isian Waktu Penugasan, Kelengkapan Dokumen serta Pembiayaan untuk masing-masing Peserta.</p>
                                    </div>
                                    <hr/>
                                    <div class="row" >
                                        <div class="col-sm-2" id="find_peserta">
                                            <button type="button" class="btn btn-circle btn-outline btn-sm blue-madison" id="btn-find-peserta-det" name="btn-find-peserta-det" title="Cari Data Peserta"><i class="fa fa-search"> </i> Cari Data Peserta </button>
                                        </div>
                                    </div>
                                    <br/>
                                    <!-- BEGIN PANEL PESERTA  -->
                                    <div class="panel_peserta" id="panel_peserta">
                                        <input type="hidden" id="id_peserta" name="id_peserta" class="form-control" />
                                        <div class="panel-group accordion" id="accordion_tab3_peserta">
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle accordion-toggle-styled collapsed" id="accordion-toggle-peserta" data-toggle="collapse" data-parent="#accordion_tab3_peserta" href="#collapse_data_peserta" aria-expanded="false"><i class="fa fa-user"></i> Biodata Peserta </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_data_peserta" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-2">
                                                                <button type="button" class="btn btn-sm btn-block purple-intense">JENIS</button>
                                                            </div>
                                                            <div class="col-sm-1"><span class="bold text-center">:</span></div>
                                                            <div class="col-sm-7">
                                                                <p class="bold text-left" id="jenis_peserta"></p>
                                                            </div>
                                                        </div><br/>
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-2">
                                                                <button type="button" class="btn btn-sm btn-block purple-intense">NIP/NRP</button>
                                                            </div>
                                                            <div class="col-sm-1"><span class="bold text-center">:</span></div>
                                                            <div class="col-sm-7">
                                                                <p class="text-uppercase bold text-left" id="nip_peserta"></p>
                                                            </div>
                                                        </div><br/>
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-2">
                                                                <button type="button" class="btn btn-sm btn-block purple-intense">NIK </button>
                                                            </div>
                                                            <div class="col-sm-1"><span class="bold text-center">:</span></div>
                                                            <div class="col-sm-7">
                                                                <p class="bold text-left" id="nik_peserta"></p>
                                                            </div>
                                                        </div><br/>
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-2">
                                                                <button type="button" class="btn btn-sm btn-block purple-intense">NAMA</button>
                                                            </div>
                                                            <div class="col-sm-1"><span class="bold text-center">:</span></div>
                                                            <div class="col-sm-7">
                                                                <p class="bold text-left" id="nama_peserta"></p>
                                                            </div>
                                                        </div><br/>
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-2">
                                                                <button type="button" class="btn btn-sm btn-block purple-intense">JABATAN </button>
                                                            </div>
                                                            <div class="col-sm-1"><span class="bold text-center">:</span></div>
                                                            <div class="col-sm-7">
                                                                <p class="text-uppercase bold text-left" id="jabatan_peserta"></p>
                                                            </div>
                                                        </div><br/>
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-2">
                                                                <button type="button" class="btn btn-sm btn-block purple-intense">INSTANSI </button>
                                                            </div>
                                                            <div class="col-sm-1"><span class="bold text-center">:</span></div>
                                                            <div class="col-sm-7">
                                                                <p class="text-capitalize bold text-left" id="instansi_peserta"></p>
                                                            </div>
                                                        </div><br/>
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-2">
                                                                <button type="button" class="btn btn-sm btn-block purple-intense">EMAIL </button>
                                                            </div>
                                                            <div class="col-sm-1"><span class="bold text-center">:</span></div>
                                                            <div class="col-sm-7">
                                                                <p class="bold text-left" id="email_peserta"></p>
                                                            </div>
                                                        </div><br/>
                                                        <div class="row">
                                                            <div class="col-sm-offset-2 col-sm-2">
                                                                <button type="button" class="btn btn-sm btn-block purple-intense">TELP/HP </button>
                                                            </div>
                                                            <div class="col-sm-1"><span class="bold text-center">:</span></div>
                                                            <div class="col-sm-7">
                                                                <p class="text-uppercase bold text-left" id="telp_peserta"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle accordion-toggle-styled collapsed" id="accordion-toggle-wp" data-toggle="collapse" data-parent="#accordion_tab3_peserta" href="#collapse_waktu_penugasan" aria-expanded="false"><i class="fa fa-calendar-check-o"></i> Waktu Penugasan </a>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div id="collapse_waktu_penugasan" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Waktu Penugasan
                                                                <span class="required strong">*</span>
                                                            </label>
                                                            <div class="col-md-4">
                                                                <div class='input-group date'>
                                                                    <input type="text" class="form-control" id="waktu_penugasan" name="waktu_penugasan" />
                                                                    <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                </div>
                                                                <em class="help-block required">* Jika lama kegiatan 1 Hari, klik pada tanggal yang sama sebanyak 2 kali(x).</em>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <a id="durasi" class="btn btn-outline btn-sm btn-circle red-mint"></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle accordion-toggle-styled collapsed" id="accordion-toggle-file" data-toggle="collapse" data-parent="#accordion_tab3_peserta" href="#collapse_dok_peserta" aria-expanded="false"><i class="fa fa-file-pdf-o"> </i> Kelengkapan Dokumen Peserta</a>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div id="collapse_dok_peserta" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                    <div class="panel-body">
                                                        <div id="file_doc_require_pemohon">
                                                            <!-- based on database automate generate element via jquery script -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle accordion-toggle-styled collapsed" id="accordion-toggle-biaya" data-toggle="collapse" data-parent="#accordion_tab3_peserta" href="#collapse_pembiayaan_peserta" aria-expanded="false"><i class="fa fa-money"></i> Pembiayaan Peserta</a>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div id="collapse_pembiayaan_peserta" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3"> Pendanaan
                                                                <span class="required" aria-required="true"> * </span>
                                                            </label>
                                                            <div class="control col-md-6">
                                                                <div class="mt-radio-inline">
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="opt_pendanaan" id="opt_pendanaan" value="0" /> Pendanaan Tunggal
                                                                        <span></span>
                                                                    </label>
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="opt_pendanaan" id="opt_pendanaan" value="1" /> Pendanaan Campuran
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- BEGIN PANEL TUNGGAL -->
                                                        <div class="panel panel-info" id="panel_tunggal">
                                                            <div class="panel-body">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Nama Instansi
                                                                        <span class="required strong">*</span>
                                                                    </label>
                                                                    <div class="col-md-4">
                                                                        <select name="instansi_tunggal" id="instansi_tunggal" class="form-control">
                                                                            <option value="">Pilih Instansi</option>
                                                                            <?php foreach ($list_instansi as $instansi) { ?>
                                                                                <option value="<?php echo $instansi->ID; ?>"><?php echo $instansi->Nama; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Jumlah Biaya APBN</label>
                                                                    <div class="col-md-4">
                                                                        <input type="text" name="biaya_tunggal" id="biaya_tunggal" class="form-control" placeholder="<Rp. xxx.xxx.xxx.xxx>"/>
                                                                    </div>
                                                                    <div class="col-xs-5">
                                                                        <span class="font-red small">* Hanya yang ditanggung oleh Pemerintah</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END PANEL TUNGGAL -->
                                                        <!-- BEGIN PANEL CAMPURAN -->
                                                        <div class="panel panel-info" id="panel_campuran">
                                                            <div class="panel-body">
                                                                <span class="title bold">*Pemerintah</span>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Nama Instansi</label>
                                                                    <div class="col-md-4">
                                                                        <select name="instansi_campuran_gov" id="instansi_campuran_gov" class="form-control">
                                                                            <option value="">Pilih Instansi</option>
                                                                            <?php foreach ($list_instansi as $instansi) { ?>
                                                                                <option value="<?php echo $instansi->ID; ?>"><?php echo $instansi->Nama; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Jumlah Biaya APBN</label>
                                                                    <div class="col-md-4">
                                                                        <input type="text" name="biaya_campuran" id="biaya_campuran" class="form-control" placeholder="<Rp. xxx.xxx.xxx.xxx>"/>
                                                                    </div>
                                                                    <div class="col-xs-5">
                                                                        <span class="font-red small">* Hanya yang ditanggung oleh Pemerintah</span>
                                                                    </div>
                                                                </div>
                                                                <span class="title bold">*Donor</span>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Nama Instansi</label>
                                                                    <div class="col-md-4">
                                                                        <select name="instansi_campuran_donor" id="instansi_campuran_donor" class="form-control">
                                                                            <option value="">Pilih Instansi</option>
                                                                            <?php foreach ($list_instansi as $instansi) { ?>
                                                                                <option value="<?php echo $instansi->ID; ?>"><?php echo $instansi->Nama; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <br/>
                                                                <div class="table-responsive">
                                                                    <table class="table table-condensed table-bordered" id="tabel_pembiayaan" >
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="5" class="text-center">KOLOM PEMBIAYAAN</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th width="3%" class="text-center" style="display: none;">ID Pembiayaan</th>
                                                                                <th width="3%" class="text-center">No.</th>
                                                                                <th width="37%">Jenis Biaya</th>
                                                                                <th class="text-center" width="20%">Checklist Pemerintah</th>
                                                                                <th class="text-center" width="20%">Checklist Donor</th>
                                                                                <th class="text-center" width="20%">Checklist Biaya Sendiri</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $no = 1;
                                                                            foreach ($jenis_pembiayaan as $biaya) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td class="text-center" style="display: none;"><?php echo $biaya->ID; ?></td>
                                                                                    <td class="text-center"><?php echo $no++ . '.' ?></td>
                                                                                    <td><?php echo $biaya->Description ?></td>
                                                                                    <td class="text-center">
                                                                                        <input type="checkbox" value="<?php echo $biaya->ID . '_'; ?>1" name="check_jb[]" id="<?php echo $biaya->Name; ?>" />
                                                                                        <span></span>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input type="checkbox" value="<?php echo $biaya->ID . '_'; ?>2" name="check_jb[]" id="<?php echo $biaya->Name; ?>" />
                                                                                        <span></span>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input type="checkbox" value="<?php echo $biaya->ID . '_'; ?>3" name="check_jb[]" id="<?php echo $biaya->Name; ?>" />
                                                                                        <span></span>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END PANEL CAMPURAN -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <button type="button" class="btn btn-circle red-soft" id="btn-save-peserta-det" name="btn-save-peserta-det" title="Simpan Data Peserta" disabled="true"><i class="fa fa-save"> </i> Simpan Data Peserta </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END PANEL PESERTA  -->
                                    <div class="clearfix"></div>
                                    <h4 class="form-section">List Peserta</h4>
                                    <div class="table">
                                        <table class="table table-condensed table-striped table-hover table-bordered" id="table_list_peserta">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="display:none;">ID LOG PESERTA</th>
                                                    <th class="text-center" style="vertical-align: middle;">No.</th>
                                                    <th class="text-center" style="vertical-align: middle;"> NIP </th>
                                                    <th class="text-center" style="vertical-align: middle;"> NIK </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Nama Peserta </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Jabatan </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Tanggal Penugasan </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Jenis Pembiayaan </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Instansi Peserta </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Jumlah Biaya </th>
                                                    <th class="text-center" width="25%" style="vertical-align: middle;"> Aksi </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab4">
                                    <h3 class="block">Konfirmasi</h3>
                                    <h3 class="block">Konfirmasi</h3>
                                    <h4 class="form-section">Detail Umum</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Surat Usulan Satker (Opsional)</label>
                                        <div class="col-md-4">
                                            <a class="btn btn-info s_pemohon_usulan" id="view_file_pemohon" name="view_file_pemohon" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Pemohon(Satker)</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Level Pejabat
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="level_pejabat"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">No. Surat Usulan Focal Point
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="no_surat_usulan_focal_point"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Tanggal Surat Usulan
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="tgl_surat_usulan_fp"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Surat Usulan Focal Point
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <a class="btn btn-info s_focal_point_usulan" id="view_file_pemohon" name="view_file_pemohon" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Focal Point </a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Pejabat Penandatangan Surat Permohonan</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="pejabat_sign_permohonan"> </p>
                                        </div>
                                    </div>
                                    <h4 class="form-section">Detail Kegiatan</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Jenis Kegiatan</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="jenis_kegiatan"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Nama Kegiatan</label>
                                        <div class="col-md-9">
                                            <p class="form-control-static" data-display="kegiatan"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Negara Tujuan</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="negara"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Kota Tujuan</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="kota"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Mulai</label>
                                        <div class="col-md-9">
                                            <p class="form-control-static" data-display="tgl_mulai_kegiatan"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Sampai</label>
                                        <div class="col-md-9">
                                            <p class="form-control-static" data-display="tgl_akhir_kegiatan"> </p>
                                        </div>
                                    </div>
                                    <h4 class="form-section">Detail Peserta</h4>
                                    <div class="table-responsive">
                                        <table class="table table-condensed table-striped table-hover table-bordered" id="table_list_peserta_confirm">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="4%" style="vertical-align: middle;">No.</th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> NIP </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> NIK </th>
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Nama Peserta </th>
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Jabatan </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Email </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Telepon </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Jenis Pembiayaan </th>
                                                    <th class="text-center" width="10%" style="vertical-align: middle;"> Nama Instansi </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Jumlah Biaya </th>
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Aksi </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center" style="vertical-align: middle;">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <button type="button" class="btn btn-xs btn-info" title="Ubah Data Peserta"><i class="fa fa-edit"></i></button>
                                                        <button type="button" class="btn btn-xs btn-danger" title="Hapus Data Peserta"><i class="fa fa-remove"></i> </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix"></div>
                                    <br/>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Disclaimer SIMPDLN Biro KTLN Kementerian Sekretariat Negara</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="well">
                                                <p>
                                                    All the information on this website is published in good faith and for general information purpose only.
                                                    ktln.setneg.go.id/simpdln does not make any warranties about the completeness, reliability and accuracy of this information.
                                                    Any action you take upon the information you find on this website (ktln.setneg.go.id/simpdln), is strictly at your own risk.
                                                    ktln.setneg.go.id/simpdln will not be liable for any losses and/or damages in connection with the use of our website.
                                                </p>
                                                <p>
                                                    From our website, you can visit other websites by following hyperlinks to such external sites.
                                                    While we strive to provide only quality links to useful and ethical websites, we have no control over the content and nature of these sites.
                                                    These links to other websites do not imply a recommendation for all the content found on these sites.
                                                    Site owners and content may change without notice and may occur before we have the opportunity to remove a link which may have gone 'bad'.
                                                </p>
                                                <p>
                                                    Please be also aware that when you leave our website, other sites may have different privacy policies and terms which are beyond our control.
                                                    Please be sure to check the Privacy Policies of these sites as well as their "Terms of Service" before engaging in any business or uploading any information.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <div class="col-md-10">
                                            <div class="md-checkbox has-success">
                                                <input type="checkbox" id="checkbox30" class="md-check">
                                                <label for="checkbox30">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> By using our website, you hereby consent to our disclaimer and agree to its terms.</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END TAB CONTENT -->
                        </div>
                        <!-- END FORM BODY -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <a href="javascript:;" class="btn default button-previous disabled" style="display: none;"><i class="fa fa-angle-left"> </i> Kembali </a>
                                    <a href="javascript:;" class="btn btn-outline green button-next"> Lanjutkan <i class="fa fa-angle-right"> </i></a>
                                    <a href="javascript:;" class="btn btn-outline yellow button-draft"> Simpan Draft <i class="fa fa-save"></i></a>
                                    <a href="javascript:;" class="btn green button-submit" style="display: none;" disabled> Kirim Permohonan <i class="fa fa-check"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END FORM WIZARD PERPANJANGAN/RALAT BARU -->
                </form>
            </div>
        </div>
        <!-- END PORTLET WIZARD PERPANJANGAN/RALAT BARU -->
    </div>
</div>
<!-- END ROW -->
<!-- BEGIN MODAL LIST DATA PERMOHONAN -->
<div class="modal fade" id="modal_list_permohonan" name="modal_list_permohonan" role="dialog" tabindex="-1" aria-labelledby="modal_list_permohonan" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text">List Data Permohonan</span>
                    </h3>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-advanced table-striped table-condensed table-hover table-bordered" id="table_list_permohonan">
                            <thead>
                                <tr>
                                    <th class="text-center">ID PDLN</th>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">No Register</th>
                                    <th class="text-center">Tanggal Register</th>
                                    <th class="text-center">No Surat SP</th>
                                    <th class="text-center">Tanggal SP</th>
                                    <th class="text-center">Jenis Kegiatan</th>
                                    <th class="text-center">Negara Tujuan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Pilih</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn blue-dark"> <i class="fa fa-left"> </i> Kembali </button>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL LIST DATA PERMOHONAN -->
<!-- BEGIN MODAL LIST DATA PEMOHON -->
<div class="modal fade" id="modal_list_pemohon" name="modal_list_pemohon" role="dialog" tabindex="-1" aria-labelledby="modal_list_pemohon" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text">List Data Pasien</span>
                    </h3>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-advanced table-striped table-condensed table-hover table-bordered" id="table_list_pemohon">
                            <thead>
                                <tr>
                                    <th class="text-center">ID Pemohon</th>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">NIP/NRP</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Jabatan</th>
                                    <th class="text-center">Instansi</th>
                                    <th class="text-center">Jenis Pemohon</th>
                                    <th class="text-center">Pilih</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn blue-dark"> <i class="fa fa-left"> </i> Kembali </button>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL LIST DATA PEMOHON -->

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fileinput/css/fileinput.min.css" />
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/fileinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/locales/id.min.js"></script>

<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>

<!-- <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" /> -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<!-- <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> -->
<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/perpanjangan.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/form_wizard_perpanjangan.js" type="text/javascript"></script>
