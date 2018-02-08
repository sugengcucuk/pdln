<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Tambah Pembatalan</span>
                </div>
            </div>
            <div class="portlet-body">
                <!-- BEGIN FORM-->
                <form id="form_add_pembatalan" class="form-horizontal" name="form_add_pembatalan">
                    <div class="form-body">
                        <input type="hidden" name="id_pdln"  id="id_pdln" class="form-control" />                       
                        <div class="form-group">
                            <label class="control-label col-md-3">Cari Nomor Register Sebelumnya Yang Akan Dibatalkan
                                <span class="required strong">*</span>
                            </label>
                            <div class="col-md-2" id="find_surat">
                                <button type="button" id="cari_surat" class="btn btn-circle btn-outline btn-sm btn-block blue-hoki"> <i class="fa fa-search"> </i> Cari Permohonan</button>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label class="control-label col-md-3">Surat Persetujuan</label>
                            <div class="col-md-2" id="s_sp">
                                <!-- <a class="btn btn-sm btn-block red-haze v_sp" id="v_sp" name="v_sp" href="" style="display: none;"><i class="fa fa-file-pdf-o"> </i> Surat Persetujuan </a> -->
                                <p class="bold text-left" id="no_sp_lama"></p>
                            </div>
                        </div>
                        <hr/>
                        <h4 class="form-section">Detail Permohonan</h4>
                        <p>
                            <span class="label label-danger"> * Diisi Oleh Unit Pemohon (Satker) </span>
                        </p>
                        <fieldset id="pemohon_form_set">
                            <div class="form-group">
                                <label class="control-label col-md-3">Surat Usulan Unit Pemohon</label>
                                <div class="col-md-9" id="show_fsp" style="display: none;">
                                    <div class="col-md-4">
                                        <!-- <a class="btn btn-xs purple-intense s_pemohon_usulan" id="view_file_pemohon_s_1" name="view_file_pemohon_s_1" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Pemohon (Satker)</a> -->
                                        <p class="bold text-left" id="view_file_pemohon_s_1"></p>
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
                                    <span class="label label-info">*Note<span class="small bold font-white">  Extensi File yang diizinkan [.pdf]</span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">No.Surat Usulan Pemohon</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="no_surat_usulan_pemohon" id="no_surat_usulan_pemohon" placeholder="<No Surat Usulan Unit Pemohon>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Tanggal Surat Usulan Pemohon</label>
                                <div class="col-md-4">
                                    <div class='input-group date'>
                                        <input type="text" class="form-control" id="tgl_surat_usulan_pemohon" name="tgl_surat_usulan_pemohon" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <p>
                            <span class="label label-danger">* Diisi Oleh Unit Focal Point (K/L) </span>
                        </p>
                        <fieldset id="focal_point_form_set">
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
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="no_surat_usulan_focal_point" id="no_surat_usulan_focal_point" placeholder="<No Surat Usulan Unit Focal Point>"/>
                                </div>
                                <span><i class="icon-remove"></i></span>
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
                                        <!-- <a class="btn btn-xs purple-intense s_focal_point_usulan" id="view_file_pemohon" name="view_file_pemohon" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Focal Point </a> -->
                                        <p class="bold text-left" id="view_file_fp"></p>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-xs red-mint" id="btn_del_file_surat_fp"><i class="fa fa-remove"></i>&nbsp; Ubah</button>
                                    </div>
                                </div>
                                <!-- Edit again -->
                                <div id="edit_fsp_fp" style="display: none;">                                            
                                    <div class="col-md-4">                                                    
                                        <input type="file" name="file_surat_usulan_pemohon" id="file_surat_usulan_pemohon" accept=".pdf" class="form-control" placeholder="Pilih File" />
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
                            <div class="form-group">
                                <label class="control-label col-md-3">User Pemohon</label>
                                <div class="col-md-5"> 
                                    <select class="form-control" name="list_pemohon" id="list_pemohon">
                                        <option value=""></option>
                                        <?php
                                        if (isset($list_pemohon)) {
                                            foreach ($list_pemohon as $pemohon) {
                                                ?>
                                                <option value="<?php echo $pemohon->UserID; ?>"><?php echo strtoupper($pemohon->username) . ' - ' . $pemohon->nama; ?></option>
                                            <?php }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <span class="font-red"><small><strong>* Tentukan Unit Pemohon jika belum di inisiasi.</strong></small></span>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <div class="form-group">
                            <label class="control-label col-md-3">Alasan Pembatalan
                                <span class="required" aria-required="true"> * </span>
                            </label>
                            <div class="col-md-6"> 
                                <textarea class="form-control" name="alasan_pembatalan" id="alasan_pembatalan" placeholder="<Alasan Pembatalan>"></textarea>
                            </div>
                        </div> 
                        <div class="clearfix"></div>
                        <br/>
                        <div class="panel panel-primary" id="disclaimer">
                            <div class="panel-heading">
                                <h3 class="panel-title">Disclaimer SIMPDLN Biro KTLN Kementerian Sekretariat Negara</h3>
                            </div>
                            <div class="panel-body">
                                <div class="well">
                                    <p class="bold">
                                        Dengan ini saya menyatakan bertanggung jawab atas kebenaran seluruh data yang saya masukkan dan dokumen yang saya unggah ke situs 
                                        Sistem Informasi Kerja Sama Teknik Luar Negeri. Segala bentuk kerugian negara dan pertanggungjawaban secara hukum yang terjadi sebagai 
                                        akibat dari tindakan saya, baik disengaja maupun tidak disengaja akan menjadi tanggung jawab saya sesuai ketentuan peraturan perundang-undangan
                                        yang berlaku.
                                    </p>
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
                    </div> <!-- END form-body -->   
                    <div class="form-actions">
                        <div class="row"> 
                            <div class="col-md-offset-4 col-md-8">
                                <button type="submit" id="kirim_pembatalan" class="btn btn-outline yellow-gold"> <i class="fa fa-send"> </i> Kirim Pengajuan</button>
                                <button type="button" id="kembali" class="btn btn-outline red-soft"> <i class="fa fa-angle-left"> </i> Kembali </button>
                            </div>
                        </div>
                    </div> 
                </form> <!-- END FORM-->            
            </div>
        </div>
    </div>  
</div>
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
                        <span class="title-text">List Data Surat Persetujuan</span>
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
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fileinput/css/fileinput.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/fileinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/locales/id.min.js"></script>
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
<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/form_add_pembatalan.js" type="text/javascript"></script>