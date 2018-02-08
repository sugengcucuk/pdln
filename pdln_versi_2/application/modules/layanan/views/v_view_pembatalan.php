
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
            <div class="portlet-body form">
                <form class="form-horizontal" action="javascript:;" id="submit_form_permohonan_baru" name="submit_form_permohonan_baru" autocomplete="off">
                    <!-- BEGIN FORM WIZARD PERMOHONAN BARU -->
                    <div class="form-wizard">
                        <!-- BEGN FORM BODY -->
                        <div class="form-body">
                            <ul class="nav nav-pills nav-justified steps">
                                <li>
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
                                <li class="active">
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
                                <input id="user_id_pemohon" name="user_id_pemohon" type="hidden">
                                <div class="alert alert-danger display-none">
                                    <button class="close" data-dismiss="alert"></button> Data anda bermasalah, harap cek kembali.. </div>
                                <div class="alert alert-success display-none">
                                    <button class="close" data-dismiss="alert"></button> Form Permohonan anda valid </div>
                                <div class="tab-pane" id="tab1">
                                    <h4 class="form-section">Detail Umum</h4>
                                    <p>
                                        <span class="label label-danger"> * Diisi Oleh Unit Pemohon (Satker) </span>
                                    </p>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Surat Usulan Unit Pemohon</label>
                                        <div class="col-md-9" id="show_fsp" style="display: none;">
                                            <div class="col-md-3">
                                                <a class="btn btn-xs purple-intense s_pemohon_usulan" id="view_file_pemohon_s_1" name="view_file_pemohon_s_1" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Pemohon (Satker)</a>
                                            </div>
                                        </div>
                                        <!-- Edit again -->
                                        <div id="edit_fsp" style="display: none;">
                                            <span class="label label-info">*File usulan pemohon<span class="small bold font-white">  Tidak diunggah</span></span>
                                        </div>                                          
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">No.Surat Usulan Pemohon</label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" value="<?php echo $data_pdln->no_surat_usulan_pemohon ?>" readonly>  
                                        </div>                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Tanggal Surat Usulan Pemohon</label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" value="<?php echo day(date('y-m-d', $data_pdln->tgl_surat_usulan_pemohon)) ?>" readonly>  
                                        </div>
                                    </div>
                                    <p>
                                        <span class="label label-danger">* Diisi Oleh Unit Focal Point (K/L) </span>
                                    </p>
                                    <fieldset id="focal_point_form_set">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Level Pejabat
                                                <span class="required" aria-required="true"> * </span>
                                            </label> 
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" value="<?php echo 'level_pejabat' ?>" readonly>  
                                            </div>  
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">No.Surat Usulan Focal Point
                                                <span class="required" aria-required="true"> * </span>
                                            </label>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" value="<?php echo $data_pdln->no_surat_usulan_fp ?>" readonly>  
                                            </div> 
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Tanggal Surat Usulan
                                                <span class="required" aria-required="true"> * </span>
                                            </label>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" value="<?php echo day(date('y-m-d', $data_pdln->tgl_surat_usulan_fp)) ?>" readonly>  
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Surat Usulan Focal Point
                                                <span class="required" aria-required="true"> * </span>
                                            </label>
                                            <div class="col-md-9" id="show_fsp_fp" style="display: none;">
                                                <div class="col-md-3">
                                                    <a class="btn btn-xs purple-intense s_focal_point_usulan" id="view_file_pemohon" name="view_file_pemohon" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Focal Point </a>                                                    
                                                </div>
                                            </div>
                                            <!-- Edit again -->
                                            <div id="edit_fsp_fp" style="display: none;">                                            
                                                <span class="label label-info">*File usulan Focalpoint<span class="small bold font-white">  Tidak diunggah</span></span>
                                            </div>
                                        </div>                       
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Pejabat Penandatangan Surat Permohonan
                                                <span class="required" aria-required="true"> * </span>
                                            </label>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" value="<?php echo $data_pdln->pejabat_sign_sp ?>" readonly>  
                                            </div> 
                                        </div>    
                                    </fieldset>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <h4 class="form-section">Detail Kegiatan</h4>
                                    <input type="hidden" class="form-control" name="id_jenis_kegiatan" id="id_jenis_kegiatan" value="<?php echo $detail_kegiatan->ID?>"/>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Jenis Kegiatan</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $detail_kegiatan->JenisKegiatan?>" readonly />
                                        </div>
                                    </div>                                        
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Nama Kegiatan</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $detail_kegiatan->NamaKegiatan?>" readonly />
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Negara Tujuan</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $detail_kegiatan->nmnegara?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Kota Tujuan</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $detail_kegiatan->nmkota?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Mulai</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo day($detail_kegiatan->StartDate)?>" readonly />
                                        </div>
                                    </div>                                        
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Sampai</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo day($detail_kegiatan->EndDate)?>" readonly />
                                        </div>
                                    </div>

                                    <h4 class="form-section file_doc">Kelengkapan Dokumen Kegiatan</h4>
                                    <div id="file_doc_require">

                                    </div> 
                                </div>
                                <div class="tab-pane" id="tab3">
                                    <h4 class="form-section">List Peserta</h4>
                                    <div class="table">
                                        <table class="table table-condensed table-striped table-hover table-bordered" id="table_list_peserta">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="4%" style="vertical-align: middle;">ID LOG PESERTA.</th>
                                                    <th class="text-center" width="4%" style="vertical-align: middle;">No.</th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> NIP </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> NIK </th>
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Nama Peserta </th>
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Jabatan </th>
                                                    <th class="text-center" width="16%" style="vertical-align: middle;"> Tgl Penugasan </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Jenis Pembiayaan </th>
                                                    <th class="text-center" width="10%" style="vertical-align: middle;"> Nama Instansi </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Jumlah Biaya </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center" style="vertical-align: middle;">                                                    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane active" id="tab4">
                                    <h3 class="block">Konfirmasi</h3>
                                    <h4 class="form-section">Detail Umum</h4>                                    

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Surat Usulan Satker (Opsional)</label>
                                        <div class="col-md-9" id="show_fsp_c" style="display: none;">
                                            <div class="col-md-3">
                                                <a class="btn btn-xs purple-intense s_pemohon_usulan_c" id="view_file_pemohon_c" name="view_file_pemohon_c" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Pemohon (Satker)</a>
                                            </div>
                                        </div>
                                        <!-- Edit again -->
                                        <div id="edit_fsp_c" style="display: none;">
                                            <span class="label label-info">*File usulan pemohon<span class="small bold font-white">  Tidak diunggah</span></span>
                                        </div>                                          
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Level Pejabat
                                            <span class="required" aria-required="true"> * </span>
                                        </label> 
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" value="<?php echo 'level_pejabat2'; ?>" readonly>  
                                        </div>  
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">No.Surat Usulan Pemohon</label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" value="<?php echo $data_pdln->no_surat_usulan_pemohon ?>" readonly>  
                                        </div>                                        
                                    </div> 

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Surat Usulan Focal Point
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-9" id="show_fsp_fp_c" style="display: none;">
                                            <div class="col-md-3">
                                                <a class="btn btn-xs purple-intense s_focal_point_usulan_c" id="view_file_pemohon_c" name="view_file_pemohon_c" href=""><i class="fa fa-file-pdf-o"> </i> Surat Usulan Focal Point </a>                                                    
                                            </div>
                                        </div>
                                        <!-- Edit again -->
                                        <div id="edit_fsp_fp_c" style="display: none;">                                            
                                            <span class="label label-info">*File usulan Focalpoint<span class="small bold font-white">  Tidak diunggah</span></span>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Tanggal Surat Usulan
                                            <span class="required" aria-required="true"> * </span>
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo ($data_pdln->tgl_surat_usulan_fp == 0) ? '' : date('d M Y', $data_pdln->tgl_surat_usulan_fp); ?>" name="tgl_surat_usulan_fp" id="tgl_surat_usulan_fp" placeholder="<No Surat Usulan Unit Focal Point>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">No.Surat Usulan Focal Point
                                            <span class="required" aria-required="true"> * </span>
                                        </label> 
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $data_pdln->no_surat_usulan_fp?>" name="no_surat_usulan_focal_point" id="no_surat_usulan_focal_point" placeholder="<No Surat Usulan Unit Focal Point>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group"> 
                                        <label class="control-label col-md-3">Pejabat Penandatangan Surat Permohonan</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $data_pdln->pejabat_sign_sp?>" name="pejabat_sign_sp" id="pejabat_sign_sp" readonly />
                                        </div>
                                    </div>
                                    <h4 class="form-section">Detail Kegiatan</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Jenis Kegiatan</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $detail_kegiatan->JenisKegiatan?>" readonly />
                                        </div>
                                    </div>                                        
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Nama Kegiatan</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $detail_kegiatan->NamaKegiatan?>" readonly />
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Negara Tujuan</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $detail_kegiatan->nmnegara?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Kota Tujuan</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo $detail_kegiatan->nmkota?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Mulai</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo day($detail_kegiatan->StartDate)?>" readonly />
                                        </div>
                                    </div>                                        
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Sampai</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?php echo day($detail_kegiatan->EndDate)?>" readonly />
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
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Nama Peserta </th>
                                                    <th class="text-center" width="15%" style="vertical-align: middle;"> Jabatan </th>
                                                    <th class="text-center" width="16%" style="vertical-align: middle;"> Tgl Penugasan </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Jenis Pembiayaan </th>
                                                    <th class="text-center" width="10%" style="vertical-align: middle;"> Nama Instansi </th>
                                                    <th class="text-center" width="8%" style="vertical-align: middle;"> Jumlah Biaya </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center" style="vertical-align: middle;">
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="clearfix"></div>
                                    <br/><br/>
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
                                                                <h3 class="uppercase">
                                                                    Catatan 
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
                                        <?php } ?> 



                                        <div class="well well-lg">
                                            <div class="note note-danger">
                                                <p class="block">Jenis Permohonan : <?php echo setJenisPermohonan($data_pdln->jenis_permohonan); ?></p>
                                            </div>

                                            <input type="hidden" name="id_pdln" id="id_pdln" value="<?php echo $id_pdln; ?>" class="form-control">
                                            <input type="hidden" name="jenis_preview" id="jenis_preview" value="<?php echo setJenisPermohonan($data_pdln->jenis_permohonan); ?>" class="form-control">
                                            <div class="form-group">
                                                <label class="control-label col-xs-3"></label>
                                                <div class="col-xs-4">
                                                    <button type="button" id="view_surat" class="btn btn-sm green"> <i class="fa fa-file-pdf-o"> </i>  Preview Surat Persetujuan </button>
                                                    <span class="help-block"></span> 
                                                </div>  
                                            </div>
                                            <?php
                                            $id_user = $this->session->user_id;
                                            $level = $this->db->get_where('m_user', array('UserID' => $id_user))->row()->level;
                                             // if ($level == LEVEL_ANALIS ) {
                                             if ($data_pdln->status == '11' ) {
                                                ?>
                                                <input type="hidden" name="status"  id="status" value="3" class="form-control">
                                                <input type="hidden" name="level"  id="level" value="Analis" class="form-control">
                                                <input type="hidden" name="nextlevel"  id="nextlevel" value="Kasubag" class="form-control">
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

                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Catatan : </label>
                                                    <div class="col-md-4">
                                                        <textarea id="note" name="note" rows="3" cols="40"></textarea> 
                                                    </div> 
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Lanjutkan Proses Pengajuan </label>
                                                    <div class="col-md-4">
                                                        <button type="button" id="pembatalan" class="btn submit btn-primary" data-toggle="confirmation" data-original-title="Apakah Anda Sudah Yakin ?" data-btn-ok-label=" Ya " data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="Tidak"
                                                                data-btn-cancel-icon="icon-close" data-btn-cancel-class="btn-warning"> <i class="fa fa-check"> </i>Permohonan Pembatalan </button>
                                                        <button type="button" id="cancel" class="btn btn-danger" data-toggle="confirmation" data-original-title="Apakah Anda Sudah Yakin ?" data-btn-ok-label=" Ya " data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="Tidak"
                                                                data-btn-cancel-icon="icon-close" data-btn-cancel-class="btn-warning"> <i class="fa fa-close"> </i> TIdak </button>  
                                                    </div> 
                                                </div>
                                                 
                                                <?php
                                            }
                                            ?>  
                                        </div>
                                    </div>
                                <!-- END TAB CONTENT --> 
                            </div>
                            <!-- END FORM BODY -->
                            <!--<div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-8">
                                        <a href="javascript:;" class="btn blue button-previous disabled" style="display: none;"> <i class="fa fa-angle-left"> </i> Kembali </a>
                                        <a href="javascript:;" class="btn green button-next"> Lanjutkan <i class="fa fa-angle-right"> </i></a>
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>
                        <!-- END FORM WIZARD PERMOHONAN BARU -->
                    </form>
                </div>
            </div>
            <!-- END PORTLET WIZARD PERMOHONAN BARU -->
        </div>
    </div>
    <!-- END ROW -->
    

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fileinput/css/fileinput.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
    <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/fileinput.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/locales/id.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>  

	<!-- Add fancyBox -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>

	<!-- Optionally add helpers - button, thumbnail and/or media -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-media.js"></script>
	<script src="<?php echo base_url(); ?>assets/custom/scripts/kotak_surat/form_edit_task.js?_dt=201706211600" type="text/javascript"></script>

    <!-- ++++++++++++++++++++++++++ -->