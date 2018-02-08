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
                <div class="caption" id="string_judul">
                    <i class="icon-eye font-green-haze"></i>
                        <span class="caption-subject bold uppercase font-green-haze">
                            <?php if(!empty($header_tiket->judul)){
                                echo 'NO Tiket : '.$no_tiket;
                            }   ?>   
                        </span>
                </div>
            </div>
            <div class="portlet-title">
                <div class="portlet-body" id="modal_new_kegiatan">
                    <input type="hidden" name="ID" id="ID" value="<?php if(!empty($id_tiket)){
                                echo $id_tiket;
                            }?>">
                    <input type="hidden" name="jenis_ask" value="<?php if(!empty($id_tiket)){
                                echo $header_tiket->help;
                            }?>" id="jenis_ask">
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2">Judul
                        </label> 
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="judul">  
                            <span  class="caption-subject bold uppercase font-green-haze"   id="string_judul_input"><?php if(!empty($header_tiket->judul)){ echo $header_tiket->judul;} ?></span>
                        </div>  
                        <label class="control-label col-md-1">
                        </label> 
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2">Kategori
                        </label> 
                        <div class="col-md-8">
                            <select name="JenisKegiatan" id="JenisKegiatan" class="form-control" >
                                <option value="1">Teknis Pelayanan</option>
                                <option value="2">Sistem Aplikasi</option>
                            </select>
                        </div>  
                        <label class="control-label col-md-1">
                        </label> 
                    </div>

                    <br><br><br>
                    <?php 
                    if (!empty($detail_tiket)){
                        foreach ($detail_tiket as $value) {
                    ?>
                    <div class="form-group col-md-12">
                        <label class="control-label col-md-2"><?php echo $value->NamaLevel ?>
                        </label> 
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="<?php echo $value->steatment;?>" readonly>  
                        </div>  
                        <label class="control-label col-md-1">
                        </label> 
                    </div>
                    <?php }}?>
	           </div>
            </div>
            <div class="portlet-title">
                 <div class="form-group col-md-12">
                        <label class="control-label col-md-2">
                        </label> 
                        <div class=" col-md-7"  style="position:relative;margin-top:10%;" >
                            <input type="text" class="form-control" id="comenting" placeholder="Komentar"> <br>
                            <button class="btn-success" id="send_koment"><i class="fa fa-paper-plane" aria-hidden="true" ></i></button>
                            <button class="btn-default" id="cancel">kembali</button>

                        </div>  
                        <label class="control-label col-md-2">
                        </label> 
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
<script src="<?php echo base_url(); ?>assets/custom/scripts/page/helpdesk.js?_dt=201606211658" type="text/javascript"></script>
