<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
                    <i class=" icon-layers font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> Mass Upload Data Peserta</span>
                </div>
			</div>
			<div class="portlet-body">
            </div>
            <div class="modal-body form">
				<!-- BEGIN FORM-->
                <?php echo form_open_multipart('layanan/mass_upload/process_peserta',' id="form_uploadr" class="form-horizontal"');?>
				<!-- form action="layanan/mass_upload/process" id="form_uploadr" class="form-horizontal" enctype="multipart/form-data" method="post" //-->
					<div class="form-body">
            			<div class="form-group">
							<label class="control-label col-xs-3">Dokumen Data Peserta<strong> <span class="font-red">(* csv)</span></strong></label>
							<div class="col-md-3">
                                <div class="fileinput fileinput-new" id="Path" data-provides="fileinput">
                                    <div class="input-group input-large">
                                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                            <span class="fileinput-filename" id="fileinput-filename"> </span>
                                        </div>
                                        <span class="input-group-addon btn default btn-file">
                                            <span class="fileinput-new"> Select file </span>
                                            <span class="fileinput-exists"> Change </span>
                                            <input type="file" name="fileUpload" id="fileUpload"> </span>
                                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                    </div>
                                </div>
                           	</div>
						</div>
                    </div> <!-- END form-body -->
                    <div class="modal-footer">
                        <button type="submit" id="simpan" class="btn submit btn-primary"> Upload </button>
                    </div>
				</form> <!-- END FORM-->
			</div><!-- END modal body form mass upload -->
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>