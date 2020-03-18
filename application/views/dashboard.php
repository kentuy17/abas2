<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_title"><h3 style="color:white;font-size: 27px">AVega Business Automation System</h3></div>
	<span class="pull-right" style="margin-top: -55px;margin-right: 10px">
	<?php 
		$v = $this->Abas->readChangeLog();
		if($v!=null){
		echo '<a class="btn btn-xs btn-dark" style="color:white" href="'.HTTP_PATH.'system/open_change_log" data-toggle="modal" data-target="#modalDialog">'.$v['num'].'</a>';
		}
	;?>
	</span>
</div>
<div class="container-fluid panel dashboard" style="background: #2A3F54">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?php echo HTTP_PATH."manager"; ?>" class="btn btn-warning btn-x btn-block">
				<h4><span class="glyphicon glyphicon-eye-open" style="font-size:55px"></span> <br>Manager's Dashboard</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?php echo HTTP_PATH."hr"; ?>" class="btn btn-info btn-x btn-block">
				<h4><span class="glyphicon glyphicon-user" style="font-size:55px"></span> <br>Human Resources</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?php echo HTTP_PATH."payroll"; ?>" class="btn btn-info btn-x btn-block">
				<h4><span class="glyphicon glyphicon-list-alt" style="font-size:55px"></span> <br>Payroll</h4>
			</a>
		</div>
		
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?php echo HTTP_PATH."purchasing"; ?>" class="btn btn-danger btn-x btn-block">
				<h4><span class="glyphicon glyphicon-shopping-cart" style="font-size:55px"></span> <br>Purchasing</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?php echo HTTP_PATH."inventory"; ?>" class="btn btn-success btn-x btn-block">
				<h4><span class="glyphicon glyphicon-transfer" style="font-size:55px"></span> <br>Inventory</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?php echo HTTP_PATH."accounting"; ?>" class="btn btn-danger btn-x btn-block">
				<h4><span class="glyphicon glyphicon-book" style="font-size:55px"></span> <br>Accounting</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?php echo HTTP_PATH."operation"; ?>" class="btn btn-warning btn-x btn-block">
				<h4><span class="glyphicon glyphicon-move" style="font-size:55px"></span> <br>Marketing & Operations</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?php echo HTTP_PATH."finance"; ?>" class="btn btn-danger btn-x btn-block">
				<h4><span class="glyphicon glyphicon-stats" style="font-size:55px"></span> <br>Finance</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?php echo HTTP_PATH."Asset_Management"; ?>" class="btn btn-warning btn-x btn-block">
				<h4><span class="glyphicon glyphicon-wrench" style="font-size:55px"></span> <br>Asset Management</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="#" class="btn btn-dark btn-x btn-block disabled">
				<h4><span class="glyphicon glyphicon-thumbs-up" style="font-size:55px"></span> <br>Compliance Management</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH.'Corporate_Services'?>" class="btn btn-dark btn-x btn-block">
				<h4><span class="glyphicon glyphicon-flag" style="font-size:55px"></span> <br>Corporate Services</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="#" class="btn btn-dark btn-x btn-block disabled">
				<h4><span class="glyphicon glyphicon-fire" style="font-size:55px"></span> <br>IT Services</h4>
			</a>
		</div>
</div>