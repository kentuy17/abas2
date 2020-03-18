<?php

$a1=$a2=$a3=$a4 = "";
if(!empty($cv_attachments)){
	foreach($cv_attachments as $attachment){
		switch($attachment['document_name']){
			case "Approved & Signed Copy of Check Voucher":
				$a1=$attachment['filename'];
			break;
			case "Signed Copy of Official Receipt":
				$a2=$attachment['filename'];
			break;
			case "Signed Copy of Collection Receipt":
				$a3=$attachment['filename'];
			break;
			case "Other Supporting Documents":
				$a4=$attachment['filename'];
			break;
		}
	}
}

?>


<style>
	.lbl{font-weight:bold;text-align:right;}
</style>

<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">
			Check Releasing
		</h2>
	</div>
</div>

	<div class='panel-body'>
	
		<form id='check_releasing_form' role='form' action='<?php echo HTTP_PATH."finance/check_releasing/release/".$CV['id']?>' method='POST' enctype='multipart/form-data'>

		<div class='tile-stats col-xs-12 col-md-12'>
			<br><label>Check Voucher</label><br>

			<table class="table">
				<tr>
					<td class='lbl'>CV No.:</td>
					<td><?php echo $CV['voucher_number'] . " (Transaction Code No.".$CV['id'].")"?></td>
					<td class='lbl'>Check No.:</td>
					<td><?php echo $CV['check_num'];?></td>
				</tr>
				<tr>
					<td class='lbl'>Company:</td>
					<td><?php echo $CV['company']->name;?></td>
					<td class='lbl'>Voucher Date:</td>
					<td><?php echo date("j F Y", strtotime($CV['voucher_date']));?></td>
				</tr>
				<tr>
					<td class='lbl'>Payment To:</td>
					<td><?php echo $CV['payee_name'];?></td>
					<td class='lbl'>Payee Type:</td>
					<td><?php 
						if($CV['payee_type']!=''){
							echo $CV['payee_type'];
						}else{
							echo "Supplier";
						}
					?></td>
				</tr>
				<tr>
					<td class='lbl'>Bank:</td>
					<td><?php echo $CV['bank'];?></td>
					<td class='lbl'>Check Date:</td>
					<td><?php echo date("j F Y", strtotime($CV['check_date']));?></td>
				</tr>
				<tr>
					<td class='lbl'>Remarks:</td>
					<td><?php echo $CV['remark'];?></td>
					<td class='lbl'>Amount:</td>
					<td><?php echo number_format($CV['amount'],2,'.',',');?></td>
				</tr>
			</table>
		</div>

		<div class='tile-stats col-xs-12 col-md-12'>
			<br><label>Documents Submitted</label><br>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<td></td>
						<td>Documents</td>
						<td>Scanned Copies (PDF or Image format)</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='cv_check' value='Approved & Signed Copy of Check Voucher'
						<?php 
							if($CV['status']=='Paid'){
								if($a1){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Approved & Signed Copy of Check Voucher</td>
						<?php 
							if($CV['status']=='Paid') {
								if($a1!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/finance/check_releasing/attachments/'.$a1.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a1.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="cv_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='or_check' value='Signed Copy of Official Receipt' <?php 
							if($CV['status']=='Paid'){
								if($a2){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Signed Copy of Official Receipt</td>
						<?php 
							if($CV['status']=='Paid') {
								if($a2!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/finance/check_releasing/attachments/'.$a2.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a2.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="or_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='cr_check' value='Signed Copy of Collection Receipt'
						<?php 
							if($CV['status']=='Paid'){
								if($a3){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Signed Copy of Collection Receipt</td>
						<?php 
							if($CV['status']=='Paid') {
								if($a3!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/finance/check_releasing/attachments/'.$a3.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a3.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="cr_file" disabled></td>';
							}
						?>
					</tr>
					<tr>
						<td align='center'><input type='checkbox' name='attachment[]' id='others_check' value='Other Supporting Documents'
						<?php 
							if($CV['status']=='Paid'){
								if($a4){
									echo "checked disabled";
								}
								else{
									echo "disabled";
								}
							}
						 ?>
						 ></td>
						<td>Other Supporting Documents</td>
						<?php 
							if($CV['status']=='Paid') {
								if($a4!=""){
									echo '<td><a href="'.HTTP_PATH.'../assets/uploads/finance/check_releasing/attachments/'.$a4.'" class="btn btn-xs btn-default" target="_blank">View File</a> '.$a4.'</td>';
								}else{
									echo '<td>No attachment</td>';
								}
							}
							else{
								echo '<td><input type="file" accept=".jpg,.png,.jpeg,.bmp,.pdf" name="attach_file[]" id="attach_file[]" class="others_file" disabled></td>';
							}
						?>
					</tr>
					
				</tbody>
			</table>
		</div>

		<div class='tile-stats col-xs-12 col-md-12'>
			<?php
				$official_receipt = '';
				$notes = '';
				$readonly = '';
				if($CV['status']=='Paid'){
					$official_receipt = $CV['or_no'];
					$notes = $CV['pay_to'];
					$readonly = 'disabled';
				}
			?>
			<br>
			<div class='col-xs-12 col-sm-4 col-md-4'>
				<label>Official Receipt No.:</label><br>
				<input type='text' name='official_receipt' id='official_receipt' class='form-control' value='<?php echo $official_receipt?>' <?php echo $readonly?>>
			</div>
			<div class='col-xs-12 col-sm-12 col-md-12'>
				<label>Notes:</label><br>
				<textarea name='notes' id='notes' class='form-control' <?php echo $readonly?>><?php echo $notes;?></textarea>
				<br>
			</div>
			
		</div>

			<div class='col-sm-12 col-md-12'>
				<span class="pull-right">
					<?php if($CV['status']!='Paid'){?>
					<input type="button" class="btn btn-success btn-m" onclick="javascript:releaseCheck(<?php echo $CV['id'];?>)" value="Submit"/>
					<?php } ?>
					<input type="button" class="btn btn-danger btn-m" value="Close" data-dismiss="modal" />
				</span>
			</div>

		</form>

	</div>



<script type="text/javascript">


$('#cv_check').change(function(){
   $(".cv_file").prop("disabled", !$(this).is(':checked'));
    $(".cv_file").val("");
});
$('#or_check').change(function(){
   $(".or_file").prop("disabled", !$(this).is(':checked'));
    $(".or_file").val("");
});
$('#cr_check').change(function(){
   $(".cr_file").prop("disabled", !$(this).is(':checked'));
    $(".cr_file").val("");
});
$('#others_check').change(function(){
   $(".others_file").prop("disabled", !$(this).is(':checked'));
    $(".others_file").val("");
});

function formatNumber (num) {
	return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

$(document).on('click', '.btn-remove-row', function() {
	 $(this).closest('tr').remove();
});
	

function releaseCheck(cv_id) {

		bootbox.confirm({
			title: "Check Releasing",
			size: 'small',
		    message: "Are you sure you want to release this check?",
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn-success'
		        },
		        cancel: {
		            label: 'No',
		            className: 'btn-danger'
		        }
		    },
		    callback: function (result) {
		    	if(result==true){
		    		$('body').addClass('is-loading'); 
					$('#modalDialog').modal('toggle'); 
					document.getElementById("check_releasing_form").submit();
			        return true;
				}
		    }
		});

}

</script>