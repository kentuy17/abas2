<?php
	$company="";
	$client = "";

	$companies=$this->db->query("SELECT * FROM companies");
	$companies=$companies->result_array();
	$companyoptions="";
	if(!empty($companies)){
		foreach($companies as $c){
			$companyoptions.="<option ".($company==$c['id']?"SELECTED":"")." value='".$c['id']."'>".$c['name']."</option>";
		}
	}

	$clients=$this->db->query("SELECT * FROM clients");
	$clients=$clients->result_array();
	$clientsoptions="";
	if(!empty($clients)){
		foreach($clients as $c){
			$clientsoptions.="<option ".($client==$c['id']?"SELECTED":"")." value='".$c['id']."'>".$c['company']."</option>";
		}
	}

$itemform = "
<div class='row item-row'><div class='form-group col-xs-8'><input type='text' class='col-xs-12' name='particular[]' id='particular[]' placeholder='Particular'/></div><div class='form-group col-xs-4'><input type='text' class='col-xs-12' name='amount[]' id='amount[]' placeholder='Amount' onkeyup= 'total()'/></div></div>
"
;

?>
<table data-toggle="table" id="ac_billing" class="table table-stripped table-hover" data-url="http://abas-dev.avegabros.org/finance/billing/json" data-side-pagination="server" data-pagination="true" data-search="true" data-cache="false" data-show-columns="true" data-page-list="[5,10,20,50]">
	<!--<div>
		<a href='<?php echo HTTP_PATH; ?>finance/billing/add_billing' role='button' class='btn btn-info' data-toggle='modal' data-target='#modalDialog'>Add Billing</a>
		<a href='<?php echo HTTP_PATH; ?>finance/billing/payment' role='button' class='btn btn-info' data-toggle='modal' data-target='#modalDialog'>Payment</a>
	</div>-->

	<div class="panel-group" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
			<div class="panel-body">
				<ul class="nav nav-tabs">
					<li class= "nav-info"><a data-toggle="tab" href="#fromContract">From Contract</a></li>
					<li class="nav-info"><a data-toggle="tab" href="#fromSales">From Sales</a></li>
					<li class="nav-info"><a data-toggle="tab" href="#fromRental">From Rental</a></li>
					<li class="nav-info"><a data-toggle="tab" href="#fromClaims">From Claims</a></li>
				</ul>
				<div class="tab-content">
					<div id="fromContract">
					<a href='<?php echo HTTP_PATH; ?>finance/billing/payment' role='tablist' data-toggle='modal'></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<thead>
		<tr>
			<th data-sortable="true" data-align="center" data-field="soa">SOA#</th>
			<th data-sortable="true" data-align="center" data-field="reference_no">Reference No.</th>
			<th data-sortable="true" data-align="center" data-field="date">Date</th>
			<th data-sortable="true" data-align="center" data-field="client">Client</th>
			<th data-sortable="true" data-align="center" data-field="amount">Amount</th>
			<th data-sortable="true" data-align="center" data-field="status">Status</th>
			<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents" data-align="center">Manage</th>
		</tr>
	</thead>
	<script>
		function operateFormatter(value, row, index) {
			return[
			'<a class="btn btn-xs btn-default" href="<?php echo HTTP_PATH.'finance/billing/payment'?>" data-toggle="modal" data-target="#modalDialog" title="Payment">Payment</a>'
			].join('');
		}
		window.operateFormatter ={
			'click.like': function (e, value, row, index){
				p = row["sid"];
				var wid = 940;
				var leg = 680;
				var left = (screen.width/2)-(wid/2);
				var top = (screen.height/2)-(leg/2);
			},
			'click.payment': function (e, value, row, index) {
				p = row["sid"];
			}
		};
		$(function (){
			var $table = $('#ac_billing-table');
			$table.bootstrapTable();
		});
	</script>
</table>