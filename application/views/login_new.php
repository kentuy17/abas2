<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
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
<html lang="en" >
    <!-- begin::Head -->
    <head><!--begin::Base Path (base relative path for assets of this page) -->
        <meta charset="utf-8"/>

        <title>Metronic | Login Page v3</title>
        <meta name="description" content="Login page example">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!--begin::Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">        <!--end::Fonts -->

        
                    <!--begin::Page Custom Styles(used by this page) -->
                             <link href="<?=LINK?>assets/metronic/css/demo1/pages/login/login-3.css" rel="stylesheet" type="text/css" />
                        <!--end::Page Custom Styles -->
        
        <!--begin:: Global Mandatory Vendors -->
<link href="<?=LINK?>assets/metronic/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />
<!--end:: Global Mandatory Vendors -->



<!--begin::Global Theme Styles(used by all pages) -->
                    
                    <link href="<?=LINK?>assets/metronic/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />
                <!--end::Global Theme Styles -->

        <!--begin::Layout Skins(used by all pages) -->
        
<link href="<?=LINK?>assets/metronic/css/demo1/skins/header/base/light.css" rel="stylesheet" type="text/css" />
<link href="<?=LINK?>assets/metronic/css/demo1/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
<link href="<?=LINK?>assets/metronic/css/demo1/skins/brand/dark.css" rel="stylesheet" type="text/css" />
<link href="<?=LINK?>assets/metronic/css/demo1/skins/aside/dark.css" rel="stylesheet" type="text/css" />        <!--end::Layout Skins -->

        <link rel="shortcut icon" href="./assets/media/logos/favicon.ico" />
    </head>
    <!-- end::Head -->

    <!-- begin::Body -->
    <body  class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"  >

       
    	<!-- begin:: Page -->
	<div class="kt-grid kt-grid--ver kt-grid--root">
		<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
	<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(<?=LINK?>/assets/metronic/media/bg/bg-3.jpg);">
		<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
			<div class="kt-login__container">
				<div class="kt-login__logo">
					<a href="#">
						<img src="<?=LINK?>/assets/images/AvegaLogo.jpg">  	
					</a>
				</div>
				<div class="kt-login__signin">
					<div class="kt-login__head">
						<?php 
							$version = $this->Abas->readChangeLog();
							//echo "<br>ABAS ".$version['num'];
						?>
						<h3 class="kt-login__title">ABAS <?=$version['num']?></h3>
					</div>
					<form class="kt-form" action="">
						<div class="input-group">
							<input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
						</div>
						<div class="input-group">
							<input class="form-control" type="password" placeholder="Password" name="password">
						</div>
						<div class="row kt-login__extra">
							<div class="col">
								<label class="kt-checkbox">
									<input type="checkbox" name="remember"> Remember me
									<span></span>
								</label>
							</div>
							<div class="col kt-align-right">
								<a href="javascript:;" id="kt_login_forgot" class="kt-login__link">Forget Password ?</a>
							</div>
						</div>
						<div class="kt-login__actions">
							<button id="kt_login_signin_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign In</button>
						</div>
					</form>
				</div>
				<div class="kt-login__signup">
					<div class="kt-login__head">
						<h3 class="kt-login__title">Sign Up</h3>
						<div class="kt-login__desc">Enter your details to create your account:</div>
					</div>
					<form class="kt-form" action="">
						<div class="input-group">
							<input class="form-control" type="text" placeholder="Fullname" name="fullname">
						</div>
						<div class="input-group">
							<input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
						</div>
						<div class="input-group">
							<input class="form-control" type="password" placeholder="Password" name="password">
						</div>
						<div class="input-group">
							<input class="form-control" type="password" placeholder="Confirm Password" name="rpassword">
						</div>
						<div class="row kt-login__extra">
							<div class="col kt-align-left">
								<label class="kt-checkbox">
									<input type="checkbox" name="agree">I Agree the <a href="#" class="kt-link kt-login__link kt-font-bold">terms and conditions</a>.
									<span></span>
								</label>
								<span class="form-text text-muted"></span>
							</div>
						</div>
						<div class="kt-login__actions">
							<button id="kt_login_signup_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign Up</button>&nbsp;&nbsp;
							<button id="kt_login_signup_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
						</div>
					</form>
				</div>
				<div class="kt-login__forgot">
					<div class="kt-login__head">
						<h3 class="kt-login__title">Forgotten Password ?</h3>
						<div class="kt-login__desc">Enter your email to reset your password:</div>
					</div>
					<form class="kt-form" action="">
						<div class="input-group">
							<input class="form-control" type="text" placeholder="Email" name="email" id="kt_email" autocomplete="off">
						</div>
						<div class="kt-login__actions">
							<button id="kt_login_forgot_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Request</button>&nbsp;&nbsp;
							<button id="kt_login_forgot_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
						</div>
					</form>
				</div>
				<div class="kt-login__account">
					<span class="kt-login__account-msg">
						Don't have an account yet ?
					</span>
					&nbsp;&nbsp;
					<a href="javascript:;" id="kt_login_signup" class="kt-login__account-link">Sign Up!</a>
				</div>
			</div>	
		</div>
	</div>
</div>	
	</div>
	
<!-- end:: Page -->


        <!-- begin::Global Config(global config for global JS sciprts) -->
        <script>
            var KTAppOptions = {"colors":{"state":{"brand":"#5d78ff","dark":"#282a3c","light":"#ffffff","primary":"#5867dd","success":"#34bfa3","info":"#36a3f7","warning":"#ffb822","danger":"#fd3995"},"base":{"label":["#c5cbe3","#a1a8c3","#3d4465","#3e4466"],"shape":["#f0f3ff","#d9dffa","#afb4d4","#646c9a"]}}};
        </script>
        <!-- end::Global Config -->

    	<!--begin:: Global Mandatory Vendors -->
<script src="<?=LINK?>assets/metronic/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
<script src="<?=LINK?>assets/metronic/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
<script src="<?=LINK?>assets/metronic/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=LINK?>assets/metronic/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
<script src="<?=LINK?>assets/metronic/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
<script src="<?=LINK?>assets/metronic/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
<script src="<?=LINK?>assets/metronic/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
<script src="<?=LINK?>assets/metronic/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
<script src="<?=LINK?>assets/metronic/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>
<!--end:: Global Mandatory Vendors -->



<!--begin::Global Theme Bundle(used by all pages) -->
    	    	   
		    	   <script src="<?=LINK?>assets/metronic/js/demo1/scripts.bundle.js" type="text/javascript"></script>
				<!--end::Global Theme Bundle -->

        
                    <!--begin::Page Scripts(used by this page) -->
                            <script src="<?=LINK?>assets/metronic/js/demo1/pages/login/login-general.js" type="text/javascript"></script>
                        <!--end::Page Scripts -->
            </body>
    <!-- end::Body -->
</html>
