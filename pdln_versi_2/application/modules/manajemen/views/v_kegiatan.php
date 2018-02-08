 <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
		
<div class="row">
    <div class="col-md-12">  
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze"> 
                        Daftar Kegiatan
                    </span>
                </div> 
            </div>
            <div class="portlet-body">
				<?if($this->ion_auth->is_allowed(28,'create')) 
				{?>
				 <div class="actions">
                    <button class="btn btn-outline blue" id="new_kegiatan"> <i class="fa fa-plus"></i> Tambah Kegiatan Baru </button> 
                </div>
				<br/><br/>
				<?}?>
			   <div class="table">
                    <table class="table table-hover table-bordered" id="tabel_kegiatan_manage">
                        <thead>
                            <tr>
                                <th class="text-center">#</th> 
                                <th class="text-center">No</th>
								<th class="text-center">Nama Kegiatan</th>
								<th class="text-center">Jenis Kegiatan</th>
								<th class="text-center">Negara Tujuan</th>
								<th class="text-center">Kota Tujuan</th>
								<th class="text-center">Waktu Kegiatan</th>
								<th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr> 
                        </thead>
                        <tbody class="text-center">
                        </tbody> 
                    </table>
                </div> 
            </div>
        </div>
        <!-- END CHART PORTLET-->                     
    </div>
	
	<div class="modal fade" id="modal_new_kegiatan" kegiatan="dialog" aria-labelledby="modal_new_kegiatan" aria-hidden="true"> 
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
				<form action="javascript:;" id="form_kegiatan" class="form-horizontal" kegiatan="form">
					<div class="form-body">
						<input type="hidden" name="ID"  id="ID" class="form-control">
						<input type="hidden" name="method" id="method" class="form-control">
						
						<div class="form-group">
							<label class="control-label col-xs-3">Jenis Kegiatan <strong> <span class="font-red">(*)</span></strong></label>
							<div class="col-xs-4">
								<select name="JenisKegiatan" id="JenisKegiatan" class="form-control">
									<option value="0">--Pilih--</option>									
									<?php foreach ($jenis_kegiatan as $row) { ?>
										<option value="<?php echo $row->ID; ?>"><?php echo trim(ucwords(strtolower($row->Nama))); ?></option>
									<?php } ?>
								</select>
								<span class="help-block"></span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-xs-3">Nama <strong> <span class="font-red">(*)</span></strong></label>
							<div class="col-xs-4">
								<input name="NamaKegiatan" id="NamaKegiatan" placeholder="Nama Kegiatan" class="form-control" type="text" />
								<span class="help-block"></span> 
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-xs-3">Penyelenggara <strong> <span class="font-red">(*)</span></strong></label>
							<div class="col-xs-4">
								<input name="Penyelenggara" id="Penyelenggara" placeholder="Penyelenggara" class="form-control" type="text" />
								<span class="help-block"></span> 
							</div>
						</div> 
						
						<div class="form-group">
                            <label class="control-label col-md-3">Waktu Kegiatan <strong> <span class="font-red">(*)</span></strong></label>
                                <div class="col-md-4">
                                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                        <input type="text" class="form-control" name="StartDate" id="StartDate"> 
                                        <span class="input-group-addon"> s/d </span>
                                        <input type="text" class="form-control" name="EndDate" id="EndDate"> 
									</div>
                                                    <!-- /input-group -->
                                </div>
                        </div>
						
						<div class="form-group">
							<label class="control-label col-xs-3">Negara Tujuan <strong> <span class="font-red">(*)</span></strong></label>
							<div class="col-xs-4">
								<select name="Negara" id="Negara" class="form-control">
								<option value="">--Silahkan Pilih-</option>}
									<?php foreach ($negara as $row) { ?>
										<option value="<?php echo $row->id; ?>"><?php echo trim(ucwords(strtolower($row->nmnegara))); ?></option>
									<?php } ?>
								</select>								
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-3">Kota Tujuan <strong> <span class="font-red">(*)</span></strong></label>
							<div class="col-xs-4">
								<select name="Tujuan" id="Tujuan" class="form-control"></select>
							</div>  
							<div class="col-xs-4">
								<font class="font-red-mint"> Bila kota tujuan belum terdaftar silakan menghubungi Helpdesk <strong>(021)-38901137</strong> </font>
							</div> 
						</div>
						
						<div class="form-group">
							<label class="control-label col-xs-3">Status <strong> <span class="font-red">(*)</span></strong></label>
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
 <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
 <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
 <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
 <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
 <script src="<?php echo base_url(); ?>assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
 <script src="<?php echo base_url(); ?>assets/custom/scripts/manajemen/kegiatan.js" type="text/javascript"></script>
