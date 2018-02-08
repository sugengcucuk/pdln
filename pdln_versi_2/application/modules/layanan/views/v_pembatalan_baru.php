<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET WIZARD PERMOHONAN BARU -->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-red"></i>
                    <span class="caption-subject font-red bold uppercase">Tambah Permohonan Pembatalan </span>
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-horizontal" action="javascript:;" id="submit_form_pembatalan_baru" name="submit_form_pembatalan_baru" autocomplete="off">
                    <!-- BEGIN FORM PMEBATALAN BARU -->
                    <div class="alert alert-danger display-none">
                        <button class="close" data-dismiss="alert"></button> Data anda bermasalah, harap cek kembali.. </div>
                    <div class="alert alert-success display-none">
                        <button class="close" data-dismiss="alert"></button> Form Permohonan anda valid </div>
                    <div class="form-body">
                        <h4 class="form-section">Detail Umum </h4>
                        <div class="form-group">
                            <label class="control-label col-md-3">Cari Nomor Register sebelumnya yang akan dibatalkan</label>
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
                            <label class="control-label col-md-3">Surat Persetujuan</label>
                            <div class="col-md-4">
                                <a href="<?php echo base_url(); ?>assets" target="_blank">View Surat Persetujuan</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr/>
    					<p>
    						<span class="label label-danger"> * Diisi Oleh Unit Pemohon (Satker) </span>
    					</p>
    					<div class="form-group">
                            <label class="control-label col-md-3">Surat Usulan Unit Pemohon (Opsional)</label>
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
                                    <option value="">Pilih</option>
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
                            <label class="control-label col-md-3"> Tanggal Surat Usulan
                                <span class="required" aria-required="true"> * </span>
                            </label>
                            <div class="col-md-4">
                                <div class='input-group date' id='tgl_surat_usulan'>
                                    <input type="text" class="form-control" id="tgl_surat" name="tgl_surat" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
    					<div class="form-group">
                            <label class="control-label col-md-3">Surat Usulan Focal Point
                                <span class="required" aria-required="true"> * </span>
                            </label>
                            <div class="col-md-5">
                                <input type="file" class="form-control" name="file_surat_usulan_fc" id="file_surat_usulan_fc" />
                            </div>
                            <div class="col-md-4">
                                <a href="<?php echo base_url(); ?>assets" target="_blank">View Surat Usulan FP</a>
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
                        <hr>
                        <div class="form-group">
                            <label class="control-label col-md-3">Alasan Pembatalan
                                <span class="required" aria-required="true"> * </span>
                            </label>
                            <div class="col-md-7"> 
                                <textarea class="form-control" name="alasan_pembatalan" id="alasan_pembatalan" placeholder="<Alasan Pembatalan PDLN>" rows="3"></textarea>
                            </div>
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
                    <!-- END FORM BODY -->
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-4">                                
                                <a href="javascript:;" class="btn red button-submit"> Kirim Pengajuan <i class="fa fa-send"></i></a>
                            </div>
                        </div>
                    </div>                
                </form>
            </div>
        <!-- END PORTLET PEMBATALAN BARU -->
        </div>
    </div>
</div>
<!-- END ROW -->
<!-- <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" /> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.5/css/fileinput.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.5/js/fileinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.5/js/locales/id.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/pembatalan.js" type="text/javascript"></script>
<!-- <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> -->