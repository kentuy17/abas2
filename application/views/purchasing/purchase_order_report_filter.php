<?php
	$companyoptions	=	"";
	$companies		=	$this->Abas->getCompanies();
	if(!empty($companies)) {
		foreach($companies as $c) {
			$companyoptions	.=	"<option value='".$c->id."'>".$c->name."</option>";
		}
	}
	$supplieroptions	=	"";
	$suppliers			=	$this->Abas->getSuppliers();
	if(!empty($suppliers)) {
		foreach($suppliers as $s) {
			$supplieroptions	.=	"<option value='".$s['id']."'>".$s['name']."</option>";
		}
	}
	$categoryoptions	=	"";
	$categories			=	$this->Inventory_model->getCategories();
	if(!empty($categories)) {
		foreach($categories as $c) {
			$categoryoptions	.=	"<option value='".$c['id']."'>".$c['category']."</option>";
		}
	}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		Purchase Order Report
	</div>
</div>
	<div class="panel-body">
		<form action="<?php echo HTTP_PATH."purchasing/purchase_order_report/report"; ?>" method="GET" onsubmit="javascript: checkform();" enctype="multipart/form-data" name="fs_form" id="fs_form">
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="company" class="control-label">Company</label>
				<select class="form-control" id="company" name="company">
					<option value="">All companies</option>
					<?php echo $companyoptions; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="supplier" class="control-label">Supplier</label>
				<select class="form-control" id="supplier" name="supplier">
					<option value="">All suppliers</option>
					<?php echo $supplieroptions; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="category" class="control-label">Category</label>
				<select class="form-control" id="category" name="category">
					<option value="">All Categories</option>
					<?php echo $categoryoptions; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="status" class="control-label">PO Status</label>
				<select class="form-control" id="status" name="status">
					<option value="Active">Active</option>
					<option value="Cancelled">Cancelled</option>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label for="status" class="control-label">Service Status</label>
				<select class="form-control" id="service_status" name="service_status">
					<option value="all">All</option>
					<option value="served">Served</option>
					<option value="unserved">Unserved</option>
				</select>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dstart" class="control-label">Date From</label>
				<input type="text" name="dstart" id="dstart" class="form-control datepicker" value="<?php echo date("Y-m-")."01"; ?>" />
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<label for="dfinish" class="control-label">Date To</label>
				<input type="text" name="dfinish" id="dfinish" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
			</div>
			<div class="clearfix"><br/></div>
			<div class="col-md-offset-1 col-md-10">
				<br>
				<input type="button" onclick="javascript: checkform();" class="btn btn-success btn-block" value="Generate Report" />
			</div>
		</form>
	</div>

<script>
$(".datepicker").datepicker();
function checkform() {
	$(this).prop("disabled", true);
	var msg="";
	//var patt1=/^[0-9]+$/i;
	var patt1=/^\d+(\.\d+)*$/i;
	var datestart=document.forms.fs_form.dstart.value;
	if (datestart==null || datestart=="" || datestart==0) {
		msg+="Date From is required! <br/>";
	}
	var datefinish=document.forms.fs_form.dfinish.value;
	if (datefinish==null || datefinish=="" || datefinish==0) {
		msg+="Date To is required! <br/>";
	}
	if(msg!="") {
		$(this).prop("disabled", false);
		toastr["warning"](msg,"ABAS Says");
		return false;
	}
	else {
		$(this).prop("disabled", false);
		document.getElementById("fs_form").submit();
		return true;
	}
}
</script>