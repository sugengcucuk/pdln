<div class="row">
<div class="col-md-12">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-eye font-green-haze"></i>
				<span class="caption-subject bold uppercase font-green-haze">
					Daftar Jenis Kegiatan
				</span>
			</div>

		</div>
		<div class="portlet-body">
			<?php if($this->ion_auth->is_allowed(28,'create')) {?>
			 <div class="actions">
				<button class="btn btn-outline blue" id="new_jenis_kegiatan"> <i class="fa fa-plus"></i> Tambah Jenis Kegiatan Baru </button>
			</div>
			<br/><br/>
			<?php }?>
			<div class="table">
				<table class="table table-hover table-bordered" id="tabel_jenis_kegiatan_manage">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">No</th>
							<th class="text-center">Jenis Kegiatan</th>
							<th class="text-center">Sub Kategori Kegiatan</th>
							<th class="text-center">Kategori Kegiatan</th>
							<th class="text-center">Kodifikasi</th>
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
</div>
</div>
<!-- END ROW -->
<div class="modal fade" id="modal_new_jenis_kegiatan" jenis_kegiatan="dialog" aria-labelledby="modal_new_jenis_kegiatan" aria-hidden="true">
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<div class="modal-title">
				<h3 class="font-green uppercase bold"> <i class="icon-plus"></i>
					<span class="title-text"></span>
				</h3>
			</div>
		</div>
		<div class="modal-body form">
			<!-- BEGIN FORM-->
			<form action="javascript:;" id="form_jenis_kegiatan" class="form-horizontal" jenis_kegiatan="form">
				<div class="alert alert-danger display-hide">
					<button class="close" data-close="alert"></button>
					<span class>Data bermasalah, Silahkan lengkapi data.</span>
				</div>
				<div class="form-body">
					<input type="hidden" name="ID"  id="ID" class="form-control">
					<input type="hidden" name="method" id="method" class="form-control">

					<div class="form-group">
						<label class="control-label col-xs-3">Kategori Kegiatan</label>
						<div class="col-xs-4">
							<select name="Kategori" id="Kategori" class="form-control">
								<option value="">--Pilih--</option>
								<?php foreach ($kategori as $row) { ?>
									<option value="<?php echo $row->ID; ?>"><?php echo trim(ucwords(strtolower($row->Nama))); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-3">Sub Kategori Kegiatan</label>
						<div class="col-xs-4">
							<select name="SubKategori" id="SubKategori" class="form-control"></select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-3">Jenis Kegiatan</label>
						<div class="col-xs-7">
							<input name="Nama" id="Nama" placeholder="Nama Jenis Kegiatan" class="form-control" type="text" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-3">Kodifikasi</label>
						<div class="col-xs-7">
							<input name="Kodifikasi" id="Kodifikasi" placeholder="Kodifikasi" class="form-control" type="text" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-3">Set Dokumen Pemohon</label>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-3">Nama Dokumen</label>
						<label class="control-label col-xs-3">Mandatory</label>
					</div>
						<?php foreach ($dokumen_pemohon as $dp){?>
							<div class="form-group">
								<label class="control-label col-xs-1"></label>
								<div class="col-xs-9">
									<div class="col-xs-5"><input class="pemohon<?=$dp->ID;?>" type="checkbox" id="pemohon<?=$dp->ID?>required" value='<?=$dp->ID?>' name="doc_pemohon[]">&nbsp;&nbsp;<?=$dp->Nama?></div>
									<div class="col-xs-4"><input class="pemohon<?=$dp->ID;?>" type="checkbox" id="pemohon<?=$dp->ID?>mandatory" value='1' name="pemohon<?=$dp->ID?>"></div>
								</div>
							</div>
						<?php }	?>
					<div class="form-group">
						<label class="control-label col-xs-3">Set Dokumen Kegiatan</label>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-3">Nama Dokumen</label>
						<label class="control-label col-xs-3">Mandatory</label>
					</div>
						<?php foreach ($dokumen_kegiatan as $dk){ ?>
							<div class="form-group">
								<label class="control-label col-xs-1"></label>
								<div class="col-xs-9">
									<div class="col-xs-5"><input class="kegiatan<?=$dk->ID;?>" type="checkbox" id="kegiatan<?=$dk->ID?>required" value='<?=$dk->ID?>' name="doc_kegiatan[]">&nbsp;&nbsp;<?=$dk->Nama?></div>
									<div class="col-xs-4"><input class="kegiatan<?=$dk->ID;?>" type="checkbox" id="kegiatan<?=$dk->ID?>mandatory" value='1' name="kegiatan<?=$dk->ID?>"></div>
								</div>
							</div>
						<?php }	?>
					<div class="form-group">
						<label class="control-label col-xs-3">Status</label>
						<div class="col-md-9">
							<div class="mt-radio-inline">
								<label class="mt-radio">
									<input type="radio" name="opt_status" id="opt_status" value="1" /> Aktif
									<span></span>
								</label>
								<label class="mt-radio">
									<input type="radio" name="opt_status" id="opt_status" value="0" /> Tidak Aktif
									<span></span>
								</label>
							</div>
						</div>
					</div>
				</div> <!-- END form-body -->
			</form> <!-- END FORM-->
		</div>
		<div class="modal-footer">
			<button type="submit" id="simpan" class="btn submit btn-primary"> Simpan </button>
			<button type="button" data-dismiss="modal" id="batal" name="batal" class="btn btn-default"> Batal </button>
		</div>
	</div>
</div>
</div>
<script src="<?php echo base_url(); ?>assets/custom/scripts/master/jenis_kegiatan.js?_dt=201606211658" type="text/javascript"></script>