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
		<link rel="stylesheet" href="<?php echo LINK."assets/normalize.css"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap/css/bootstrap.min.css"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/style.css"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap-table-master/src/bootstrap-table.css"; ?>">
		<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>

		<script src="<?php echo LINK.'assets/bootstrap/js/bootstrap.min.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/bootstrap-table-master/src/bootstrap-table.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/toastr/toastr.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/stickUp.min.js'; ?>"></script>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-80766509-1', 'auto');
			ga('send', 'pageview');

		</script>
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
							<span class='hidden-sm hidden-xs'>AVega Business Automation System</span>
							<span class='hidden-lg hidden-md visible-sm-* visible-xs-*'>ABAS</span>
						</span>
					</a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<?php if(isset($_SESSION['abas_login'])): ?>
						<ul class="nav navbar-nav navbar-right">
							<li><a href="<?php echo HTTP_PATH."home/logout"; ?>"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
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
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<p class="loading-text">Loading Content...</p>
				</div>
			</div>
		</div>

        <div id="modalDialogNorm" class="modal fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <p class="loading-text">Loading...</p>
                </div>
            </div>
        </div>
		<?php if(isset($_SESSION['abas_login'])): ?>
		<div class="chatroom panel panel-success">
			<div id="chat-head" class="panel-heading">
				<span>AVega Channel</span>
			</div>
			<div id="chat-body" class="panel-body">
				<div class="chatbox col-xs-12">

				</div>
				<div class="chat-input col-xs-12">
					<textarea class="form-control pull-left" rows="1" id="chat-text" name="chat-text"></textarea> <a id="chat-send" class="btn btn-warning pull-left">&rarr;</a>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</body>
	<script>
		<?php $this->Abas->display_messages(); ?>

		// resets the modal upon close so there is no need to write new markup for each modal dialog
		$('body').on('hidden.bs.modal', '.modal', function () {
			$(this).removeData('bs.modal');
			$(".modal-content").html("<p class='loading-text'>Loading Content...</p>");
		});
		function showNotifications() {
			$.ajax({
				url: "<?php echo HTTP_PATH; ?>home/ajaxNotifs/1",
				cache: false,
				dataType: 'json',
				success: function(html){
					for (var key in html) {
						if (html.hasOwnProperty(key)) {
							toastr[html[key].type](html[key].content, html[key].title);
						}
					}
				},
			});
		}

		function scrollChatToBottom() {
			$('.chatbox').scrollTop($('.chatbox')[0].scrollHeight);
		}
		function refreshChat() {
			$.ajax({
				url: "<?php echo HTTP_PATH; ?>assets/chat.txt",
				cache: false,
				success: function(html){
					$(".chatbox").html(html);
					scrollChatToBottom();
				},
			});
		}
		$("#chat-send").click(function(){
			var clientmsg = $("#chat-text").val();
			$.post("<?php echo HTTP_PATH."home/chat"; ?>", {text: clientmsg});
			$("#chat-text").val('');
			$("#chat-text").focus();
			refreshChat();
			scrollChatToBottom();
			return false;
		});

		$("#chat-head").click(function() {
			$("#chat-body").toggle( "slow", function() { refreshChat(); });
			scrollChatToBottom();
		});

		function refreshNotifs() {
			$.ajax({
				url: "<?php echo HTTP_PATH; ?>home/ajaxNotifs",
				cache: false,
				dataType: 'json',
				success: function(html){
					for (var key in html) {
						if (html.hasOwnProperty(key)) {
							toastr[html[key].type](html[key].content, html[key].title);
						}
					}
				},
			});
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

			<?php if(isset($_SESSION['abas_login'])): ?>
			refreshChat();
			refreshNotifs();
			setInterval (refreshNotifs, 120000);
			<?php endif; ?>

		});
	</script>
</html>
