<?php
	$title = 'Edit Leave Application';
	//$type = $item->type;
	//$payed = $item->is_with_pay;
	$id = $item->id;
	$date_from = $item->date_from;
	$date_to = $item->date_to;
	$reason = $item->reason;
	$address = $item->address;
	$contact_no = $item->contact_number;
	$employee = array();
	$emp_name = $this->Abas->getEmpName($item->employee_id);

	switch ($item->is_with_pay) {
		case 1:
			$with_pay = 'WITH PAY';
		break;
		
		case 0:
			$with_pay = 'WITHOUT PAY';
		break;
	}
?>
<style type="text/css">
	#modal-bot{
		padding-bottom: 15px;
	}
</style>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title">Leave Application Approval
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<div class="panel panel-info">
		<div class="panel-body" id="summary_container">
			<?php //$this->Mmm->debug($item) ?>
			<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
				<label>Employee:</label>
				<input type="text" class="form-control" value="<?=strtoupper($emp_name)?>" name="type" readonly>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
				<label>Leave Type:</label>
				<input type="text" class="form-control" value="<?=strtoupper($item->type)?>" name="type" readonly>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
				<label>Payed:</label>
				<input type="text" class="form-control" value="<?=strtoupper($with_pay)?>" readonly>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
				<label>Date From:</label>
				<input type="date" name="date_from" value="<?=$date_from?>" class="form-control" readonly>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6" id="modal-bot">
				<label>Date To:</label>
				<input type="date" name="date_to" value="<?=$date_to?>" class="form-control" readonly>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
				<label>Reason of Leave:</label>
				<textarea class="form-control" name="reason" placeholder="Reason of Leave" readonly><?=$reason?></textarea>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
				<label>Address while on Leave:</label>
				<input type="text" name="address" value="<?=$address?>" class="form-control" placeholder="Address while on Leave" readonly>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
				<label>Contact Number while on Leave:</label>
				<input type="number" name="contact_no" value="<?=$contact_no?>" class="form-control" placeholder="Contact Number while on Leave" readonly>
			</div>
		</div>
	</div>	
	<div class="col-xs-12 col-sm-12 col-lg-12"s>
		<br>
		<span class="pull-right">
			<?php if($item->status == 'FOR APPROVAL') { ?>
				<a href="<?=HTTP_PATH.'Corporate_Services/approval/reject/'.$id?>" class="btn btn-danger">Reject</a>
				<a href="<?=HTTP_PATH.'Corporate_Services/approval/approve/'.$id?>" class="btn btn-success">Approve</a>
			<?php }elseif($item->status == 'FOR PROCESSING') { ?>
				<a href="<?=HTTP_PATH.'Corporate_Services/approval/approve/'.$id?>" class="btn btn-warning">Cancel</a>
				<input type="button" class="btn btn-danger btn-m" value="Close" data-dismiss="modal">
			<?php }else{ ?>
				<input type="button" class="btn btn-danger btn-m" value="Close" data-dismiss="modal">
			<?php } ?>
			
			<!--input type="submit" value="Submit" class="btn btn-success btn-m"/-->
		</span>
	</div>
</div>


<script type="text/javascript">
	$( "#emp_auto_complete" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>Corporate_Services/autocomplete_employee",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr["warning"]("Employee not found!", "ABAS Says");
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$('#emp_auto_complete').val( ui.item.label );
			return false;
		}
	});
</script>