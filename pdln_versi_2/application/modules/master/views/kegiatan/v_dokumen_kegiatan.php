<div class="row">
    <div class="col-md-12">
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze">
                        Daftar Dokumen Kegiatan
                    </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="actions">
                    <?php if($this->ion_auth->is_allowed(19,'create')) {?>
						<button class="btn btn-outline blue" id="new_dokumen_kegiatan"> <i class="fa fa-plus"></i> Tambah Dokumen Kegiatan Baru </button>
						<br/><br/>
					<?php }?>
				</div>
				<div class="table">
                    <table class="table table-hover table-bordered" id="tabel_dokumen_kegiatan_manage">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No</th>
								<th>Nama</th>
								<th>Deskripsi</th>
								<th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END CHART PORTLET-->
    </div>
	<div class="modal fade" id="modal_new_dokumen_kegiatan" dokumen_kegiatan="dialog" aria-labelledby="modal_new_dokumen_kegiatan" aria-hidden="true">
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
				<form action="javascript:;" id="form_dokumen_kegiatan" class="form-horizontal" dokumen_kegiatan="form">
					<div class="form-body">
						<input type="hidden" name="ID"  id="ID" class="form-control">
						<input type="hidden" name="method" id="method" class="form-control">
						<div class="form-group">
							<label class="control-label col-xs-3">Nama</label>
							<div class="col-xs-4">
								<input name="Nama" id="Nama" placeholder="Nama" class="form-control" type="text" />
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-3">Deskripsi </label>
							<div class="col-xs-4">
								<input name="Description" id="Description" placeholder="Deskripsi" class="form-control" type="text" />
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-3">Status <span class="font-red"></span></label>
							<div class="col-md-9">
								<div class="mt-radio-inline">
	                            	<label class="mt-radio">
	                                	<input type="radio" name="opt_status" id="opt_status" value="1" /> Aktif
	                                	<span></span>
	                                </label>
	                                <label class="mt-radio">
	                                	<input type="radio" name="opt_status" id="opt_status" value="0" /> Tidak Aktif
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
<script src="<?php echo base_url(); ?>assets/custom/scripts/master/dokumen_kegiatan.js?_dt=201606211658" type="text/javascript"></script>
