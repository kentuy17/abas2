<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_title"><h3 style="color:white;font-size: 27px">Corporate Services Dashboard</h3></div>
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
			<a href="<?=HTTP_PATH."Corporate_Services/purchase_requests/listview/"?>" class="btn btn-success btn-x btn-block">
				<h4><span class="glyphicon glyphicon-list-alt" style="font-size:55px"></span> <br>Materials/Services Requests</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."Corporate_Services/request_for_payment/listview"?>" class="btn btn-warning btn-x btn-block">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-ruble"></span> <br>Request for Payments</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."Corporate_Services/attendance"?>" class="btn btn-danger btn-x btn-block disabled">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-th"></span> <br>Attendance Logs</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."Corporate_Services/leave"?>" class="btn btn-info btn-x btn-block">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-calendar"></span> <br>Leave Applications</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."Corporate_Services/overtime"?>" class="btn btn-primary btn-x btn-block">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-plus"></span> <br>Overtime Applications</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."Corporate_Services/undertime"?>" class="btn btn-dark btn-x btn-block disabled">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-minus">0</span> <br>Undertime Applications</h4>
			</a>
		</div>
	</div>