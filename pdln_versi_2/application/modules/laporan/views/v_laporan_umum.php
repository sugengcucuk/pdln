<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <h4>Form Pencarian Data</h4>
            </div>
            <div class="portlet-body">
                <div class="form">
                    <form action="javascript:;" method="post" accept-charset="utf-8" id="form-filter-personal" name="form-filter-personal" class="form-horizontal">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-xs-3">Periode Tanggal Register</label>
                                <div class="col-xs-9">
                                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                        <input type="text" class="form-control" id="tgl_from" name="tgl_from" placeholder="tanggal awal" />
                                        <span class="input-group-addon"> s/d </span>
                                        <input type="text" class="form-control" id="tgl_to" name="tgl_to" placeholder="tanggal akhir"/>
                                    </div>
                                    <!-- /input-group -->
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-3" for="nip">Instansi Kementerian/Lembaga</label>
                                <div class="col-xs-5">
                                    <select id="instansi" name="instansi">
                                        <?php foreach ($instansi as $row) { ?>
                                            <option value="">Pilih</option>
                                            <option value="<?php echo $row->ID; ?>"><?php echo trim(ucwords(strtolower($row->Nama))); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-3" for="nik">Jenis Kegiatan</label>
                                <div class="col-xs-5">
                                    <select id="kegiatan" name="kegiatan">
                                        <?php foreach ($kegiatan as $row) { ?>
                                            <option value="">Pilih</option>
                                            <option value="<?php echo $row->ID; ?>"><?php echo trim(ucwords(strtolower($row->Nama))); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-3" for="status">Status</label>
                                <div class="col-xs-3">
                                    <select name="status" class="form-control" id="status">
                                        <option value="">Pilih</option>
                                        <!-- <option value="0">Draft</option> -->
                                        <option value="1">Pemohon</option>
                                        <option value="2">Focal Point</option>
                                        <option value="3">Analis</option>
                                        <option value="4">Kasubag</option>
                                        <option value="5">Kabag</option>
                                        <option value="6">Kepala Biro</option>
                                        <option value="7">Sesmen</option>
                                        <option value="8">Mensesneg</option>
                                        <option value="9">TU Sesmen</option>
                                        <option value="10">TU Mensesneg</option>
                                        <option value="11">Disetujui</option>
                                        <option value="12">Dikembalikan</option>
                                        <option value="13">Ralat</option>
                                        <option value="14">Perpanjangan</option>
                                        <option value="15">Pembatalan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-action">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="pull-left">
                                            <button type="button" class="btn btn-primary btn-sm" id="btn-apply" name="btn-apply" title="apply"><i class="fa fa-check"> </i> Apply</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-right">
                                            <button type="button" class="btn green-dark btn-sm" id="btn-export" name="btn-export" title="Eksport Excell"><i class="fa fa-arrow-right"> </i> Export To Excell <i class="fa fa-file-excel-o"> </i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="portlet box blue bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-light"></i>
                    <span class="caption-subject bold uppercase font-light-haze">
                        Hasil Pencarian
                    </span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>
                    <a href="javascript:;" class="reload"> </a>
                    <a href="javascript:;" class="fullscreen"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-hover" id="tabel_laporan_umum">
                        <thead>
                            <tr>
                                <!--
                                <th class="text-center" style="vertical-align: middle;">NIP</th>
                                <th class="text-center" style="vertical-align: middle;">NIK</th>
                                -->
                                <th class="text-center" style="vertical-align: middle;">ID</th>
                                <th class="text-center" style="vertical-align: middle;">No.</th>
                                <th class="text-center" style="vertical-align: middle;">Tgl Register</th>
                                <th class="text-center" style="vertical-align: middle;">No.Register</th>
                                <th class="text-center" style="vertical-align: middle;">Tgl SP</th>
                                <th class="text-center" style="vertical-align: middle;">No.Surat Persetujuan</th>
                                <th class="text-center" style="vertical-align: middle;">Jenis Permohonan</th>
                                <th class="text-center" style="vertical-align: middle;">Status</th>
                                <th class="text-center" style="vertical-align: middle;">Unit Pemohon</th>
                                <th class="text-center" style="vertical-align: middle;">Nama Instansi</th>
                                <th class="text-center" style="vertical-align: middle;">Level Pejabat</th>
                                <th class="text-center" style="vertical-align: middle;">Jenis Kegiatan</th>
                                <th class="text-center" style="vertical-align: middle;">Nama Kegiatan</th>
                                <th class="text-center" style="vertical-align: middle;">Negara Tujuan</th>
                                <th class="text-center" style="vertical-align: middle;">Jml Peserta</th>
                                <th class="text-center" style="vertical-align: middle;">Waktu Kegiatan</th>
                                <th class="text-center" style="vertical-align: middle;">Pemroses</th>
                                <!--<th class="text-center" style="vertical-align: middle;">Tgl Release</th>-->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/custom/scripts/laporan/umum.js?_dt=201706211600" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
