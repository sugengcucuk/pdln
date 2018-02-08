<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fileinput/css/fileinput.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" /> 


<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
                    <i class=" icon-layers font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Laporan Penugasan</span>
                </div>
			</div>
			<div class="portlet-body">
				<!-- BEGIN FORM-->
				<form action="javascript:;" id="form_add_realisasi" class="form-horizontal viewsaja" kegiatan="form">
					<div class="form-body">
						<?php 
						foreach ($m_pdln as $pdln) {
							$id_pdln = $pdln->id_pdln;
							$id_kegiatan = $pdln->id_kegiatan;
							$id_kota = $pdln->id_kota;
							$tujuan_kegiatan  = $pdln->tujuan_kegiatan ;
							$materi_kegiatan = $pdln->materi_kegiatan;
							$tindak_lanjut  = $pdln->tindak_lanjut ;
							$dampak_recom = $pdln->dampak_recom;
							$NamaKegiatan = $pdln->nama_kegiatan;
							$StartDate = date("d-m-Y",strtotime($pdln->start_date)); 
							$EndDate = date("d-m-Y",strtotime($pdln->end_date));
							$nmkota = $pdln->nmkota;
							$is_final_print = $pdln->is_final_print;
						}
						?>
						<input type="hidden" name="is_final_print"  id="is_final_print"  value="<?php echo $is_final_print;?>">
						<input type="hidden" name="tgl_awal"  id="tgl_awal"  value="<?php echo $StartDate;?>">
						<input type="hidden" name="tgl_akhir"  id="tgl_akhir"  value="<?php echo $EndDate;?>">

						<input type="hidden" name="id_pdln"  id="id_pdln" class="form-control" value="<?php echo $id_pdln;?>">
						<input type="hidden" name="id_kegiatan"  id="id_kegiatan" value="<?php echo $id_kegiatan;?>">
						<input type="hidden" name="StartDate"  id="StartDate"  value="<?php echo $pdln->start_date;?>">
						<input type="hidden" name="EndDate"  id="EndDate"  value="<?php echo $pdln->end_date ;?>">
						<input type="hidden" name="id_kota"  id="id_kota" value="<?php echo $id_kota;?>">
						<!-- <input type="hidden" name="ID"  id="ID"  value="<?php //echo $id_kegiatan;?>"> -->
						
						<div class="form-group">
							<label class="control-label col-xs-3">No.Register Permohonan Yang Dilaporkan
								<span class="required strong">*</span>
							</label>
							<div class="col-md-6">
								<span class="form-control"><?php  echo $no_registri; ?></span>
                            </div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">Nama Kegiatan
								<span class="required strong">*</span>
							</label>
							<div class="col-md-6">
								<input type="text" name="kegiatan_name" id="kegiatan_name" class="form-control viewsaja"  value="<?php echo $NamaKegiatan;?>" >
                            </div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-3">Tempat 
								<span class="required strong">*</span>
							</label>
							<div class="col-md-6">
								<input type="text" name="nama_kota" id="nama_kota" class="form-control viewsaja" 
								value="<?php echo $nmkota;?>">
                            </div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">Waktu Pelaksanaan
								<span class="required strong">*</span>
							</label>
							<div class="col-md-6 date">
								<input type="text" name="tanggal_pelaksana" id="tanggal_pelaksana" class="form-control viewsaja "
								  >
                            </div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">Tujuan Kegiatan 
								<span class="required strong">*</span>
							</label>
							<div class="col-md-6">
								<textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="3" cols="40" class="form-control viewsaja" ><?php echo $tujuan_kegiatan; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-3">Materi Kegiatan
								<span class="required strong">*</span>
							</label>
							<div class="col-md-6">
								<textarea id="materi_kegiatan" name="materi_kegiatan" rows="3" cols="40" class="form-control viewsaja" ><?php echo $materi_kegiatan;?></textarea> 
                            </div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">Tindak Lanjut
								<span class="required strong">*</span>
							</label>
							<div class="col-md-6">
                                <textarea id="tindak_lanjut" name="tindak_lanjut" rows="3" cols="40" class="form-control viewsaja" ><?php echo $tindak_lanjut;?></textarea> 
                            </div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">Dampak & Rekomendasi
								<span class="required strong">*</span>
							</label>
							<div class="col-md-6">
                                <textarea id="rekom" name="rekom" rows="3" cols="40" class="form-control viewsaja" ><?php echo $dampak_recom;?></textarea> 
                            </div>
						</div>
						<!-- <button type="button" id="cari_surat" class="btn btn-circle btn-outline btn-sm btn-block blue-hoki"> <i class="fa fa-search"> </i> Cari Permohonan</button> -->
							<!--
							<div class="col-xs-4">
								<input name="nomor_surat" id="nomor_surat" placeholder="Nomor Register" class="form-control" type="text" />
								<span class="help-block"></span> 
							</div>
							<div class="col-xs-4">
								<button type="submit" id="cari_surat" class="btn btn-outline green"> <i class="fa fa-search"> </i> Cari </button>
								<span class="help-block"></span> 
							</div>
							-->
						
						<!-- <br><br> -->
						<div class="portlet-title">
							<div class="caption">
			                    <i class=" icon-layers font-red"></i>
			                    <span class="caption-subject font-red bold uppercase"> Laporan Peserta Detail</span>
			                </div>
						</div>
						
						<hr/>
						<div class="form-group" id="realisasi_peserta">
							<label class="control-label col-xs-1"><b>No.</b></label>
							<label class="control-label col-xs-2"><b>Nama Peserta</b></label>
							<label class="control-label col-xs-3"><b>Estimasi Biaya</b></label>
							<label class="control-label col-xs-3"><b>Realisasi</b></label>
						</div>
						<div id="totalPeserta">
						<?php //$key =0;
						foreach ($m_pdln as $key=>$pst) {?>
						
						<div class="form-group" id="realisasi_peserta">

							<input type="hidden" name="id_realisasi_<?php echo $key;?>" value="<?php echo $pst->id;?>">
							<input type="hidden" name="id_nama_peserta_<?php echo $key;?>" value="<?php echo $pst->nama_peserta;?>">
							<input type="hidden" name="id_peserta_<?php echo $key;?>" value="<?php echo $pst->id_peserta;?>">
							<input type="hidden" name="id_kategori_biaya_<?php echo $key;?>" id="id_kategori_biaya_<?php echo $key;?>" value="<?php echo $pst->id_kategori_biaya;?>">
						
							<label class="control-label col-xs-1"><b><?php echo $key+1;?></b></label>
							<label class="control-label col-xs-3"><b><?php echo $pst->nama_peserta;?></b></label>
							<div class="col-xs-3">
								<input name="biaya_estimasi_<?php echo $key;?>" id="biaya_estimasi_<?php echo $key;?>" placeholder="Biaya Estimasi" class="form-control biaya" type="text"  value="<?php echo $pst->estimasi_awal;?>" />
							</div> 
							<div class="col-xs-3">
								<input name="biaya_realisasi_<?php echo $key;?>" id="biaya_realisasi_<?php echo $key;?>" placeholder="Biaya Realisasi" class="form-control biaya" type="text"  value="<?php echo $pst->realisasi_biaya;?>"/>
							</div> 
						</div>
						
						<?php }?>
						</div>
						<input type="hidden" name="counter_peserta" value="<?php echo $key+1;?>" id="counter_peserta">
						<div class="form-group view_surat">
							<label class="control-label col-xs-3">Surat Persetujuan</label>
							<div class="col-xs-4">
								<button type="button" id="view_surat" class="btn btn-sm btn-block red-haze v_sp viewsaja_pdf_persetujuan"> <i class="fa fa-file-pdf-o"> </i> Tampilkan Surat Persetujuan </button>
								<span class="help-block"></span> 
							</div> 
							<div class="col-xs-1"><button type="button" id="ganti_pdf" class="btn btn-sm btn-block green button_ganti_pdf"> ganti </button></div>
						</div>
						
						<div class="form-group upload_surat">
                            <label class="control-label col-md-3">Dokumen Laporan Kegiatan</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control" name="file_laporan_kegiatan" id="file_laporan_kegiatan" />
								<span class="help-block"></span>
								<input type="hidden" name="file_laporan_pdf" id="file_laporan_pdf" value="<?php echo $dokumen;?>">
							</div>                                       
                        </div>
					</div> <!-- END form-body -->
					<br>
					
					<div class="form-actions">
                        <div class="row"> 
                            <div class="col-md-offset-3 col-md-8">
                            	<?php if ($is_final_print == 1) {?>
                            	<h3 style="color: green;">Sudah Dilaporkan Terima kasih</h3>
                            	<?php }else{ ?>
                            	<button type="submit" id="edit_realisasi" class="btn btn-outline blue">  Aksi </button>
                            	<button type="submit" id="laporkan" class="btn btn-inline green">  Simpan </button>
                            	<button type="submit" id="sudah_dilaporkan" class="btn btn-inline red">  Laporkan </button>
                            <? };?>
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
                        <span class="title-text">List Data Permohonan Disetujui</span>
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
<!-- 
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
 
 Include Date Range Picker -->
<!-- <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />  -->
<!-- END MODAL LIST DATA PERMOHONAN -->


<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/fileinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/locales/id.min.js"></script>
<script src="<?php  echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.js"></script>

<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/form_pelaporan.js" type="text/javascript"></script>






<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fileinput/css/fileinput.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/fileinput.min.js"></script>
<!-- <script src="<?php //echo base_url(); ?>assets/global/plugins/fileinput/js/locales/id.min.js"></script> -->

<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- Add fancyBox -->
<!-- <link rel="stylesheet" href="<?php //echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" /> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-media.js"></script>
<!-- <script src="<?php //echo base_url(); ?>assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script> -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" />
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<!-- <script src="<?php //echo base_url(); ?>assets/custom/scripts/layanan/form_wizard_permohonan.js" type="text/javascript"></script> -->
<!-- <script src="<?php //echo base_url(); ?>assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script> -->
<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
