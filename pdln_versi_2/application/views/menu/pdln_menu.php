<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo base_url(); ?>">
                <img src="<?php echo base_url(); ?>assets/psd/logo.png" alt="logo" class="logo-default" /> </a>
            <div class="menu-toggler sidebar-toggler">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE ACTIONS -->
        <!-- DOC: Remove "hide" class to enable the page header actions -->
        <!-- END PAGE ACTIONS -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <!-- BEGIN HEADER SEARCH BOX -->
            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
            <!-- <form class="search-form" action="page_general_search_2.html" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control input-sm" placeholder="Search..." name="query">
                    <span class="input-group-btn">
                        <a href="javascript:;" class="btn submit">
                            <i class="icon-magnifier"></i>
                        </a>
                    </span>
                </div>
            </form> -->
            <!-- END HEADER SEARCH BOX -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <span class="username username-hide-on-mobile"> Hi, <?php echo $this->session->userdata('name');?> </span>
                            <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                            <img alt="" class="img-circle" src="<?php echo base_url(); ?>assets/psd/no-avatar.png" /> </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="javascript:;" id="user_profile"><i class="icon-user"></i> Profil </a>
                            </li>
							<li>
                                <a href="javascript:;" id="change_password_user"><i class="icon-lock"></i> Change Password </a>
                            </li>
                            <li class="divider"> </li>
                            <li><a href="<?php echo base_url(); ?>auth/logout"><i class="icon-key"></i> Log Out </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->

