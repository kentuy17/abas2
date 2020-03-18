
<?php

foreach($tablefields AS $tf) {
	$record[$tf->COLUMN_NAME]	=	"";
}

$title			=	"Add Vessel Certificate";
$action	=	HTTP_PATH.'vessels/vessel_certificates/insert';

$cert_id	=	"";
$vessel_id	=	"";
$cert_date	=	"";
$exp_date	=	"";
$type		=	"";

if(isset($recid)) {
	$title		=	"Edit Vessel Certificate";
	$record		=	$this->db->query("SELECT * FROM ".$table." WHERE id=".$recid);
	$record		=	$record->row();

	$cert_id	=	$record->id;
	$vessel_id	=	$record->vessel_id;
	$cert_date	=	$record->cert_date;
	$exp_date	=	$record->expiration_date;
	$type		=	$record->type;

	$action		=	HTTP_PATH.'vessels/vessel_certificates/update/'.$cert_id;
}

$vesseloptions	=	"";
foreach($this->Abas->getVessels() as $s) {
	$vesseloptions	.=	"<option ".($vessel_id==$s->id?"SELECTED":"")." value='".$s->id."'>".$s->name."</option>";
}

?>



<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title">
		<?php echo $title; ?>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2>
	</div>
</div>

<div class='panel-body'>
	<form action='<?php echo $action; ?>' role='form' method='POST' id='certForm' enctype='multipart/form-data' >
			<?php echo $this->Mmm->createCSRF(); ?>

			<div class='col-xs-12 col-md-12'>
				<label>For Vessel*</label>
				<select name='vessel_id' id='vessel_id0' class='form-control'>
					<option value=''>Select</option>
					<?php echo $vesseloptions; ?>
				</select>
			</div>

			<div class='col-xs-12 col-md-6'>
				<label>Certificate Date*</label>
				<input type="text" name='cert_date' id='cert_date1' class='form-control' value='<?php echo $cert_date;?>'>
			</div>

			<div class='col-xs-12 col-md-6'>
				<label>Expiration Date*</label>
				<input type="text" name='expiration_date' id='expiration_date2' class='form-control' value='<?php echo $exp_date;?>'>
			</div>

			<div class='col-xs-12 col-md-12'>
				<label>Document Type*</label>
				<input type="text" name='type' id='type3' class='form-control' placeholder="Format: Name/Description of Document (Document Number)" value='<?php echo $type;?>'>
			</div>

			<div class='col-xs-12 col-xs-12 col-lg-12'>
				<br>
				<span class="pull-right">
					<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: checkform()'/>
					<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
				</span>
			</div>

	</form>
</div>


<script>

$("#cert_date1").datepicker({changeYear: true,yearRange: "-100:+10", dateFormat: "yy-mm-dd"});
$("#expiration_date2").datepicker({changeYear: true,yearRange: "-100:+10", dateFormat: "yy-mm-dd"});

function checkform() {
	var msg="";
	
	var vessel = $('#vessel_id0').val();
	var cert_date = $('#cert_date1').val();
	var exp_date = $('#expiration_date2').val();
	var type = $('#type3').val();

	if (vessel==null || vessel=="") {
		msg+="Vessel is required! <br/>";
	}
	if (cert_date==null || cert_date=="") {
		msg+="Certificate Date is required! <br/>";
	}
	if (exp_date==null || exp_date=="") {
		msg+="Expiration Date is required! <br/>";
	}
	if (type==null || type=="") {
		msg+="Document Type is required! <br/>";
	}

	if(msg!="") {
		$("#btnSubmit").visible=true;
		toastr["error"](msg,"ABAS Says:");
		return false;
	}
	else{
		$("#btnSubmit").visible=true;

		$('body').addClass('is-loading'); 
		$('#modalDialog').modal('toggle'); 

		document.getElementById("certForm").submit();
		return true;
	}
}

</script>

