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
				<form action="javascript:;" id="form_add_realisasi" class="form-horizontal" kegiatan="form">
					<div class="form-body">
						<input type="hidden" name="ID"  id="ID" class="form-control">
						<input type="hidden" name="method" id="method" class="form-control">

						<div class="form-group">
							<label class="control-label col-xs-3">Cari Nomor Register Sebelumnya Yang Akan Dilaporkan
								<span class="required strong">*</span>
							</label>
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
							<div class="col-md-2" id="find_surat">
                                <button type="button" id="cari_surat" class="btn btn-circle btn-outline btn-sm btn-block blue-hoki"> <i class="fa fa-search"> </i> Cari Permohonan</button>
                            </div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">Surat Persetujuan</label>
							<div class="col-xs-4">
								<button type="button" id="view_surat" class="btn btn-sm btn-block red-haze v_sp"> <i class="fa fa-file-pdf-o"> </i> Tampilkan Surat Persetujuan </button>
								<span class="help-block"></span>
							</div>
						</div>
						<hr/>

						<div class="form-group">
							<label class="control-label col-xs-3">Biaya APBN Yang Diserap</label>
							<div class="col-xs-4">
								<input name="biaya" id="biaya" placeholder="Biaya APBN Yang Diserap" class="form-control" type="text" />
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
                            <label class="control-label col-md-3">Dokumen Laporan Kegiatan</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control" name="file_laporan_kegiatan" id="file_laporan_kegiatan" />
								<span class="help-block"></span>
							</div>
                        </div>

					</div> <!-- END form-body -->

					<div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-8">
                               <button type="submit" id="kembali" class="btn btn-outline red"> <i class="fa fa-angle-left"> </i> Kembali </button>
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
                        <table class="table table-advanced table-striped table-condensed table-hover table-bordered" id="table_list_permohonan" style="width:100% !important;">
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


<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/fileinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/locales/id.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.js"></script>

<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/form_add_realisasi.js" type="text/javascript"></script>