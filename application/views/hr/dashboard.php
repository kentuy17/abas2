<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_title"><h3 style="color:white;font-size: 27px">Human Resources' Dashboard</h3></div>
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
			<a href="<?=HTTP_PATH."hr/employees"?>" class="btn btn-success btn-x btn-block">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-user"><?=$emp_count?></span> <br>Employee Count</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."hr/employee_for_transfer/view"?>" class="btn btn-warning btn-x btn-block">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-share-alt"><?=$emp_for_transfer?></span> <br>Employee/s for Transfer</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."hr/employees_for_awol"?>" class="btn btn-danger btn-x btn-block">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-warning-sign"><?=$for_awol?></span> <br>Employee/s for AWOL</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."hr/leave"?>" class="btn btn-info btn-x btn-block">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-calendar"><?=$leave?></span> <br>Leave Applications</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."hr/overtime_approval"?>" class="btn btn-primary btn-x btn-block">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-plus"><?=$overtime?></span> <br>Overtime Applications</h4>
			</a>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<a href="<?=HTTP_PATH."accounting"?>" class="btn btn-dark btn-x btn-block disabled">
				<h4><span style="font-size:55px" class="glyphicon glyphicon-minus">0</span> <br>Undertime Applications</h4>
			</a>
		</div>
	</div>