<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>" class="nav-link">
                        <i class="icon-home"></i>
                        <span class="title">Dashboard</span>
                        <span class="selected"></span>
                    </a>
                </li>
				<?php if($this->ion_auth->is_parent_menu_allowed(array(25,26,27,40))) {?>
                <li class="heading">
                    <h3 class="uppercase">Layanan</h3>
                </li>
						<?php if($this->ion_auth->is_allowed(25,'view')) {?>
                    	<li class="nav-item  ">
                            <a href="<?php echo base_url(); ?>layanan/baru/add" class="nav-link ">
                            	<i class="fa fa-sticky-note-o"> </i>
                                <span class="title"> Baru </span>
                            </a>
                        </li>
                        <?php }?>


						<?php if($this->ion_auth->is_allowed(26,'view')) {?>
						<li class="nav-item  ">
                            <a href="<?php echo base_url(); ?>layanan/perpanjangan" class="nav-link ">
                            	<i class="fa fa-edit"> </i>
                                <span class="title"> Perpanjangan / Ralat </span>
                            </a>
                        </li>
						<?php }?>
						<?php if($this->ion_auth->is_allowed(27,'view')) { ?>
                        <li class="nav-item  ">
                            <a href="<?php echo base_url(); ?>layanan/pembatalan" class="nav-link ">
                            	<i class="fa fa-remove"> </i>
                                <span class="title"> Pembatalan </span>
                            </a>
                        </li>
						<?php }?>
				<?php }?>
                <?php if($this->ion_auth->is_parent_menu_allowed(array(25)))
                {?>
                <li class="heading">
                    <h3 class="uppercase">Kotak Surat</h3>
                </li>
                        <?php if($this->ion_auth->is_allowed(29,'view')){?>
                        <li class="nav-item  ">
                            <a href="<?php echo base_url(); ?>kotak_surat/approval/" class="nav-link ">
                                <i class="fa fa-share"> </i>
                                <span class="title"> Surat Masuk </span>
                                <span id="bullet_task" class="badge badge-warning"></span>
                            </a>
                        </li>
                        <?php }?>
                        <?php if($this->ion_auth->is_allowed(30,'view')){?>
                        <li class="nav-item  ">
                                <a href="<?php echo base_url(); ?>kotak_surat/approval/progress" class="nav-link ">
                                    <i class="fa fa-hourglass-half"> </i>
                                    <span class="title">Dalam Proses</span>
                                    <span id="bullet_progress"  class="badge badge-info"></span>
                                </a>
                        </li>
                        <?php }?>
                        <?php if($this->ion_auth->is_allowed(42,'view')){?>
                        <li class="nav-item  ">
                                <a href="<?php echo base_url(); ?>kotak_surat/approval/retur" class="nav-link ">
                                    <i class="fa fa-line-chart"></i>
                                    <span class="title">Dikembalikan</span>
                                    <span id="bullet_kembali"  class="badge badge-danger"></span>
                                </a>
                        </li>
                        <?php }?>
                        <?php if($this->ion_auth->is_allowed(43,'view')){?>
                        <li class="nav-item  ">
                                <a href="<?php echo base_url(); ?>kotak_surat/approval/arsip" class="nav-link ">
                                    <i class="fa fa-file-archive-o"></i>
                                    <span class="title">Arsip</span>
                                    <span id="bullet_arsip"  class="badge badge-succes"></span>
                                </a>
                        </li>
                        <?php }?>
                        <?php if($this->ion_auth->is_allowed(31,'view')){?>
                        <li class="nav-item  ">
                                <a href="<?php echo base_url(); ?>kotak_surat/approval/done" class="nav-link ">
                                    <i class="fa fa-check-square-o"> </i>
                                    <span class="title">Disetujui</span>
                                    <span id="bullet_done"  class="badge badge-success"></span>
                                </a>
                        </li>
                        <?php }?>
                        
                <?php }?>
                <?php if($this->ion_auth->is_parent_menu_allowed(array(28,41)))
                {?>
                <li class="heading">
                    <h3 class="uppercase">Laporan</h3>
                </li>


                        <?php if($this->ion_auth->is_allowed(40,'view')) {?>
                        <li class="nav-item  ">
                            <a href="<?php echo base_url(); ?>layanan/realisasi" class="nav-link ">
                                <i class="fa fa-check-circle-o"> </i>
                                <span class="title"> Laporan Penugasan </span>
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a href="<?php echo base_url(); ?>laporan/personal" class="nav-link ">
                                <i class="fa fa-user"></i>
                                <span class="title">Rekap Persetujuan</span>
                            </a>
                        </li>
                        <?php }?>
                <?php }?>


				<?php if($this->ion_auth->is_parent_menu_allowed(array(27,41))) {?>
                <li class="heading">
                    <h3 class="uppercase">Manajemen</h3>
                </li>
					<?php if($this->ion_auth->is_allowed(27,'view')) {?>
                    <!-- <li class ="nav-item  ">
                        <a href="<?php //echo base_url(); ?>master/kegiatan/jenis_kegiatan" class="nav-link ">
                            <span class="title"> Master Jenis Kegiatan </span>
                        </a>
                    </li> -->
                    <li class ="nav-item  ">
                        <a href="<?php echo base_url(); ?>master/kegiatan" class="nav-link ">
                            <span class="title"> Master Kegiatan </span>
                        </a>
                    </li>

                    <li class ="nav-item  ">
                        <a href="<?php echo base_url(); ?>master/kegiatan/kegiatan_request" class="nav-link ">
                            <span class="title"> Request Kegiatan </span>
                            <span id="is_req"  class="badge badge-success"></span>

                        </a>
                    </li>

					<?php }?>
					<?php if($this->ion_auth->is_allowed(41,'view')) {?>
					<li class="nav-item  ">
						<a href="<?php echo base_url(); ?>manajemen/pemohon" class="nav-link ">
							<i class="icon-user"></i>
							<span class="title">Daftar Pemohon</span>
						</a>
					</li>
					<?php }?>
				<?php }?>
                <?php if($this->ion_auth->is_parent_menu_allowed(array(28,41))) {?>


                <li class="heading">
                    <h3 class="uppercase">Informasi</h3>
                </li>
                <li class="nav-item  ">
                    <a href="<?php echo base_url(); ?>page/helpdesk" class="nav-link ">
                        <i class="fa fa-headphones" aria-hidden="true"></i>
                        <span class="title" >Help desk</span>
                        <span id="helpdesk"  class="badge badge-success"></span>

                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="<?php echo base_url(); ?>page/manual" class="nav-link ">
                        <i class="icon-notebook"></i>
                        <span class="title">User Manual</span>
                    </a>
                </li>
                <!-- <li class="nav-item  ">
                    <a href="<?php // echo base_url(); ?>page/faq" class="nav-link ">
                        <i class="icon-info"></i>
                        <span class="title">FAQ</span>
                    </a>
                </li> -->
                <li class="nav-item  ">
                    <a href="<?php echo base_url(); ?>page/tentang" class="nav-link ">
                        <i class="icon-globe"></i>
                        <span class="title">Tentang PDLN</span>
                    </a>
                </li>
                <?php }?>


            </ul>
        </div>
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <div class="page-title">
                <h1><?php echo $title_page; ?></h1>
            </div>
            <!-- BEGIN PAGE BREADCRUMB -->
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <a href="<?php echo base_url(); ?>">Dashboard</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span class="active"><?php echo $breadcrumb; ?></span>
                </li>
            </ul>
            <!-- END PAGE BREADCRUMB -->

			<div class="modal fade" id="modal_change_password" user="dialog" aria-labelledby="modal_change_password" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<div class="modal-title">
								<h3 class="font-green uppercase bold"> <i class="icon-plus"></i>
									Ganti Password
								</h3>
							</div>
						</div>
						<div class="modal-body form">
							<!-- BEGIN FORM-->
								<div class="form-body">
									<form method="post" action="javascript:;" id="form_change_password" name="form_change_password" class="form-horizontal" user="form">
										<br/><br/>
										<input type="hidden" name="id_user"  id="id_user" value="<?php echo $this->session->userdata('user_id');?>" class="form-control">
										<div class="form-group last password-strength">
											<label class="control-label col-xs-4">Password Lama<strong> <span class="font-red">(*)</span></strong></label>
											<div class="col-xs-5">
												<input name="old_password" id="old_password" class="form-control" type="password" />
												<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group last password-strength">
											<label class="control-label col-xs-4">Password Baru<strong> <span class="font-red">(*)</span></strong></label>
											<div class="col-xs-5">
												<input name="new_password" id="new_password" class="form-control" type="password" />
												<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group last password-strength">
											<label class="control-label col-xs-4">Konfirmasi Password Baru<strong> <span class="font-red">(*)</span></strong></label>
											<div class="col-xs-5">
												<input name="confirm_new_password" id="confirm_new_password" class="form-control" type="password" />
												<span class="help-block"></span>
											</div>
										</div><br/><br/>
									</form>
								</div> <!-- END form-body -->
						</div><!-- END modal body form new user -->
						<div class="modal-footer">
							<button type="submit" id="change_password_submit" class="btn submit btn-primary"> Ganti Password </button>
							<button type="button" data-dismiss="modal" class="btn btn-default"> Batal </button>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="modal_profil_pengguna" region="dialog" aria-labelledby="modal_profil_pengguna" aria-hidden="true">
			<div class="modal-dialog modal-lg" style="width:400px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>

					</div>
					<div class="modal-body form">
						<!-- BEGIN FORM-->
                                <!-- PORTLET MAIN -->
                                <div class="portlet light profile-sidebar-portlet bordered">
                                    <!-- SIDEBAR USERPIC -->
                                    <div class="profile-userpic">
                                        <img src="<?php echo base_url(); ?>assets/psd/no_avatar_green.png" class="img-responsive" alt=""> </div>
                                    <!-- END SIDEBAR USERPIC -->
                                    <!-- SIDEBAR USER TITLE -->
                                    <div class="profile-usertitle">
                                        <div class="profile-usertitle-name"> <?php echo $this->session->user_detail->nama; ?> </div>
                                        <div class="profile-usertitle-job"> <?php echo $this->session->user_detail->instansi; ?> </div>
                                    </div>
                                    <!-- END SIDEBAR USER TITLE -->

                                </div>
                                <!-- END PORTLET MAIN -->
                                <!-- PORTLET MAIN -->
                                <div class="portlet light bordered">
                                    <div>
                                        <h4 class="profile-desc-title">Detail</h4>
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-user"></i> <a><?php echo $this->session->username; ?></a>
                                        </div>
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-institution"></i> <a><?php echo $this->session->user_detail->unit_kerja; ?></a>
										</div>
                                        <div class="margin-top-20 profile-desc-link">
                                            <i class="fa fa-envelope"></i> <a><?php echo $this->session->user_detail->email; ?></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- END PORTLET MAIN -->

					</div><!-- END modal body form new user -->
				</div>
			</div>
		</div>

