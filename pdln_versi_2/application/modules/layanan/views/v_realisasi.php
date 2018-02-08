<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" /> 
<div class="row">
    <div class="col-md-12"> 
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze"> 
                        Daftar Realisasi
                    </span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a> 
                    <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                    <a href="javascript:;" class="reload"> </a>
                    <a href="javascript:;" class="fullscreen"> </a>
                </div>
            </div>
            <div class="portlet-body"> 
				<?php if($this->ion_auth->is_allowed(40,'create')) {?> 
				<div class="actions">
					<!-- <a href="<?php //echo base_url(); ?>layanan/realisasi/add" class="nav-link ">
						<button class="btn btn-outline blue"> <i class="fa fa-plus"></i> Tambah Realisasi Baru </button> 	
					</a> -->
                </div><br/><br/>
				<?php }?>
                <div class="table">
                    <table class="table table-hover table-bordered" id="tabel_realisasi">
                        <thead>
                            <tr>
                                <th>ID PDLN</th>
                                <th>No.</th>
                                <th>Laporkan</th>
                                <th>Nomor Register</th>
                                <th>Tanggal Upload Laporan</th>
								<th>Nomor Surat</th> 
                                <th>Status Pelaporan</th>
								<th>Jenis Permohonan</th>
								<th>Status</th>
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
</div>
<!-- END ROW -->
<script src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.js"></script>
<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/realisasi.js" type="text/javascript"></script>