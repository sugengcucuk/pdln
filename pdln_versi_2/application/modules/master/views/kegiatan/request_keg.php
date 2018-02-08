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
                        Perubahan Kegiatan
                    </span>
                </div>
            <div class="portlet-body" id="modal_new_kegiatan">
                <input type="hidden" name="ID" id="ID" value="<?php echo $id_keg;?>">
                <input type="hidden" name="method" value="ubah" id="method">
            	<div class="form-group col-md-12">
                    <label class="control-label col-md-1">
                    </label> 
                    <div class="col-md-4">
                        <b>Nama Lama Kegiatan</b>
                    </div>  
                    <label class="control-label col-md-1">
                    </label>
                    <label class="control-label col-md-1"> 
                    </label>
                    <div class="col-md-4">
                        <b>Nama Usulan Kegiatan</b>
                    </div>  
                </div>
            	<div class="form-group col-md-12">
                    <label class="control-label col-md-1">Jenis Kegiatan
                        <span class="required" aria-required="true"> * </span>
                    </label> 
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="<?php echo $keg_master->JenisKegiatan;?>" readonly>  
                    </div>  
                    <label class="control-label col-md-1">
                    </label>
                    <label class="control-label col-md-1">Jenis Kegiatan
                        <span class="required" aria-required="true"> * </span>
                    </label>
                    <div class="col-md-4">
                        <select name="JenisKegiatan" id="JenisKegiatan" class="form-control">
                        <option value="0">--Pilih--</option>
                            <?php foreach ($jenis_kegiatan as $row) { ?>
                                <option value="<?php echo $row->ID; ?>"><?php echo trim(ucwords(strtolower($row->Nama))); ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" class="form-control baru" id="JenisKegiatan_def" value="<?php  echo $keg_req->JenisKegiatan;?>" >  
                    </div>  
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-1">Nama  Kegiatan
                        <span class="required" aria-required="true"> * </span>
                    </label> 
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="<?php echo $keg_master->NamaKegiatan;?>" readonly>  
                    </div>  
                    <label class="control-label col-md-1">
                    </label>
                    <label class="control-label col-md-1">Nama  Kegiatan
                        <span class="required" aria-required="true"> * </span>
                    </label>
                    <div class="col-md-4">
                        <input type="text" class="form-control baru" name="NamaKegiatan" id="NamaKegiatan" value="<?php echo $keg_req->NamaKegiatan;?>" >  
                    </div>  
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-1">Penyelenggara 
                        <span class="required" aria-required="true"> * </span>
                    </label> 
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="<?php echo $keg_master->Penyelenggara;?>" readonly>  
                    </div>  
                    <label class="control-label col-md-1">
                    </label>
                    <label class="control-label col-md-1">Penyelenggara 
                        <span class="required" aria-required="true"> * </span>
                    </label>
                    <div class="col-md-4">
                        <input type="text" class="form-control baru" nama="Penyelenggara" id="Penyelenggara" value="<?php echo $keg_req->Penyelenggara;?>" >  
                    </div>  
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-1">Waktu Kegiatan
                        <span class="required" aria-required="true"> * </span>
                    </label> 
                    <div class="col-md-4">
                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                            <input type="text" class="form-control " readonly name="StartDate_master" id="StartDate_master" value="<?php echo date("d-m-Y", strtotime($keg_master->StartDate)); ?>">
                            <span class="input-group-addon"> s/d </span>
                            <input type="text" class="form-control baru" readonly name="EndDate_master" id="EndDate+master" value="<?php echo date("d-m-Y", strtotime($keg_master->EndDate)); ?>">
                        </div>
                    </div>  
                    <label class="control-label col-md-1">
                    </label>
                    <label class="control-label col-md-1">Waktu Kegiatan
                        <span class="required" aria-required="true"> * </span>
                    </label>
                    <div class="col-md-4">
                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                            <input type="text" class="form-control baru" name="StartDate" id="StartDate" value="<?php echo date("d-m-Y", strtotime($keg_master->StartDate)); ?>">
                            <span class="input-group-addon"> s/d </span>
                            <input type="text" class="form-control baru" name="EndDate" id="EndDate" value="<?php echo date("d-m-Y", strtotime($keg_master->EndDate)); ?>">
                        </div>
                    </div>  
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-1">Negara Tujuan
                        <span class="required" aria-required="true"> * </span>
                    </label> 
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="<?php echo $keg_master->nmnegara;?>" readonly>  
                    </div>  
                    <label class="control-label col-md-1">
                    </label>
                    <label class="control-label col-md-1">Negara Tujuan
                        <span class="required" aria-required="true"> * </span>
                    </label>
                    <div class="col-md-4">
                        <select name="Negara" id="Negara" class="form-control">
                                <option value="">--Silahkan Pilih-</option>}
                                    <?php foreach ($negara as $row) { ?>
                                        <option value="<?php echo $row->id; ?>"><?php echo trim(ucwords(strtolower($row->nmnegara))); ?></option>
                                    <?php } ?>
                                </select>
                        <input type="hidden" class="form-control baru" id="Negara_def" value="<?php echo $keg_req->Negara;?>">  
                    </div>  
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-1">Kota Tujuan
                        <span class="required" aria-required="true"> * </span>
                    </label> 
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="<?php echo $keg_master->nmkota;?>" readonly>  
                    </div>  
                    <label class="control-label col-md-1">
                        <!-- <span class="required" aria-required="true"> * </span> -->
                    </label>
                    <label class="control-label col-md-1">Kota Tujuan
                        <span class="required" aria-required="true"> * </span>
                    </label>
                    <div class="col-md-4">
                        <select name="Tujuan" id="Tujuan" class="form-control"></select>
                        <input type="hidden" class="form-control baru" id="Tujuan_def" value="<?php echo $keg_req->Tujuan;?>">
                        <input type="hidden" class="form-control baru" id="Status" value="<?php echo $keg_req->Status;?>">
                        
                    </div>  
                </div>
                <div class="form-group col-md-12">
                	<div class="col-md-4">
					
                	</div>
                	<div class="col-md-4">
                	<?php if ($keg_req->is_request == '1') {; ?>
                    <button type="submit" id="edit" class="btn submit btn-info"> Edit </button>
					<button type="submit" id="simpan" class="btn submit btn-primary"> simpan </button>
					<?php }?>
					<button type="button" id="batal" data-dismiss="modal" class="btn btn-default"> Batal</button>
                	</div><div class="col-md-4">
					
                	</div>
                </div>






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
<script src="<?php echo base_url(); ?>assets/custom/scripts/master/req_kegiatan.js?_dt=201606211658" type="text/javascript"></script>
