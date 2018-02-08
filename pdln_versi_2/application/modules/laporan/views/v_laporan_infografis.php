<div class="portlet box green">        
    <div class="portlet-body">
        <div class="tabbable-custom nav-justified">
            <ul class="nav nav-tabs nav-justified">
                <li class="active">
                    <a href="#tab_1_1_1" data-toggle="tab" aria-expanded="true"> Penggunaan APBN </a>
                </li>
                <li class="">
                    <a href="#tab_1_1_2" data-toggle="tab" aria-expanded="false"> Jumlah Peserta </a>
                </li>
                <li>
                    <a href="#tab_1_1_3" data-toggle="tab"> Jumlah SP </a>
                </li>
                <li>
                    <a href="#tab_1_1_4" data-toggle="tab"> Kunjungan Per Negara </a>
                </li>
                <li>
                    <a href="#tab_1_1_5" data-toggle="tab"> Jenis Penugasan </a>
                </li>
            </ul>
            <div class="tab-content"> 
                <div class="tab-pane active" id="tab_1_1_1">
                    <p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered"> 
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-pie-chart font-light"></i>
                                        <span class="caption-subject bold uppercase font-light-haze"> 
                                            Data Penggunaan APBN Dalam Rangka Perjalanan Dinas Luar Negeri (Juta Rupiah)
                                        </span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse"> </a>	                
                                        <a href="javascript:;" class="reload"> </a>
                                        <a href="javascript:;" class="fullscreen"> </a>                        
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="chart_1" class="chart" style="height: 550px;"> </div>
                                </div>
                            </div>

                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-globe"></i>Tabel </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-responsive"> 
                                        <table class="table table-condensed table-bordered table-hover" id="tabel_penggunaan_apbn">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="vertical-align: middle;">No.</th>
                                                    <th class="text-center" style="vertical-align: middle;">KL</th>
                                                    <th class="text-center" style="vertical-align: middle;">Januari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Februari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Maret</th>
                                                    <th class="text-center" style="vertical-align: middle;">April</th>
                                                    <th class="text-center" style="vertical-align: middle;">Mei</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juni</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juli</th>
                                                    <th class="text-center" style="vertical-align: middle;">Agustus</th>
                                                    <th class="text-center" style="vertical-align: middle;">September</th>
                                                    <th class="text-center" style="vertical-align: middle;">Oktober</th>
                                                    <th class="text-center" style="vertical-align: middle;">November</th>
                                                    <th class="text-center" style="vertical-align: middle;">Desember</th>
                                                    <th class="text-center" style="vertical-align: middle;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $index1 = 1; ?>
                                                <?php foreach ($list_penggunaan_apbn as $item) { ?>
                                                <tr>
                                                    <td><?php echo $index1; ?></td>
                                                    <td><?php echo $item['lembaga'] ?></td>
                                                    <td class="text-right"><?php echo number_format($item['jan'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['feb'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['mar'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['apr'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['mei'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['jun'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['jul'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['agus'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['sep'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['okt'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['nov'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['des'],0,',','.'); ?></td>
                                                    <td class="text-right"><?php echo number_format($item['biaya'],0,',','.'); ?></td>
                                                </tr>
                                                <?php $index1++; ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </p>
                </div>
                <div class="tab-pane" id="tab_1_1_2">
                    <p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-pie-chart font-light"></i>
                                        <span class="caption-subject bold uppercase font-light-haze"> 
                                            Data Realisasi Jumlah Peserta Persetujuan Perjalanan Dinas Luar Negeri.
                                        </span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse"> </a>	                
                                        <a href="javascript:;" class="reload"> </a>
                                        <a href="javascript:;" class="fullscreen"> </a>                        
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="chart_2" class="chart" style="height: 550px;"> </div>
                                </div>
                            </div>

                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-globe"></i>Tabel </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-responsive"> 
                                        <table class="table table-condensed table-bordered table-hover" id="tabel_jumlah_peserta">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="vertical-align: middle;">No.</th>
                                                    <th class="text-center" style="vertical-align: middle;">KL</th>
                                                    <th class="text-center" style="vertical-align: middle;">Januari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Februari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Maret</th>
                                                    <th class="text-center" style="vertical-align: middle;">April</th>
                                                    <th class="text-center" style="vertical-align: middle;">Mei</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juni</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juli</th>
                                                    <th class="text-center" style="vertical-align: middle;">Agustus</th>
                                                    <th class="text-center" style="vertical-align: middle;">September</th>
                                                    <th class="text-center" style="vertical-align: middle;">Oktober</th>
                                                    <th class="text-center" style="vertical-align: middle;">November</th>
                                                    <th class="text-center" style="vertical-align: middle;">Desember</th>
                                                    <th class="text-center" style="vertical-align: middle;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $index1 = 1; ?>
                                                <?php foreach ($list_peserta as $item) { ?>
                                                <tr>
                                                    <td><?php echo $index1; ?></td>
                                                    <td><?php echo $item['lembaga'] ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jan'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['feb'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['mar'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['apr'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['mei'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jun'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jul'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['agus'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['sep'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['okt'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['nov'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['des'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jumlah'],0,',','.'); ?></td>
                                                </tr>
                                                <?php $index1++; ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    </p>					
                </div>
                <div class="tab-pane" id="tab_1_1_3">
                    <p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-pie-chart font-light"></i>
                                        <span class="caption-subject bold uppercase font-light-haze"> 
                                            Data Realisasi Jumlah Surat Persetujuan Perjalanan Dinas Luar Negeri.
                                        </span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse"> </a>
                                        <a href="javascript:;" class="reload"> </a>
                                        <a href="javascript:;" class="fullscreen"> </a>                        
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="chart_3" class="chart" style="height: 550px;"> </div>
                                </div>
                            </div>

                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-globe"></i>Tabel </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-responsive"> 
                                        <table class="table table-condensed table-bordered table-hover" id="tabel_jumlah_sp">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="vertical-align: middle;">No.</th>
                                                    <th class="text-center" style="vertical-align: middle;">KL</th>
                                                    <th class="text-center" style="vertical-align: middle;">Januari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Februari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Maret</th>
                                                    <th class="text-center" style="vertical-align: middle;">April</th>
                                                    <th class="text-center" style="vertical-align: middle;">Mei</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juni</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juli</th>
                                                    <th class="text-center" style="vertical-align: middle;">Agustus</th>
                                                    <th class="text-center" style="vertical-align: middle;">September</th>
                                                    <th class="text-center" style="vertical-align: middle;">Oktober</th>
                                                    <th class="text-center" style="vertical-align: middle;">November</th>
                                                    <th class="text-center" style="vertical-align: middle;">Desember</th>
                                                    <th class="text-center" style="vertical-align: middle;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $index1 = 1; ?>
                                                <?php foreach ($list_sp as $item) { ?>
                                                <tr>
                                                    <td><?php echo $index1; ?></td>
                                                    <td><?php echo $item['lembaga'] ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jan'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['feb'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['mar'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['apr'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['mei'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jun'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jul'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['agus'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['sep'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['okt'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['nov'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['des'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jumlah'],0,',','.'); ?></td>
                                                </tr>
                                                <?php $index1++; ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>					
                    </p>					
                </div>				
                <div class="tab-pane" id="tab_1_1_4">
                    <p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-pie-chart font-light"></i>
                                        <span class="caption-subject bold uppercase font-light-haze"> 
                                            Data Realisasi Jumlah Kunjungan Per-Negara.
                                        </span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse"> </a>	                
                                        <a href="javascript:;" class="reload"> </a>
                                        <a href="javascript:;" class="fullscreen"> </a>                        
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="chart_4" class="chart" style="height: 550px;"> </div>
                                </div>
                            </div>

                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-globe"></i>Tabel </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-responsive"> 
                                        <table class="table table-condensed table-bordered table-hover" id="tabel_kunjungan_negara">
                                            <thead>
                                                <tr> 
                                                    <th class="text-center" style="vertical-align: middle;">No.</th>
                                                    <th class="text-center" style="vertical-align: middle;">Negara</th>
                                                    <th class="text-center" style="vertical-align: middle;">Januari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Februari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Maret</th>
                                                    <th class="text-center" style="vertical-align: middle;">April</th>
                                                    <th class="text-center" style="vertical-align: middle;">Mei</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juni</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juli</th>
                                                    <th class="text-center" style="vertical-align: middle;">Agustus</th>
                                                    <th class="text-center" style="vertical-align: middle;">September</th>
                                                    <th class="text-center" style="vertical-align: middle;">Oktober</th>
                                                    <th class="text-center" style="vertical-align: middle;">November</th>
                                                    <th class="text-center" style="vertical-align: middle;">Desember</th>
                                                    <th class="text-center" style="vertical-align: middle;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $index1 = 1; ?>
                                                <?php foreach ($list_negara as $item) { ?>
                                                <tr>
                                                    <td><?php echo $index1; ?></td>
                                                    <td><?php echo $item['country'] ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jan'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['feb'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['mar'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['apr'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['mei'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jun'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jul'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['agus'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['sep'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['okt'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['nov'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['des'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jumlah'],0,',','.'); ?></td>
                                                </tr>
                                                <?php $index1++; ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    </p>
                </div>
                <div class="tab-pane" id="tab_1_1_5">
                    <p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-pie-chart font-light"></i>
                                        <span class="caption-subject bold uppercase font-light-haze"> 
                                            Data Realisasi Jenis Penugasan.
                                        </span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse"> </a>	                
                                        <a href="javascript:;" class="reload"> </a>
                                        <a href="javascript:;" class="fullscreen"> </a>                        
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div id="chart_5" class="chart" style="height: 550px;"> </div>
                                </div>
                            </div>

                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-globe"></i>Tabel </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-responsive"> 
                                        <table class="table table-condensed table-bordered table-hover" id="tabel_penggunaan_apbn">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="vertical-align: middle;">No.</th>
                                                    <th class="text-center" style="vertical-align: middle;">Jenis Penugasan</th>
                                                    <th class="text-center" style="vertical-align: middle;">Januari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Februari</th>
                                                    <th class="text-center" style="vertical-align: middle;">Maret</th>
                                                    <th class="text-center" style="vertical-align: middle;">April</th>
                                                    <th class="text-center" style="vertical-align: middle;">Mei</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juni</th>
                                                    <th class="text-center" style="vertical-align: middle;">Juli</th>
                                                    <th class="text-center" style="vertical-align: middle;">Agustus</th>
                                                    <th class="text-center" style="vertical-align: middle;">September</th>
                                                    <th class="text-center" style="vertical-align: middle;">Oktober</th>
                                                    <th class="text-center" style="vertical-align: middle;">November</th>
                                                    <th class="text-center" style="vertical-align: middle;">Desember</th>
                                                    <th class="text-center" style="vertical-align: middle;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $index1 = 1; ?>
                                                <?php foreach ($list_tugas as $item) { ?>
                                                <tr>
                                                    <td><?php echo $index1; ?></td>
                                                    <td><?php echo $item['jenis_tugas'] ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jan'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['feb'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['mar'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['apr'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['mei'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jun'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jul'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['agus'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['sep'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['okt'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['nov'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['des'],0,',','.'); ?></td>
                                                    <td class="text-center"><?php echo number_format($item['jumlah'],0,',','.'); ?></td>
                                                </tr>
                                                <?php $index1++; ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    </p>
                </div>				
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/amcharts/amcharts/plugins/export/export.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/global/plugins/amcharts/amcharts/plugins/export/export.css" type="text/css" media="all" />
<script src="<?php echo base_url(); ?>assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url(); ?>assets/custom/scripts/laporan/infografis.js?_dt=201706211600" type="text/javascript" charset="utf-8"></script>
<!-- Styles -->
<style>
    .amcharts-pie-slice {
        transform: scale(1);
        transform-origin: 50% 50%;
        transition-duration: 0.3s;
        transition: all .3s ease-out;
        -webkit-transition: all .3s ease-out;
        -moz-transition: all .3s ease-out;
        -o-transition: all .3s ease-out;
        cursor: pointer;
        box-shadow: 0 0 30px 0 #000;
    }

    .amcharts-pie-slice:hover {
        transform: scale(1.1);
        filter: url(#shadow);
    }
</style>