<div class="row">
    <div class="col-md-12"> 
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze"> 
                        Daftar Perpanjangan/Ralat 
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
                <?php if ($this->ion_auth->is_allowed(26, 'create')) {
                    ?>
                    <div class="actions">  
                        <!-- <a href="<?php //echo base_url(); ?>layanan/perpanjangan/add" class="nav-link ">
                            <button class="btn btn-outline blue"> <i class="fa fa-plus"></i> Tambah Perpanjangan / Ralat Baru </button> 	
                        </a> -->
                        <a href="<?php echo base_url(); ?>layanan/perpanjangan/persetujuan_list" class="nav-link ">
                            <button class="btn btn-outline blue"> <i class="fa fa-plus"></i>  Perpanjangan / Ralat  Berdasarkan Persetujuan</button>     
                        </a>
                    </div><br/><br/>
                <?php } ?>
                <div class="table">
                    <table class="table table-hover table-bordered" id="tabel_perpanjangan">
                        <thead>
                            <tr>
                                <th>ID PDLN</th>
                                <th>No.</th>
                                <th>Aksi</th>
                                <th>Nomor Register</th>
                                <th>Tanggal Register</th>
                                <th>Nomor Surat</th>
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

<div class="modal fade" id="modal_renew" name="modal_renew" role="dialog" tabindex="-1" aria-labelledby="modal_renew" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text"><i class="fa fa-history"> </i> Permohonan baru </span>
                    </h3>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart font-dark hide"></i>
                                    <span class="caption-subject font-dark bold uppercase"><i class="fa fa-th-list"> </i> Berdasarkan PDLN Lama !!</span>
                                    <span class="caption-helper">
                                        <span class="badge badge-success total_catatan"></span>
                                    </span>
                                </div>            
                            </div>
                            <div class="portlet-body">
                                <div class="scroller" style="height: 160px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                    <div class="general-item-list" id="general-item-list_">
                                    <!-- AUTOMATE VIA JQUERY -->
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn blue-dark"> <i class="fa fa-left"> </i> Kembali </button>
            </div>
        </div>          
    </div>
</div>
<!-- END ROW --> 
<script src="<?php echo base_url(); ?>assets/custom/scripts/layanan/perpanjangan.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-media.js"></script>