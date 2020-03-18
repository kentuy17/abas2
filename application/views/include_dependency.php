<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>AVega Business Automation System</title>

    
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap 
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>-->

  
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    
    <link href="<?php echo LINK ?>assets/gentelella-master/build/css/custom.min.css" rel="stylesheet">
    
     
    
    <!-- MASKE Style -->
    <link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
	<link rel="stylesheet" href="<?php //echo LINK."assets/bootstrap/css/bootstrap.min.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
	
	<link rel="stylesheet" href="<?php echo LINK."assets/style.css"; ?>" />
	
    <!-- jQuery -->
    <script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
    <script src="<?php echo LINK.'assets/jquery/jQuery.print.js' ?>"></script>
    <script src="<?php echo LINK.'assets/jquery/jquery.printPage.js' ?>"></script>
	<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>
    <script src="<?php echo LINK; ?>assets/toastr/toastr.js"></script>
	<script src="<?php echo LINK; ?>assets/stickUp.min.js"></script>
    
    
   
    
    <div id="modalDialog" class="modal fade">
        <div class="modal-dialog" style="width:800px">
            <div class="modal-content">
                <p class="loading-text">Loading...</p>
            </div>
        </div>
	</div>
    

    
    <div id="modalDialogNorm" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <p class="loading-text">Loading...</p>
            </div>
        </div>
	</div>
    
    <div id="modalDialogSemiWide" class="modal fade">
        <div class="modal-dialog" style="width:1000px">
            <div class="modal-content">
                <p class="loading-text">Loading...</p>
            </div>
        </div>
	</div>
    
    <div id="modalDialogWide" class="modal fade">
        <div class="modal-dialog" style="width:1150px">
            <div class="modal-content">
                <p class="loading-text">Loading...</p>
            </div>
        </div>
	</div>
    
     <script>
		// resets the modal upon close so there is no need to write new markup for each modal dialog
		$(document).ready(function() {
		
			$('body').on('hidden.bs.modal', '.modal', function () {
				$(this).removeData('bs.modal');
				$(".modal-content").html("<p class='loading-text'>Loading ...</p>");
			});

			if (/Mobi/.test(navigator.userAgent)) {
				// mobile!
				//alert('im on mobile');
				document.getElementById("normalWidth").style.width = "99%";
			}

		});
		
		
				
		function showNotifications() {
			// toastr['info']("This is a notification");
			<?php if(isset($_SESSION['abas_login'])) echo $this->Abas->getNotifications(); ?>
		}
		
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
    
    <style type="text/css">
    
    .blink_me {
	  animation: blinker 1s linear infinite;
	}
	
	@keyframes blinker {  
	  90% { opacity: 0; }
	}
    
    </style>