
<div class="row">
    <div class="col-md-12"> 
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze"> 
                        Daftar Surat Masuk Saya
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
                <div class="table"> 
                    <table class="table table-hover table-bordered" id="tabel_task_manage">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nomor Register</th>
                                <th>Tanggal Permohonan</th>
                                <th class="text-center">Aksi</th>  
                                <th class="text-center">Arsipkan</th>  
								<th>Nomor Surat FP</th>  
								<th>Unit Pemohon</th>
								<th>Unit Focal Point</th>
								<th class="text-center">Jenis Permohonan</th>
								<th>Jenis Kegiatan</th>
								<th class="text-center">Status</th>
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
<div class="modal fade" id="archived_form" name="archived_form" role="dialog" tabindex="-1" aria-labelledby="archived_form" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text"><i class="fa fa-history"> </i> Permohonan Di-Arsipkan </span>
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
                                    <span class="caption-subject font-dark bold uppercase"><i class="fa fa-th-list"> </i>Permohonan PDLN Akan Ter-Arsipkan </span>
                                    <span class="caption-helper">
                                        <span class="badge badge-success total_catatan"></span>
                                    </span>
                                </div>            
                            </div>
                            <div class="portlet-body">
                                <div class="scroller" style="height: 160px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                    <div class="general-item-list" id="general-item-list_">
                                    <!-- AUTOMATE VIA JQUERY -->
                                    <!-- <div class="item-details"></div> -->
                                        <!-- <div class="form-group"> -->
                                                <!-- <label class="control-label col-md-3"> Catatan : </label>
                                                <div class="col-md-4">
                                                    <textarea id="note" name="note" rows="3" cols="40"></textarea> 
                                                </div>  -->
                                        <!-- </div> -->
                                    <!-- </div>                                 -->
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

<script src="<?php echo base_url(); ?>assets/custom/scripts/kotak_surat/task.js?_dt=201706211600" type="text/javascript"></script>