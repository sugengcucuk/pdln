<div class="row">
    <div class="col-md-12">
        <!-- BEGIN DASHBOARD PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-eye font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze">
                        Monitoring Progres Layanan PDLN
                    </span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>
                    <a href="javascript:;" class="reload"> </a>
                    <a href="javascript:;" class="fullscreen"> </a>
                </div>
            </div>
            <input type="hidden" name="test_survey" id="test_survey" value="<?php echo($test_survey)?>">
            <div class="portlet-body">
                <div class="table">
                    <table class="table table-hover table-bordered" id="tabel_dashboard" style="width:100% !important;">
                        <thead>
                            <tr>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">ID PDLN</th>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">No.</th>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">No. Surat UP</th>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">Tgl Surat UP</th>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">No. Register</th>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">Tgl. Register</th>
                                <th class="text-center" width="10%" style = "vertical-align: middle;">Catatan</th>
                                <th class="text-center" width="15%" style = "vertical-align: middle;">Aksi</th>
                                <th class="text-center" width="4%" style = "vertical-align: middle;">Unduh SP</th>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">No. SP</th>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">Tgl. SP</th>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">Status</th>
                                <th class="text-center" width="8%" style = "vertical-align: middle;">Tgl. Update</th>
                                <th class="text-center" width="7%" style = "vertical-align: middle;">No. Surat FP</th>
                                <!--th class="text-center" width="7%" style = "vertical-align: middle;">No. Surat Pemohon</th//-->
                                <th class="text-center" width="8%" style = "vertical-align: middle;">Jenis Permohonan</th>
                                <th class="text-center" width="15%" style = "vertical-align: middle;">Jenis Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END DASHBOARD PORTLET-->
    </div>
</div>
<!-- BEGIN MODAL LOG CATATAN -->
<div class="modal fade" id="modal_log_catatan" name="modal_log_catatan" role="dialog" tabindex="-1" aria-labelledby="modal_log_catatan" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text"><i class="fa fa-history"> </i> Log Catatan Pengembalian</span>
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
                                    <span class="caption-subject font-dark bold uppercase"><i class="fa fa-th-list"> </i> List Catatan</span>
                                    <span class="caption-helper">
                                        <span class="badge badge-success total_catatan"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="scroller" style="height: 160px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                    <div class="general-item-list" id="general-item-list">
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
<?php if ($test_survey > 0){?>
<div class="modal fade" id="modal_survey" name="modal_survey" role="dialog" tabindex="-1" aria-labelledby="modal_survey" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" enctype="multipart/form-data" action="javascript:;" id="form_survey_responden" class="form-horizontal" user="form">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h3 class="font-blue-madison uppercase bold text-center">
                        <span class="title-text">Survey SIMPLE</span>
                    </h3>
                </div>
            </div>

            <?php $k = 0;foreach ($survey_not_yet as $surveyList) {?>
            <div class="modal-body">
                <div class="row">
                    <div class="font-blue-madison uppercase bold text-center">
                        <b><?php echo $surveyList[1];?></b>
                    </div>
                    <div  class="col-md-12">
                        <p><?php echo $surveyList[2];?></p>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-advanced table-striped table-condensed table-hover table-bordered" id="list_survey" style="width:100% !important;">
                            <tr>
                                <td class="text-center" >No</td>
                                <td class="text-center" >pertanyaan</td> 
                                <td class="text-center" >Tidak Setuju</td>
                                <td class="text-center" >Kurang Setuju</td>
                                <td class="text-center" > Setuju</td>
                                <td class="text-center" >Sangat Setuju</td>
                            </tr>
                            <?php
                            $i = 1; foreach ($surveyList[3] as $value) {
                            ?>
                            <tr>
                                <td class="text-center" ><?php echo $i ?></td>
                                <td class="text-center" ><?php echo $value->question;?></td> 
                                <td class="text-center" ><input type="radio" name="ans_<?php echo $value->id_survey.'_'.$value->id;?>" id="ans_<?php echo $value->id_survey.'_'.$i;?>" value="1" required></td>
                                <td class="text-center" ><input type="radio" name="ans_<?php echo $value->id_survey.'_'.$value->id;?>" id="ans_<?php echo $value->id_survey.'_'.$i;?>" value="2"></td>
                                <td class="text-center" ><input type="radio" name="ans_<?php echo $value->id_survey.'_'.$value->id;?>" id="ans_<?php echo $value->id_survey.'_'.$i;?>" value="3"></td>
                                <td class="text-center" ><input type="radio" name="ans_<?php echo $value->id_survey.'_'.$value->id;?>" id="ans_<?php echo $value->id_survey.'_'.$value->id;?>" value="4"></td>
                            </tr>
                            <?php $i++;
                        }?>
                        <input type="hidden" name="jum_pertanyaan_<?php echo $surveyList[0];?>" id="jum_pertanyaan_<?php echo $surveyList[0];?>" value="<?php echo $i;?>">
                        <input type="hidden" name="id_survey_<?php echo $k;?>" id="id_survey_<?php echo $k;?>" value="<?php echo $surveyList[0];?>">
                        </table>
                    </div>
                </div>
            </div>
            <?php $k++; }?>
            <input type="hidden" name="jumlah_survey" id="jumlah_survey" value="<?php echo $k;?>">

            <div class="modal-footer">
                <button type="button" class="btn blue-dark" id="survey_wajib"> <i class="fa fa-left"> </i> Kirim </button>
            </div>
        </div>
    </form>
    </div>
</div>
<?php }?>
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
<!-- END MODAL LIST DATA PERMOHONAN -->
<!-- END ROW -->
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/fancybox/source/helpers/jquery.fancybox-media.js"></script>
<script src="<?php echo base_url(); ?>assets/custom/scripts/dashboard.js" type="text/javascript"></script>