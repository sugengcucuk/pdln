
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze">
                        Daftar Pemohon
                    </span>
                </div>

            </div>
            <div class="portlet-body">
				<?php if($this->ion_auth->is_allowed(41,'create')) {?>
				<div class="actions">
                    <button class="btn btn-outline blue" id="new_pemohon"> <i class="fa fa-plus"></i> Tambah Pemohon Baru </button>
					<button type="button" class="btn btn-circle green" id="btn_m_download" title="Download Template Mass Upload Data Peserta"><i class="fa fa-cloud-download"></i> Template Mass Upload</button>
					<!--button type="button" class="btn btn-circle green" id="btn_m_upload" title="Mass Upload Data Peserta"><i class="fa fa-cloud-upload"></i> Mass Upload</button//-->
                </div><br/><br/>
				<?php }?>
                <div class="table">
                    <table class="table table-hover table-bordered" id="tabel_pemohon_manage">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">No</th>
								<th class="text-center">Nama</th>
								<th class="text-center">NIP/NRP</th>
								<th class="text-center">NIK</th>
								<th class="text-center">Jabatan</th>
								<th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center" style="vertical-align: middle;">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END CHART PORTLET-->
    </div>
	<div class="modal fade" id="modal_new_pemohon" pemohon="dialog" aria-labelledby="modal_new_pemohon" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<div class="modal-title">
					<h3 class="font-green uppercase bold"> <i class="icon-plus"></i>
						<span class="title-text"></span>
					</h3>
				</div>
			</div>
			<div class="modal-body form">
				<!-- BEGIN FORM-->
				<form action="javascript:;" id="form_pemohon" class="form-horizontal" pemohon="form">
					<div class="form-body">
						<input type="hidden" name="ID"  id="ID" class="form-control">
						<input type="hidden" name="method" id="method" class="form-control">

						<div class="form-group">
							<label class="control-label col-xs-3">Nama <span class="required" aria-required="true"> * </span></label>
							<div class="col-xs-4">
								<input name="nama" id="nama" placeholder="Nama" class="form-control" type="text" />
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">NIP/NRP </label>
							<div class="col-xs-4">
								<input name="nip_nrp" id="nip_nrp" placeholder="NIP" class="form-control" type="text"/>
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">NIK <span class="required" aria-required="true"> * </span></label>
							<div class="col-xs-4">
								<input name="nik" id="nik" placeholder="NIK" class="form-control" type="text" maxlength="16"/>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-3">PASPOR <span class="required" aria-required="true"> * </span></label>
							<div class="col-xs-4">
								<input name="paspor" id="paspor" placeholder="No Paspor" class="form-control" type="text"/>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-3">Jabatan <span class="required" aria-required="true"> * </span></label>
							<div class="col-xs-4">
								<input name="jabatan" id="jabatan" placeholder="Jabatan" class="form-control" type="text" />
								<span class="help-block"></span>
							</div>
						</div>

						 <div class="form-group">
                            <label class="control-label col-md-3">Instansi
								<span class="required" aria-required="true"> * </span>
                            </label>
                            <div class="col-md-4">
                                <select name="instansi" id="instansi" class="form-control">
                                    <option value="">Pilih Instansi</option>
                                    <?php foreach ($list_instansi as $instansi) { ?>
                                        <option value="<?php echo $instansi->ID; ?>"><?php echo $instansi->Nama; ?></option>
                                    <?php } ?>
                                    <option value="0">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-6">
                                <textarea type="text" rows="2" class="form-control" name="instansi_lainnya" id="instansi_lainnya" placeholder="Ketik Nama Instansi" readonly /></textarea>
							</div>
							<div class="col-md-3">
								<span class="font-red"><small><strong>* pilih lainnya pada instansi, untuk mengaktifkan.</strong></small></span>
							</div>
                        </div>

						<div class="form-group">
							<label class="control-label col-xs-3">Email <span class="required" aria-required="true"> * </span></label>
							<div class="col-xs-4">
								<input name="email" id="email" placeholder="email" class="form-control" type="text" />
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">Telepon <span class="required" aria-required="true"> * </span></label>
							<div class="col-xs-4">
								<input name="telp" id="telp" placeholder="Telepon" class="form-control" type="text" />
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-xs-3">Status <span class="font-red"></span></label>
							<div class="col-md-9">
								<div class="mt-radio-inline">
	                            	<label class="mt-radio">
	                                	<input type="radio" name="opt_status" id="opt_status1" value="1" /> Aktif
	                                	<span></span>
	                                </label>
	                                <label class="mt-radio">
	                                	<input type="radio" name="opt_status" id="opt_status0" value="0" /> Tidak Aktif
	                                	<span></span>
	                                </label>
	                            </div>
	                        </div>
						</div>

					</div> <!-- END form-body -->
				</form> <!-- END FORM-->
			</div><!-- END modal body form new user -->
			<div class="modal-footer">
				<button type="submit" id="simpan" class="btn submit btn-primary"> Simpan </button>
				<button type="button" data-dismiss="modal" class="btn btn-default"> Batal </button>
			</div>
		</div>
	</div>
</div>
</div>
<!-- END ROW -->
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
                              <label class="control-label">Mass Upload Data Pemohon<strong> <span class="font-red">(* csv)</span></strong></label>
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
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fileinput/css/fileinput.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/fileinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/fileinput/js/locales/id.min.js"></script>
<script src="<?php echo base_url(); ?>assets/custom/scripts/manajemen/pemohon.js" type="text/javascript"></script>