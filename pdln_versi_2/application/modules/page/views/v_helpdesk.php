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
				<?php if($this->ion_auth->is_allowed(28,'create')) {?>
				 <div class="actions">
                    <a href="<?php echo base_url('page/helpdesk/ask/0/'); ?>"><button class="btn btn-outline blue" id="new_kegiatan"> <i class="fa fa-plus"></i> Pertanyaan  Baru </button></a>
                </div>
				<br/><br/>
				<?php }?>
			   <div class="table">
                    <table class="table table-hover table-bordered" id="tabel_kegiatan_manage">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No</th>
                                <th>Aksi</th>
								<th>Judul</th>
								<th>Jenis Pertanyaan</th>
								<th>Status</th>
								<th>Tanggal Dibuat</th>
                                <th>Perubahan terakhir</th>
								<!-- <th>Dari</th> -->
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
</div>
<!-- END ROW -->
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/custom/scripts/page/helpdesk.js?_dt=201606211658" type="text/javascript"></script>
