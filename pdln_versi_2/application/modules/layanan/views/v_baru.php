<div class="row">
    <div class="col-md-12"> 
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze"> 
                        Daftar Permohonan Baru
                    </span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>
                    <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                    <a href="javascript:;" class="reload"> </a>
                    <a href="javascript:;" class="fullscreen"> </a>
                    <a href="javascript:;" class="remove"> </a>
                </div>
            </div>
            <div class="portlet-body">
				<?php if($this->ion_auth->is_allowed(25,'create')) 
				{?>
				<div class="actions">					
					<button class="btn btn-outline blue" name="btn-add-pdln" type="button" id="btn-add-pdln"> <i class="fa fa-plus"></i> Tambah Permohonan Baru </button>
                </div><br/><br/>
				<?php }?> 
                <div class="table">
                    <table class="table table-hover table-bordered" id="tabel_permohonan_baru">
                        <thead>
                            <tr>
                                <th class="text-center">ID PDLN</th>
                                <th class="text-center">No.</th>
								<th class="text-center">Nomor Register</th>
                                <th class="text-center">Tanggal Register</th>
								<th class="text-center">Nomor Surat</th>
								<th class="text-center">Jenis Kegiatan</th>
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
</div>
<!-- END ROW -->

<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/baru.js" type="text/javascript"></script>