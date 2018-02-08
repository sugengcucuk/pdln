<div class="row">
    <div class="col-md-12"> 
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze"> 
                        Daftar Pembatalan 
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
                <?php if ($this->ion_auth->is_allowed(27, 'create')) { ?>
                <div class="actions">
                    <!-- <a href="<?php // echo base_url(); ?>layanan/pembatalan/add" class="nav-link ">
                        <button class="btn btn-outline blue" id="new_region"> <i class="fa fa-plus"></i> Tambah Pembatalan</button> 	
                    </a> -->
                    <a href="<?php echo base_url(); ?>layanan/pembatalan/persetujuan_list" class="nav-link ">
                        <button class="btn btn-outline blue" id="new_region"> <i class="fa fa-plus"></i> Pembatalan Dari Persetujuan</button>     
                    </a>
                    
                </div><br/><br/>
                <?php }?>
                <div class="table">
                    <table class="table table-hover table-bordered" id="tabel_pembatalan">
                        <thead>
                            <tr>
                                <th>ID PDLN</th>
                                <th>No.</th>
                                <th>Aksi</th>
                                <th>Nomor Register</th>
                                <th>Tanggal Register</th>
                                <th>Nomor Surat</th>
                                <th>Alasan Pembatalan</th>
                                <th>Jenis permohonan</th>
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
<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/pembatalan.js" type="text/javascript"></script>