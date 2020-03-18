<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>AVega Business Automation System</title>

    
    <link rel="stylesheet" href="<?php echo LINK."assets/normalize.css"; ?>">
		<!--link rel="stylesheet" href="<?php echo LINK ?>assets/gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css"-->
		<link rel="stylesheet" href="<?php echo LINK ?>assets/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo LINK ?>assets/gentelella-master/build/css/custom.min.css">
		<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap-table-master/src/bootstrap-table.css"; ?>">
		<link rel="stylesheet" href="<?php echo LINK."assets/style.css"; ?>" />   
	    <link href="<?php echo LINK ?>assets/gentelella-master/build/css/custom.min.css" rel="stylesheet">
		<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/jquery/jQuery.print.js' ?>"></script>
		<script src="<?php echo LINK.'assets/jquery/jquery.printPage.js' ?>"></script>
		<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/bootstrap/js/bootstrap.min.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/bootstrap-table-master/src/bootstrap-table.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/toastr/toastr.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/stickUp.min.js'; ?>"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    
    
    <div id="modalDialog" class="modal fade">
        <div class="modal-dialog modal-lg">
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
    
    <div id="processing" style="display:none; color:#009900">
    	<div class="modal">Processing, please wait...</div>
    </div>
    
    <script>
		<?php $this->Abas->display_messages(); ?>
	</script>
     <script>
		// resets the modal upon close so there is no need to write new markup for each modal dialog
		$(document).ready(function() {
		
				

			$('body').on('hidden.bs.modal', '.modal', function () {
				
				$(this).removeData('bs.modal');						
				 $(this).removeData();
				$(".modal-content").html("<p class='loading-text'>Loading ...</p>");
				
				//window.reload();
			});

			//$(document).on("hidden.bs.modal", function (e) {
			    
			  //  $(e.target).removeData("bs.modal").find(".modal-content").empty();
			//});

			if (/Mobi/.test(navigator.userAgent)) {
				// mobile!
				//alert('im on mobile');
				document.getElementById("normalWidth").style.width = "99%";
			}
			
			
			$('#datatable-responsive').DataTable();
			$('#datatable-responsive1').DataTable();
			$('#datatable-responsive2').DataTable();
			$('#datatable-responsive3').DataTable();
			$('#datatable-responsive4').DataTable();

		});
		
		

				
		function showNotifications() {
			// toastr['info']("This is a notification");
			<?php if(isset($_SESSION['abas_login'])) echo $this->Abas->getNotifications(); ?>
		}
		
		$(function() {
			//var	$window = $(window),
			//$body = $('body');
		
			// Disable animations/transitions until the page has loaded.
			//$body.addClass('is-loading');
			
			/*
			$window.on('load', function() {
				window.setTimeout(function() {
					$body.removeClass('is-loading');
				}, 0);
			});
			*/
		});
		
		
	</script>
    
    <style type="text/css">
    
    .blink_me {
	  animation: blinker 2s linear infinite;
	}
	
	@keyframes blinker {  
	  90% { opacity: 0; }
	}
    
    </style>