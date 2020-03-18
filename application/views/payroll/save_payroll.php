<div class = "panel panel-default" style="border:#999999 thin solid">
	<div class = "panel-heading" style=" background:#CCC; color:#000">
		<h3 class = "panel-title">
			<span>
				<strong>
					<h4>
						<img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle">   Avegabros Integrated Shipping Corp.
					</h4>
				</strong>
			</span>
		</h3>
   </div>
   <div class = "panel-body" style="width:100%">
		<div style="float:left;margin-left;0px"><h4>Save Payroll</h4></div>
		<div style="float:right; margin-right:10px"><h4>PAYROLL Period: <?php echo $_SESSION['payroll']['period'].", ".$_SESSION['payroll']['month']." ".$_SESSION['payroll']['year']; ?></h4></div>
		<div style="clear:both;">&nbsp;</div>
		<?php echo $message; ?>
	</div>
</div>
<?php echo $js; ?>