<div class="panel-group" id="content">
	<div class="panel panel-default">
		<div class="panel-body">
            <ul class="nav nav-tabs">
				<li class="active"><a data-toggle="pill" href="#summary"><span class="glyphicon glyphicon-home"></span> Home</a></li>
				<li><a data-toggle="pill" href="#contract"><span class="glyphicon glyphicon-list-alt"></span> Contracts</a></li>
				<li><a data-toggle="pill" href="#service"><span class="glyphicon glyphicon-tasks"></span> Service Orders</a></li>
				<li><a data-toggle="pill" href="#billing"><span class="glyphicon glyphicon-th"></span> Billing</a></li>
				<li><a data-toggle="pill" href="#payment"><span class="glyphicon glyphicon-credit-card"></span> Payment</a></li>
            </ul>
            <div class="tab-content">
				<div id="summary" class="tab-pane fade in active">
					<div class="panel-group" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default summary-panel">
							<div class="panel-heading">
                                Summary of Transactions
							</div>
							<div class="panel-body">
								<table class="table table-striped table-bordered table-hover table-condensed" data-toggle="table">
									<thead>
										<tr>
										<th width="5%">Date</th>
										<th width="40%">Contract Detail</th>
										<th width="30%">Activity</th>
										<th width="10%">Status</th>
										<th width="15%">Remark</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="contract" class="tab-pane fade">
					<div class="panel-group" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default">
							<div class="panel-heading">
								<a class="btn btn-default" href="<?php echo HTTP_PATH.'operation/contracts/add'; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="New Contract">
									<span class="glyphicon glyphicon-file"></span> New
								</a>
								<a type="button" class="btn btn-default"><span class="glyphicon glyphicon-list"></span> Reports</a>
							</div>
							<div class="panel-body">
								<table data-toggle="table" id="s_contracts" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."operation/view_all_contracts"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true">
									<thead>
										<tr>
											<th data-field="reference_no" data-align="center" data-visible="true">Reference Number</th>
											<th data-field="company_name" data-align="center" data-visible="true">Company</th>
											<th data-field="date_effective" data-align="center" data-visible="true">Date Effective</th>
											<th data-field="charterer_name" data-align="center" data-visible="true">Charterer</th>
											<th data-field="type" data-align="center" data-visible="true">Charter Type</th>
											<th data-field="unit" data-align="center" data-visible="true">Unit</th>
											<th data-field="rate" data-align="center" data-visible="false">Rate</th>
											<th data-field="quantity" data-align="center" data-visible="false">Quantity</th>
											<th data-field="amount" data-align="center" data-visible="true">Total Amount</th>
											<th data-field="cargo_details" data-align="center" data-visible="false">Details</th>
											<th data-field="status" data-align="center" data-visible="true">Status</th>
											<th data-field="operate" data-formatter="manageContracts" data-halign="center" data-align="center" >Manage</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="service" class="tab-pane fade">
                	<div class="panel-group" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="form-inline">

									<a class="button" href="<?php echo HTTP_PATH.'operation/service_order_form/';?>" data-toggle="modal" data-target="#modalDialog" title="Service Order">
                                        <button type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-link"></span> Create Service Order</button>
									</a>
									<a class="button" href="<?php echo HTTP_PATH.'operation/vessel_order_form/'; ?>" data-toggle="modal" data-target="#modalDialog" title="Service Order">
                                        <button type="button" class="btn btn-warning btn-sm">Receiving</button>
									</a>
									<button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-list"></span> Reports</button>
                                        <button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span> View Service Orders</button>
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-striped table-bordered table-hover table-condensed" id="s_contracts" data-toggle="table" data-url="" data-search="true" data-height="400" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" >
									<thead>
										<tr>
											<th data-field="vessel_name" data-halign="left"  data-align="left" width="10%">Service ID</th>
											<th data-field="activity"  data-halign="center" width="15%">Reference #</th>
											<th data-field="activity"  data-halign="center" width="15%">Service Type</th>
											<th data-field="remark"  data-halign="center" width="35%">Client</th>
											<th data-field="reference_no"  data-halign="center" width="15%">Date Issued</th>
											<th data-field="reference_no"  data-halign="center" width="25%">Service Provider</th>
											<th data-field="reference_no"  data-halign="center" width="10%">Status</th>
										</tr>
									</thead>
								</table>
							</div>
                        </div>
                    </div>
				</div>
				<div id="billing" class="tab-pane fade">
					<div class="panel-group" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default">
							<div class="panel-heading">
								<a type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-list"></span> Reports</a>
								<a type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span> View Billing Status</a>
							</div>
							<div class="panel-body">
								<div class="panel panel-default">
									<div class="panel-heading">
										<strong class="panel-title"> Billing:</strong>
									</div>
									<div class="panel-body" role="tab" >
										<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH; ?>" method="post" enctype='multipart/form-data'>
											<div class="col-lg-12">
												<label for="refnum">Enter Reference Number:</label>
												<input type="text" class="form-control input-sm" id="refnum" name="refnum" />
											</div>
										</form>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover table-condensed" id="s_contracts" data-toggle="table" data-url="" data-search="true"   data-height="400"  data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" >
									<thead>
										<tr>
											<th data-field="vessel_name" data-halign="left"  data-align="left">Contract ID</th>
											<th data-field="activity"  data-halign="center">Company</th>
											<th data-field="remark"  data-halign="center">Customer</th>
											<th data-field="reference_no"  data-halign="center">Contract Date</th>
											<th data-field="reference_no"  data-halign="center">Service Type</th>
											<th data-field="reference_no"  data-halign="center">Status</th>
										</tr>
									</thead>
								</table>
                            </div>
                        </div>
                    </div>
				</div>
				<div id="payment" class="tab-pane fade">
                    <div class="panel-group" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default">
							<div class="panel-heading">
								<a class="btn btn-default btn-sm"><span class="glyphicon glyphicon-file"></span> New</a>
								<a class="btn btn-default btn-sm"><span class="glyphicon glyphicon-list"></span> Reports</a>
							</div>
							<div class="panel-body">
								<div class="panel panel-default">
									<div class="panel-heading">
										<strong class="panel-title">Payment:</strong>
									</div>
									<div class="panel-body">
										<form class="form-horizontal" role="form" action="<?php  ?>" method="post" enctype='multipart/form-data'>
											<div class="col-lg-12">
												<label  for="city">Enter Billing Number:</label>
												<input type="text" class="form-control input-sm" id="refnum" name="refnum" />
											</div>
										</form>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover table-condensed" id="s_contracts" data-toggle="table" data-url="" data-search="true"   data-height="400"  data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" >
									<thead>
										<tr>
											<th data-field="vessel_name" data-halign="left"  data-align="left">Contract ID</th>
											<th data-field="activity"  data-halign="center">Company</th>
											<th data-field="remark"  data-halign="center">Customer</th>
											<th data-field="reference_no"  data-halign="center">Contract Date</th>
											<th data-field="reference_no"  data-halign="center">Service Type</th>
											<th data-field="reference_no"  data-halign="center">Status</th>
										</tr>
									</thead>
								</table>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer success text-right"><strong>AVEGA<span>iT</span>.2015</strong></div>
</div>

<script>
$(function () {
	var $table = $('#s_contracts');
	$table.bootstrapTable();
});
function manageContracts(value, row, index) {
	return [
		'<a class="btn btn-default btn-xs btn-block" href="<?php echo HTTP_PATH.'operation/contracts/view/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Profile">',
			'<!--i class="glyphicon glyphicon-list-alt"></i--> View',
		'</a>',
		'<a class="btn btn-default btn-xs btn-block" href="<?php echo HTTP_PATH.'operation/contracts/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit">',
			'<!--i class="glyphicon glyphicon-edit"></i--> Edit',
		'</a> '
	].join('');
};
</script>
