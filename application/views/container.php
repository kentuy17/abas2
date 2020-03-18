
<!DOCTYPE html>
<html>
<head>
	<title><?php 
		if(ENVIRONMENT=="development"){
		 echo "[DEV]";
		}elseif(ENVIRONMENT=="testing"){
		 echo "[STG]";
		}
	?>AVega Business Automation System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
	<link rel="icon" href="<?php echo LINK."assets/images/av.ico"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap/css/bootstrap.min.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/style.css"; ?>" />

	<link rel="stylesheet" href="<?php echo LINK; ?>assets/bootstrap-table-master/src/bootstrap-table.css">
	<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
	<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>

	<script src="<?php echo LINK.'assets/bootstrap/js/bootstrap.min.js'; ?>"></script>
	<script src="<?php echo LINK; ?>assets/bootstrap-table-master/src/bootstrap-table.js"></script>
	<script src="<?php echo LINK; ?>assets/toastr/toastr.js"></script>
	<script src="<?php echo LINK; ?>assets/stickUp.min.js"></script>

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-80766509-1', 'auto');
		ga('send', 'pageview');

	</script>

<style>

</style>
</head>
<body>
<nav class="navbar navbar-inverse">
	<div class="container-fluid">
        <div class="navbar-header">
			<?php if(isset($_SESSION['abas_login'])): ?>
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			<?php endif; ?>
			<a class="navbar-brand" href="<?php echo HTTP_PATH; ?>">
				<span>
					<img src="<?php echo LINK.'assets/images/AvegaLogo.jpg'; ?>" width="35px" align="absmiddle" />
					AVega Business Automation System
				</span>
				<?php if(ENVIRONMENT=="development" || ENVIRONMENT=="testing") { echo "<span style='font-size:18px; color:FF0000; font-weight:bold;'>[DEVELOPMENT SERVER]</span>"; } ?>
			</a>
		</div>
        <div class="collapse navbar-collapse" id="myNavbar">
			<?php if(isset($_SESSION['abas_login'])): ?>
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a onClick="javascript:showNotifications();" href="#"><span class="glyphicon glyphicon-envelope"></span> Notifications</a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-th-large"></span> Subsystems</a>
								<ul class="dropdown-menu">
									<li role="separator" class="divider"></li>
									<?php if($this->Abas->checkPermissions("employee_profile|view", false)): ?>
										<li><a href="<?php echo HTTP_PATH."hr"; ?>"><span class="glyphicon glyphicon-user"></span> Human Resources</a></li>
									<?php endif; ?>
									<?php if($this->Abas->checkPermissions("payroll|view", false)): ?>
										<li><a href="<?php echo HTTP_PATH."payroll"; ?>"><span class="glyphicon glyphicon-transfer"></span> Payroll</a></li>
									<?php endif; ?>
									<?php if($this->Abas->checkPermissions("purchasing|view_requests", false)): ?>
										<li><a href="<?php echo HTTP_PATH."purchasing"; ?>"><span class="glyphicon glyphicon-gift"></span> Purchasing</a></li>
									<?php endif; ?>
									<?php if($this->Abas->checkPermissions("inventory|view", false)): ?>
										<li><a href="<?php echo HTTP_PATH."inventory"; ?>"><span class="glyphicon glyphicon-download-alt"></span> Inventory</a></li>
									<?php endif; ?>
									<?php if($this->Abas->checkPermissions("accounting|view_vouchers", false)): ?>
										<li><a href="<?php echo HTTP_PATH."accounting"; ?>"><span class="glyphicon glyphicon-briefcase"></span> Accounting</a></li>
									<?php endif; ?>
									<?php if($this->Abas->checkPermissions("users|add", false)): ?>
										<li><a href="<?php echo HTTP_PATH."users"; ?>"><span class="glyphicon glyphicon-lock"></span> Users</a></li>
									<?php endif; ?>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account</a>
								<ul class="dropdown-menu">
									<li role="separator" class="divider"></li>
									<li><a href="<?php echo HTTP_PATH."home/logout"; ?>"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="<?php echo HTTP_PATH.'home/account'; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="My Account" style="cursor:pointer;"><span class="glyphicon glyphicon-user"></span> Account Details</a></li>
								</ul>
							</li>
						</ul>
					<?php endif; ?>
		</div>
	</div>
</nav>


<?php
###################
###################
###             ###
###   content   ###
###    here!    ###
###             ###
###################
###################
	if(!include($viewfile)) echo "Viewfile not found, that makes me sad &#9785;";
###################
###################
###             ###
###   content   ###
###    here!    ###
###             ###
###################
###################
?>

<div id="modalDialog" class="modal fade">
	<div class="modal-dialog" style="width:800px">
		<div class="modal-content">
			<p class="loading-text">Loading Content...</p>
		</div>
	</div>
</div>
<!--div id="modalDialogBig" class="modal fade" style="margin-top:-20px">
	<div class="modal-dialog" style="width:900px">
		<div class="modal-content">
			<p class="loading-text">Loading Content...</p>
		</div>
	</div>
</div-->

</body>
<script>
<?php $this->Abas->display_messages(); ?>

</script>
<script>
// resets the modal upon close so there is no need to write new markup for each modal dialog
$('body').on('hidden.bs.modal', '.modal', function () {
	$(this).removeData('bs.modal');
	$(".modal-content").html("<p class='loading-text'>Loading Content...</p>");
});

function showNotifications() {
	// toastr['info']("This is a notification");
	<?php if(isset($_SESSION['abas_login'])) echo $this->Abas->getNotifications(); ?>
}
<?php if(ENVIRONMENT=="development") { echo "toastr['error']('This is the unresponsive container! <pre>container-responsive.php</pre> should be used instead.');"; } ?>
$(function() {
	var	$window = $(window),
	$body = $('body');

	// Disable animations/transitions until the page has loaded.
	$body.addClass('is-loading');

	$window.on('load', function() {
		window.setTimeout(function() {
			$body.removeClass('is-loading');
		}, 0);
	});
});
</script>
</html>
