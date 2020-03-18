<?php
		$get_year = $this->input->get('year');
		if($get_year != null){
			$year = $get_year;
		}else{
			$year = date('Y');
		}
?>

<h2 id="glyphicons-glyphs">Company Summary report-table </h2>
<div id="modalDialogYear" class="modal fade modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Select Year
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
					</h2>
				</div>
			</div>
			<div class="panel-body">
				<form action="<?=HTTP_PATH.'budget/filter/company_summary_report'?>" role="form" method="POST" enctype="multipart/form-data">
					<div class="panel panel-info">
						<div class="panel-body" id="summary_container">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<label>Increment:</label>
								<select class="form-control col-lg-3" name="year">
								<?php foreach ($budget_year as $value) { ?>
									<option value="<?=$value->year?>"><?=$value->year?></option>
								<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					<div class="col-xs-12 col-sm-12 col-lg-12"s>
						<br>
						<span class="pull-right">
							
							<input type="submit" value="Select" class="btn btn-success btn-m"/>
							<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
						</span>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="panel-group" role="tablist" aria-multiselectable="true">
	<div class="panel panel-default">
		<div class="panel-body">
			<ul class="nav nav-tabs">
				<li class="active nav-danger"><a data-toggle="tab" href="#tabCompany">Company</a></li>
				<li class="nav-warning"><a data-toggle="tab" href="#tabAccountCodes">Account Codes</a></li>
				<li class="nav-info"><a data-toggle="tab" href="#tabClassification">Account Classifications</a></li>
				<!--li class="nav-success"><a data-toggle="tab" href="#approvedBudget">Approved Budget</a></li>
				<!--a href="<?php echo HTTP_PATH.'manager/set_increment'; ?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">
					<button class="btn btn-success btn-sm pull-right">Set Increment</button>
					
				</a-->
				<!--button class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalDialogYear">Select Year</button-->
				<button class="btn btn-primary pull-right" onclick="companyBudgetSummary()">Select Year</button>
			</ul>
			<div class="tab-content">
				<div id="tabCompany" class="tab-pane fade in active" >
					<br/>
					<table id="report-table" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
						<thead>
							<tr>
								<th>ID</th>
								<th>Department</th>
								<th>Abbreviation</th>
								<th>Total Credit</th>
								<th>Total Debit</th>
								<!--th>Budget Last Year</th>
								<th>Budget This Year</th-->
								<th>Manage</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if($company != null){
						foreach ($company as $key => $row) { ?>
							<tr>
								<td><?=$row['id']?></td>
								<td><?=$row['name']?></td>
								<td><?=$row['abbreviation']?></td>
								<td style="text-align: right"><?=number_format($row['total_credit'],2)?></td>
								<td style="text-align: right"><?=number_format($row['total_debit'],2)?></td>
								<!--td style="text-align: right"><?=number_format($row['last_year'],2)?></td>
								<td style="text-align: right"><?=number_format($row['this_year'],2)?></td-->
								<td>
									<a href="<?=HTTP_PATH.'budget/view_company_report/'.$row['id'].'?year='.$year?>" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">
										<button class="btn btn-info btn-xs btn-block">View</button>
									</a>
								</td>
							</tr>
						<?php } } ?>	
						</tbody>
					</table>
				</div>
				<div id="tabAccountCodes" class="tab-pane fade">
					<br/>
					<table id="accounts-table" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
						<thead>
							<tr>
								<th>#</th>
								<th>Code</th>
								<th>Account Name</th>
								<th>Budget Last Year</th>
								<th>Budget This Year</th>
								<th>Account Type</th>
								<th>Year</th>
								<th>Manage</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if($accounts != null){
						foreach ($accounts as $key => $row) { ?>
							<tr>
								<td><?=$row['id']?></td>
								<td><?=$row['code']?></td>
								<td><?=$row['name']?></td>
								<td><?=number_format($row['last_year'],2)?></td>
								<td><?=number_format($row['this_year'],2)?></td>
								<td><?=$row['type']?></td>
								<td><?=$row['year']?></td>
								<td>
									<a href="<?=HTTP_PATH.'budget/view_account_report/'.$row['id'].'?year='.$year?>" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">
										<button class="btn btn-info btn-xs btn-block">View</button>
									</a>
								</td>
							</tr>
						<?php } } ?>	
						</tbody>
					</table>
				</div>
				<div id="tabClassification" class="tab-pane fade">
					<br/>
					<table id="classifications-table" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
						<thead>
							<tr>
								<th>#</th>
								<th>Account Classification</th>
								<th>Budget Last Year</th>
								<th>Budget This Year</th>
								<th>Year</th>
								<th>Manage</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if($classifications != null){
						foreach ($classifications as $key => $row) { ?>
							<tr>
								<td><?=$row['id']?></td>
								<td><?=$row['classification']?></td>
								<td><?=number_format($row['last_year'],2)?></td>
								<td><?=number_format($row['this_year'],2)?></td>
								<td><?=$row['year']?></td>
								<td>
									<a href="<?=HTTP_PATH.'budget/view_classification_report/'.$row['id'].'?year='.$year?>" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">
										<button class="btn btn-info btn-xs btn-block">View</button>
									</a>
								</td>
							</tr>
						<?php } } ?>	
						</tbody>
					</table>
				</div>
				<!--div id="approvedBudget" class="tab-pane fade">
					
				</div-->
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
	  $('#report-table').DataTable();
		});

	$(document).ready(function () {
	  $('#accounts-table').DataTable();
		});

	$(document).ready(function () {
	  $('#classifications-table').DataTable();
		});
</script>
<script type="text/javascript">
	function companyBudgetSummary(){
    	bootbox.prompt({
    		size: "small",
		    title: "Select Year",
		    centerVertical: 'true',
		    inputType: 'select',
		    inputOptions: [
		    <?php foreach($budget_year as $row) {?>
			    {
			        text: '<?=$row->year?>',
			        value: '<?=$row->year?>',
			    },
			<?php } ?>
		    ],
		    callback: function (result) {
		    	if(result != null){
		    		window.location.href = "<?=HTTP_PATH.'budget/company_summary_report/'?>"+result;	
		    	}
		    }
		});
    }
</script>
