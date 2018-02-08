
<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
Version: 4.7.1
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>PDLN | <?php echo $title; ?> </title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="KTLN Perjalanan Dinas Luar Negeri" name="description" />
        <meta content="Biro KTLN" name="author" />
        <script type="text/javascript">
            var BASE_URL = '<?php echo base_url(); ?>';
        </script>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo base_url(); ?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url(); ?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo base_url(); ?>assets/pages/css/login.new.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/psd/logo-sesneg.png" />
    <!-- END HEAD -->
    <body class=" login" >
        <!-- BEGIN LOGIN -->
        <div class="mid_c" style="background: url(<?php echo base_url(); ?>assets/pages/img/login/bg6.jpg) no-repeat center center fixed;
  ">
        <div class="content" style="display: table-cell;vertical-align: middle;">
			<!-- BEGIN LOGIN FORM -->
            <form action="javascript:;" class="login-form f_login" method="post">
			<!-- BEGIN LOGO -->
			<div class="logo">
					<img src="<?php echo base_url(); ?>assets/pages/img/login/new-logo.png" alt="" style="margin-top: -50px;"/>
            </div>
			<!-- END LOGO -->
			<!--div class="title-apps" style="font-family: proxima_nova_rgbold;font-size: 28pt;text-align: center;width: auto;color: #1ba1e2;margin: 0 0 0 0;line-height: 1;">
				SIKTLN
			</div//-->
			<div class="desc-apps" style="margin: 0 0 0 0;padding-bottom: 10px;font-family: sans-serif;font-size: 13pt;text-align: center;width: auto;color: #fff;">
				SISTEM INFORMASI<br>PERJALANAN DINAS LUAR NEGERI
			</div>
            <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span>Silahkan lengkapi login anda!</span>
                </div>
				<div class="alert alert-warning display-hide">
                    <button class="close" data-close="alert"></button>
                    <span></span>
                </div>
				<div class="alert alert-success display-hide">
                    <button class="close" data-close="alert"></button>
                    <span></span>
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="text" id="identity" autocomplete="off" placeholder="Username" name="identity" />
				</div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="password" id="password" autocomplete="off" placeholder="Password" name="password" />
				</div>
				<div class="form-group">
                <input type="hidden" name="redirect" value="<?php echo $redirect;?>" id="redirect">
                <?php if(isset($captcha['image'])):?>
					<label for="captcha"><?php echo $captcha['image']; ?></label>
					<br>
					<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" name="userCaptcha" name="userCaptcha" placeholder="Masukkan Kode Keamanan" value="<?php if(!empty($userCaptcha)){ echo $userCaptcha;} ?>" />
					<span class="required-server"><?php echo form_error('userCaptcha','<p style="color:#F83A18">','</p>'); ?></span>
                <?php endif;?>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"></label>
                    <button type="submit" class="btn green uppercase" style="background-color: #2d8835;width: 100%;">Login</button>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"></label>
                    <div>Copyright &copy; 2017 Kemensetneg</div>
                </div>
            </form>
            <!-- END LOGIN FORM -->
        </div>
        </div>
        <!--div class="copyright"> </div//-->
        <!--[if lt IE 9]>
        <script src="<?php echo base_url(); ?>assets/global/plugins/respond.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/excanvas.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/ie8.fix.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?php echo base_url(); ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url(); ?>assets/global/scripts/app.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
         <script src="<?php echo base_url(); ?>assets/custom/scripts/login.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    <!-- Google Code for Universal Analytics -->
<!-- End -->
</body>
</html>