<script>
		$('#user_profile').on('click',function(){
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
			$('#modal_profil_pengguna').modal('show');
        });

		$('#change_password_user').on('click',function(){
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            $('#modal_change_password').modal('show');
        });

		var initialized = false;
		var newpsw = $("#new_password");
		newpsw.keydown(function () {
				if (initialized === false) {
					// set base options
					newpsw.pwstrength({
						raisePower: 1.4,
						minChar: 8,
						verdicts: ["Weak", "Normal", "Medium", "Strong", "Very Strong"],
						scores: [17, 26, 40, 50, 60]
					});

					// add your own rule to calculate the password strength
					newpsw.pwstrength("addRule", "demoRule", function (options, word, score) {
						return word.match(/[a-z].[0-9]/) && score;
					}, 10, true);

					// set as initialized
					initialized = true;
				}
			});

		$('#batal').on('click',function(){
            $('#form_change_password').validate().resetForm();
        });

		$('#form_change_password').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                new_password : {
                    required : true,
					minlength: 8,
					pwcheck: true,
					ContainsAtLeastOneCapitalLetter:true
                }
            },
            messages :{
                new_password : {
                    required: 'Silahkan isi password anda',
					minlength: 'Password Minimal 8 Karakter',
					pwcheck:'Pastikan password anda terdiri dari kombinasi angka,huruf besar,huruf kecil dan karakter spesial (mis: #,$,&)'
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                error.fadeTo(3000, 500).slideUp(500, function(){});
                    App.scrollTo(error, -200);
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

        });

		jQuery.validator.addMethod("pwcheck", function(value) {
		   return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
			   && /[a-z]/.test(value) // has a lowercase letter
			   && /\d/.test(value) // has a digit
		});


		$('#modal_change_password').on('click', '#change_password_submit', function (e) {
            e.preventDefault();
            $('#change_password_submit').prop('disabled',true);
			id_user = $('#UserID').val();

            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });

				$.ajax({
					url : BASE_URL+"auth/auth/change_user_password/",
					type: "POST",
					dataType: 'JSON',
					data: $('#form_change_password').serialize(),
					success: function(data)
					{
						if(data.status === true){
							$('#modal_change_password').modal('hide');
							window.setTimeout(function() {
								App.unblockUI();
									bootbox.alert({
										message : '<span class="font-yellow"> Ganti Password.</span> <br />'+
												  ' Password berhasil di ganti, Silahkan login ulang!',
										title   : '<span class="font-red bold"> <strong> <i class="fa fa-success"> </i> Berhasil!! </strong><span>'
								});
							}, 2000);
							location.href = BASE_URL+"auth/auth/logout";
						}
						else
						{
							if((data.msg) && (data.status === false)){
								window.setTimeout(function() {
									App.unblockUI();
										bootbox.alert({
											message : '<span class="font-yellow"> Ganti Password.</span> <br />'+
													  data.msg,
											title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
									});
								}, 1000);

							}else{
								App.unblockUI();
								for (var i = 0; i < (data.inputerror).length; i++)
								{
									$('[name="'+data.inputerror[i]+'"]')
										.parent().parent()
										.addClass('has-error');
									$('[name="'+data.inputerror[i]+'"]')
										.next()
										.text(data.error_string[i]);
								}
							}
						}
						$('#change_password_submit').prop('disabled',false);
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						App.unblockUI();
						bootbox.alert({
							message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
									  ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator Aplikasi. </strong>',
							title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'
						});
						$('#change_password_submit').prop('disabled',false);
					}
				});

        });
        $('#user_profile').on('click',function(){
            App.blockUI({
                boxed: true,
                message: 'Sedang di proses....'
            });
            App.unblockUI();
            $('#modal_profil_pengguna').modal('show');
        });

        $.ajax({
            url : BASE_URL+"badge/jumlah/task",
            type: "POST",
            dataType: 'JSON',
            success: function(data)
            {
                document.getElementById("bullet_task").innerHTML = data.jml;
            },
        });

        $.ajax({
            url : BASE_URL+"badge/jumlah/progress",
            type: "POST",
            dataType: 'JSON',
            success: function(data)
            {
                document.getElementById("bullet_progress").innerHTML = data.jml;
            },
        });
        $.ajax({
            url : BASE_URL+"badge/jumlah/archive",
            type: "POST",
            dataType: 'JSON',
            success: function(data)
            {
                document.getElementById("bullet_arsip").innerHTML = data.jml;
            },
        });
        $.ajax({
            url : BASE_URL+"badge/jumlah/retur",
            type: "POST",
            dataType: 'JSON',
            success: function(data)
            {
                document.getElementById("bullet_kembali").innerHTML = data.jml;
            },
        });

        $.ajax({
            url : BASE_URL+"badge/jumlah/done",
            type: "POST",
            dataType: 'JSON',
            success: function(data)
            {
                document.getElementById("bullet_done").innerHTML = data.jml;
           },
        });
        $.ajax({
            url : BASE_URL+"badge/jumlah/request",
            type: "POST",
            dataType: 'JSON',
            success: function(data)
            {
                document.getElementById("is_req").innerHTML = data.jml;
            },
        });
        $.ajax({
            url : BASE_URL+"badge/jumlah/help",
            type: "POST",
             dataType: 'JSON',
            success: function(data)
            {
                document.getElementById("helpdesk").innerHTML = data.jml;
            },
        });
</script>