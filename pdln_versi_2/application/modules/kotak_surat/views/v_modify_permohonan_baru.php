<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fileinput/css/fileinput.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<div class="row step-no-background">
    <div class="col-md-12">
        <!-- BEGIN PORTLET WIZARD PERMOHONAN BARU -->
        <div class="portlet light bordered" id="form_wizard_permohonan_baru" name="form_wizard_permohonan_baru">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-red"></i>
                    <span class="caption-subject font-red bold uppercase"><span class="step-title"> Langkah 1 dari 4 </span> </span>
                </div>
            </div>
            <div class="portlet-body">
                <form class="form form-horizontal" id="submit_form_permohonan_baru" role="form">
                    <!-- BEGIN FORM WIZARD PERMOHONAN BARU -->
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
                                <input id="id_log_peserta" name="id_log_peserta" type="hidden">
                                <input id="user_id_pemohon" name="user_id_pemohon" type="hidden">
                                <input id="user_id_fp" name="user_id_fp" type="hidden">
                                <input id="id_pdln" name="id_pdln" type="hidden" value="<?php echo $id_pdln; ?>">
                                <div class="alert alert-danger display-none">
                                    <button class="close" data-dismiss="alert"></button> Data anda bermasalah, harap cek kembali.. </div>
                                <div class="alert alert-warning display-none">
                                    <button class="close" data-dismiss="alert"></button> Data peserta masih kosong, harap isi... </div>
                                <div class="alert alert-success display-none">
                                    <button class="close" data-dismiss="alert"></button> Form Permohonan anda valid </div>
                                <?php if ($id_pdln_lama > 0){
                                    ?>
                                <div class="alert alert-info">
                                 <?php echo $jenis_permohonan; ?>   NO. Register Lama : <?php echo $no_register_lama;?>
                                </div>
                                <?php  }?>
                                <div class="tab-pane active" id="tab1">
                                    <h4 class="form-section">Detail Umum</h4>

                                    <div class="form-group">
                                            <label class="control-label col-md-3">Level Pejabat
                                                <span class="required" aria-required="true"> * </span>
                                            </label>
                                            <div class="col-md-4">
                                                <select name="level_pejabat" id="level_pejabat" class="form-control" >
                                                    <option value="">Pilih Level Pejabat</option>
                                                    <?php foreach ($level_pejabat as $level) { ?>
                                                        <option value="<?php echo $level->id; ?>" data-desc="<?php echo $level->deskripsi; ?>"><?php echo $level->nama; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-1" id="Description_level">
                                                <span class="label label-info" ><span class="small bold font-white"><i class="fa fa-question" aria-hidden="true"></i></span></span>
                                            </div>
                                        </div>

                                    <?php $id_user = $this->session->user_id;
                                    $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
                                     if ($level == LEVEL_PEMOHON ) {
                                     ?>

                                    <p>
                                        <span class="label label-danger"> * Diisi Oleh Unit Pemohon (Satker) </span>
                                    </p>
                                    <fieldset id="pemohon_form_set">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Surat Usulan Unit Pemohon
                                                <span class="required" aria-required="true"> * </span>
                                            </label>
                                            <div class="col-md-9" id="show_fsp" style="display: none;">
                                                <div class="col-md-4">
                                                    <a class="btn btn-xs purple-intense s_pemohon_usulan" id="view_file_pemohon_s_1" name="view_file_pemohon_s_1" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Pemohon (Satker)</a>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-xs red-mint" id="btn_del_file_sp"><i class="fa fa-remove"></i>&nbsp; Ubah</button>
                                                </div>
                                            </div>
                                            <!-- Edit again -->
                                            <div id="edit_fsp" style="display: none;">
                                                <div class="col-md-4">
                                                    <input type="file" name="file_surat_usulan_pemohon" id="file_surat_usulan_pemohon" accept=".pdf" class="form-control" placeholder="Pilih File" />
                                                </div>
                                                <span class="label label-info">*Note<span class="small bold font-white">  Extensi File yang diizinkan [.pdf Max 2(MB) Mega Byte)]</span></span>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">No.Surat Usulan Pemohon
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="no_surat_usulan_pemohon" id="no_surat_usulan_pemohon" placeholder="<No Surat Usulan Unit Pemohon>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Tanggal Surat Usulan Pemohon
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <div class='input-group date'>
                                                <input type="text" class="form-control" id="tgl_surat_usulan_pemohon" name="tgl_surat_usulan_pemohon" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <label class="control-label col-md-3">User Pemohon</label>
                                            <div class="col-md-5">
                                                    <span> <?php echo $nama_pemohon; ?></span>
                                                    <input type="hidden" name="list_pemohon" id="list_pemohon"
                                                    value="<?php echo $unit_pemohon; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <span class="font-red"><small><strong>* Tentukan Unit Pemohon jika belum di inisiasi.</strong></small></span>
                                            </div>
                                        </div>
                                    <?php }else{?>

                                    <p>
                                        <span class="label label-danger">* Diisi Oleh Unit Focal Point (K/L) </span>
                                    </p>
                                    <fieldset id="focal_point_form_set">

                                        <div class="form-group">
                                            <label class="control-label col-md-3">No.Surat Usulan Focal Point
                                                <span class="required" aria-required="true"> * </span>
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="no_surat_usulan_focal_point" id="no_surat_usulan_focal_point" placeholder="<No Surat Usulan Unit Focal Point>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Tanggal Surat Usulan Focal Point
                                                <span class="required" aria-required="true"> * </span>
                                            </label>
                                            <div class="col-md-4">
                                                <div class='input-group date'>
                                                    <input type="text" class="form-control" id="tgl_surat_usulan_fp" name="tgl_surat_usulan_fp" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
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
                                                <span class="label label-info">*Note<span class="small bold font-white">  Extensi File yang diizinkan [.pdf Max 2(MB) Mega Byte)]</span></span>
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

                                    </fieldset>
                                    <?php };?>
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
                                        <div class="col-md-5">
                                            <select name="kegiatan" id="kegiatan" class="form-control">
                                                <option value="">Pilih</option>
                                                <?php foreach ($kegiatan as $keg) { ?>
                                                    <option value="<?php echo $keg->ID; ?>"><?php echo $jenis->NamaKegiatan; ?></option>
<?php } ?>
                                            </select>
                                            <input type="hidden" name="fake_keg" id="fake_keg">
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
                                            <div class='input-group' id='tgl_mulai'>
                                                <input type="text" class="form-control" id="tgl_mulai_kegiatan" name="tgl_mulai_kegiatan" readonly />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"> Selesai
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <div class='input-group' id='tgl_akhir'>
                                                <input type="text" class="form-control" id="tgl_akhir_kegiatan" name="tgl_akhir_kegiatan" readonly />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
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
                                                        <a href="collapse_data_peserta" class="accordion-toggle accordion-toggle-styled collapsed" id="accordion-toggle-peserta" data-toggle="collapse"   data-parent="#accordion_tab3_peserta" aria-expanded="false"><i class="fa fa-user"></i> Biodata Peserta </a>
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
                                                                <button type="button" class="btn btn-sm btn-block purple-intense">Paspor </button>
                                                            </div>
                                                            <div class="col-sm-1"><span class="bold text-center">:</span></div>
                                                            <div class="col-sm-7">
                                                                <p class="bold text-left" id="paspor_peserta"></p>
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
                                                        <div class="row">
                                                            <a id="accordion-toggle-wp" data-toggle="collapse" data-parent="#accordion_tab3_peserta" href="#collapse_waktu_penugasan" aria-expanded="false"><button class="btn btn-info">Berikutnya</button></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle accordion-toggle-styled collapsed" id="accordion-toggle-wp" href="#collapse_waktu_penugasan" data-toggle="collapse" data-parent="#accordion_tab3_peserta"  aria-expanded="false"><i class="fa fa-calendar-check-o"></i> Waktu Penugasan </a>
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
                                                        <div class="row">
                                                                <a id="accordion-toggle-wp" data-toggle="collapse" data-parent="#accordion_tab3_peserta" href="#collapse_dok_peserta" aria-expanded="false"><button class="btn btn-info">Berikutnya</button></a>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle accordion-toggle-styled collapsed" id="accordion-toggle-file" data-toggle="collapse" data-parent="#accordion_tab3_peserta"  href="#collapse_dok_peserta"  aria-expanded="false"><i class="fa fa-file-pdf-o"> </i> Kelengkapan Dokumen Peserta</a>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div id="collapse_dok_peserta" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                    <div class="panel-body">
                                                        <div id="file_doc_require_pemohon">
                                                            <!-- based on database automate generate element via jquery script -->
                                                        </div>
                                                        <div class="row">
                                                            <a id="accordion-toggle-wp" data-toggle="collapse" data-parent="#accordion_tab3_peserta" href="#collapse_pembiayaan_peserta" aria-expanded="false"><button class="btn btn-info">Berikutnya</button></a>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle accordion-toggle-styled collapsed" id="accordion-toggle-biaya" data-toggle="collapse" href="#collapse_pembiayaan_peserta" data-parent="#accordion_tab3_peserta" aria-expanded="false"><i class="fa fa-money"></i> Pembiayaan Peserta</a>
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
                                                                        <input type="radio" name="opt_pendanaan" id="opt_pendanaan1" value="0" /> Pendanaan Tunggal
                                                                        <span></span>
                                                                    </label>
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="opt_pendanaan" id="opt_pendanaan2" value="1" /> Pendanaan Campuran
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
                                                                                        <input type="checkbox" value="<?php echo $biaya->ID . '_'; ?>1" name="check_jb[]" id="<?php echo $biaya->Name; ?>1" />
                                                                                        <span></span>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input type="checkbox" value="<?php echo $biaya->ID . '_'; ?>2" name="check_jb[]" id="<?php echo $biaya->Name; ?>2" />
                                                                                        <span></span>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input type="checkbox" value="<?php echo $biaya->ID . '_'; ?>3" name="check_jb[]" id="<?php echo $biaya->Name; ?>3" />
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
                                    </div>
                                    <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-circle red-soft" id="btn-save-peserta-det" name="btn-save-peserta-det" title="Simpan Data Peserta" disabled="true"><i class="fa fa-save"> </i> Simpan Data Peserta </button>
                                                <!-- button type="button" class="btn btn-circle green" id="btn_m_upload" title="Mass Upload Data Peserta"><i class="fa fa-cloud-upload"></i> Mass Upload</button//-->
                                            </div>
                                            <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
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
                                                    <th class="text-center" width="25%" style="vertical-align: middle;"> Aksi </th>
                                                    <th class="text-center" style="vertical-align: middle;"> NIP </th>
                                                    <th class="text-center" style="vertical-align: middle;"> NIK </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Paspor </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Nama Peserta </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Jabatan </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Tanggal Penugasan </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Jenis Pembiayaan </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Instansi Peserta </th>
                                                    <th class="text-center" style="vertical-align: middle;"> Jumlah Biaya </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab4">
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
                                        <label class="control-label col-md-3">Penyelenggara</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="penyelenggara"> </p>
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
                                    <div class="table">
                                        <table class="table table-condensed table-striped table-hover table-bordered" id="table_list_peserta_confirm">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="4%" style="vertical-align: middle;">ID LOG PESERTA.</th>
                                                    <th class="text-center" width="4%" style="vertical-align: middle;">No.</th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> NIP </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> NIK </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Paspor </th>
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Nama Peserta </th>
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Jabatan </th>
                                                    <th class="text-center" width="16%" style="vertical-align: middle;"> Tgl Penugasan </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Jenis Pembiayaan </th>
                                                    <th class="text-center" width="10%" style="vertical-align: middle;"> Instansi Peserta </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Jumlah Biaya </th>
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Aksi </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center" style="vertical-align: middle;">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="clearfix"></div>
                                    <?php if (!empty($list_approval)) { ?>
                                        <div class="mt-element-list">
                                            <div class="mt-list-head list-simple ext-1 font-white bg-green-sharp">
                                                <div class="list-head-title-container">
                                                    <h3 class="list-title">Catatan Persetujuan </h3>
                                                </div>
                                            </div>
                                            <?php foreach ($list_approval as $approval) { ?>
                                                <div class="mt-list-container list-simple ext-1">
                                                    <ul>
                                                        <li class="mt-list-item done">
                                                            <div class="list-icon-container">
                                                                <i class="icon-check"></i>
                                                            </div>
                                                            <div class="list-datetime" style="margin-right:10px;"> <?php echo date("d/m/Y H:i:s", strtotime($approval->submit_date)) ?> </div>
                                                            <div class="list-item-content">
                                                                <h3 class="uppercase">Catatan
                                                                    <?php if($approval->level == 'Pemohon'){
                                                                        echo $approval->level;
                                                                    }else if($approval->level == 'Focalpoint'){
                                                                        echo $approval->level;

                                                                    }else{
                                                                        echo 'SETNEG';
                                                                    }?> :
                                                                </h3>
                                                                <p style="margin-top:5px;color:#929191"><?php echo $approval->note ?></p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <br/><br/>
                                    <br/>
                                    <?php };?>
                                    <div class="form-group" id="note_pengajuan">

                                        <label class="control-label col-md-3"> Catatan : </label>
                                        <div class="col-md-4">
                                            <textarea id="note1" name="note" rows="3" cols="40"></textarea>
                                        </div>
                                    </div>

                                    <div class="panel panel-primary" id="disclaimer">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Disclaimer SIMPDLN Biro KTLN Kementerian Sekretariat Negara</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="well">
                                                 <?php if ($jenis_permohonan == 'Pembatalan'){ ?>
                                                <p class="bold">
                                                    Dengan ini saya menyatakan bertanggung jawab atas kebenaran pembatalan permohonan perjalanan dinas tersebut ke situs Sistem Informasi Kerja Sama Teknik Luar Negeri. Segala bentuk kerugian negara dan pertanggungjawaban secara hukum yang terjadi sebagai akibat dari tindakan saya, baik disengaja maupun tidak disengaja akan menjadi tanggung jawab saya sesuai ketentuan peraturan perundang-undangan yang berlaku.
                                                </p>
                                                <?php }else{?>
                                                <p class="bold">
                                                    Dengan ini saya menyatakan bertanggung jawab atas kebenaran seluruh data yang saya masukkan dan dokumen yang saya unggah ke situs
                                                    Sistem Informasi Kerja Sama Teknik Luar Negeri. Segala bentuk kerugian negara dan pertanggungjawaban secara hukum yang terjadi sebagai
                                                    akibat dari tindakan saya, baik disengaja maupun tidak disengaja akan menjadi tanggung jawab saya sesuai ketentuan peraturan perundang-undangan
                                                    yang berlaku.
                                                </p>
                                                <?php };?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input" id="disclaimer_aggrement">
                                        <div class="col-md-10">
                                            <div class="md-checkbox has-success">
                                                <input type="checkbox" value="1" id="check_disclaimer" name="check_disclaimer" class="md-check">
                                                <label for="check_disclaimer">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>Dengan ini saya menyatakan setuju.</label>
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
                                <div class="col-md-offset-3 col-md-8">
                                    <button class="btn blue button-previous" type="button" style="display: none;"> <i class="fa fa-angle-left"> </i> Kembali </button>
                                    <button class="btn green button-next" type="button"> Lanjutkan <i class="fa fa-angle-right"> </i></button>
                                    <button class="btn red button-draft" type="button"> Simpan Draft <i class="fa fa-save"> </i></button>
                                    <?php
                                    $str_kirim = 'Kirim Permohonan';
                                    if ($status_pdln == '12' || $status_pdln == '0'){
                                        $str_kirim = 'Kirim Ulang';
                                    }
                                    ?>

                                    <button class="btn green button-submit" type="button" style="display: none;" disabled="true"> <?php echo $str_kirim;?> <i class="fa fa-check"></i></button>
                                    <?php
                                    $id_user = $this->session->user_id;
                                    $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
                                     if ($level == LEVEL_FOCALPOINT ) {
                                     ?>
                                    <button class="btn yellow button-cancel" type="button" style="display: none;" >Dikembalikan <i class="fa fa-close"></i></button>
                                    <?php };?>
                                </div>
                            </div>
                        </div>
                        <div id="comentar_fp" style="display: none;" >
                                <div class="form-group">
                                    <label class="control-label col-md-3"> Catatan : </label>
                                    <div class="col-md-4">
                                        <textarea id="note2" name="note_tolak" rows="3" cols="40"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3"> Kembalikan Ke Unit Pemohon </label>
                                    <div class="col-md-8">
                                        <button type="button" id="tolak" class="btn submit btn-primary" data-toggle="confirmation" data-original-title="Apakah Anda Sudah Yakin ?" data-btn-ok-label=" Ya " data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="Tidak"
                                                data-btn-cancel-icon="icon-close" data-btn-cancel-class="btn-warning"> <i class="fa fa-check"> </i> Dikembalikan Ke UP</button>
                                        <button type="button" id="close_dikembalikan" class="btn btn-danger" > <i class="fa fa-close"> </i> Batal Dikembalikan  </button>
                                    </div>
                                </div>

                        </div >
                    </div>
                    <!-- END FORM WIZARD PERMOHONAN BARU -->
                </form>
            </div>
        </div>
        <!-- END PORTLET WIZARD PERMOHONAN BARU -->
    </div>
</div>
<!-- END ROW -->
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
                        <span class="title-text">List Data Pemohon</span>
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
                                    <th class="text-center">Pilih</th>
                                    <th class="text-center">NIP/NRP</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">Paspor</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Jabatan</th>
                                    <th class="text-center">Instansi</th>
                                    <th class="text-center">Jenis Pemohon</th>
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
<div class="modal fade" id="update_list_pemohon" name="update_list_pemohon" role="dialog" tabindex="-1" aria-labelledby="update_list_pemohon" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text">List Data Pemohon</span>
                    </h3>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-advanced table-striped table-condensed table-hover table-bordered" id="table_update_pemohon">
                            <thead>
                                <tr>
                                    <th class="text-center">ID Pemohon</th>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">NIP/NRP</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">Paspor</th>
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

<div class="modal fade" id="list_level" name="list_level" role="dialog" tabindex="-1" aria-labelledby="list_level" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text">List Level Pejabat</span>
                    </h3>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8" id="desc_level">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn blue-dark"> <i class="fa fa-left"> </i> Kembali </button>
            </div>
        </div>
    </div>
</div>
<?php /* ?>
<div class="modal fade" id="mass_upload" name="mass_upload" role="dialog" tabindex="-1" aria-labelledby="mass_upload" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text">Mass Upload</span>
                    </h3>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-body">

                          <div class="form-group">
                              <label class="control-label">Mass Upload Data Peserta<strong> <span class="font-red">(* csv)</span></strong></label>
                              <br>
                              <input id="fileUpload" name="fileUpload" type="file" class="file" data-show-preview="false">
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php */ ?>
<div class="modal fade" id="bukti_register" name="bukti_register" role="dialog" tabindex="-1" aria-labelledby="bukti_register" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text" id="judul_register">Permohonan Telah Dikirim ...</span>
                    </h3>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8" id="deskripsi_register">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button-primary" data-dismiss="modal" class="btn blue-dark" id="print_register"> Cetak Bukti Register </button>
                <button type="button" data-dismiss="modal" class="btn default" id="kembali_home"> <i class="fa fa-left"> </i> Kembali </button>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL LIST DATA PEMOHON -->
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/fileinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/locales/id.min.js"></script>

<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-media.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" />
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/custom/scripts/kotak_surat/form_wizard_edit_permohonan.js" type="text/javascript"></script>
