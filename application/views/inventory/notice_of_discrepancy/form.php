<?php
$attributes = array('id'=>'notice_of_discrepancy_form','role'=>'form');
if(!isset($nod)){
	$title = "Add Notice of Discrepancy";
	$action = 'inventory/notice_of_discrepancy/insert';
	$po_id ='';
	$company_id ='';
	$company_name ='';
	$supplier_id ='';
	$supplier_name ='';
	$delivery_date ='';
	$dr_no ='';
	$plate_no ='';
	$driver ='';
	$reason ='';
	$other_remarks ='';
}else{
	$title = "Edit Notice of Discrepancy";
	$action = 'inventory/notice_of_discrepancy/update/'.$nod->id;
	$po_id =$nod->purchase_order_id;
	$company_id =$nod->company_id;
	$company_name =$nod->company_name;
	$supplier_id =$nod->supplier_id;
	$supplier_name =$nod->supplier_name;
	$delivery_date =$nod->date_of_delivery;
	$dr_no =$nod->delivery_receipt_number;
	$plate_no =$nod->vehicle_plate_number;
	$driver =$nod->name_of_driver;
	//$reason =$nod->reason_of_discrepancy;
	$other_remarks =$nod->other_remarks;
}

?>

<div class='panel panel-primary'>
	<div class='panel-heading'>
		<div class='panel-title'>
			<text><?php echo $title;?></text>
			<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
		</div>
	</div>
</div>

<?php
	echo form_open_multipart(HTTP_PATH.$action,$attributes);
	echo $this->Mmm->createCSRF();
?>

<div class='panel-body panel'>
	<div class='panel-group' id='NDFormDivider' role='tablist' aria-multiselectable='true'>
		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='general'>	
					<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#NDGeneral' aria-expanded='true' aria-controls='NDGeneral'>
					General Information
					<span class='glyphicon glyphicon-chevron-down pull-right'></span>
					</a>
			</div>

			<div id='NDGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='NDGeneral'>

				<div class='panel-body'>
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>PO Transaction Code No.*</label>
						<input type="text" id="po" name="po" class='form-control' value="<?php echo $po_id?>" required <?php if(isset($nod)){ echo 'readonly'; }?>>
						<input type="hidden" id="po_id" name="po_id" class='form-control' value="<?php echo $po_id?>" required>
					</div>
					<div class='col-md-9 col-sm-9 col-xs-12'>
						<label>Company</label>
						<input type="text" id="company" name="company" class='form-control' value="<?php echo $company_name?>" readonly>
						<input type="hidden" id="company_id" name="company_id" class='form-control' value="<?php echo $company_id?>"required>
					</div>
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Delivery Receipt No.*</label>
						<input type="text" id="dr_no" name="dr_no" class='form-control' value="<?php echo $dr_no?>" required>
					</div>
					<div class='col-md-9 col-sm-9 col-xs-12'>
						<label>Supplier</label>
						<input type="text" id="supplier" name="supplier" class='form-control' value="<?php echo $supplier_name?>" readonly>
						<input type="hidden" id="supplier_id" name="supplier_id" class='form-control' value="<?php echo $supplier_id?>" required>
					</div>
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Date of Delivery*</label>
						<input type="date" id="date_delivery" name="date_delivery" class='form-control' value="<?php echo $delivery_date?>" required>
					</div>
					<div class='col-md-6 col-sm-6 col-xs-12'>
						<label>Name of Courier/Driver*</label>
						<input type="text" id="driver" name="driver" class='form-control' value="<?php echo $driver?>" required>
					</div>
					<div class='col-md-3 col-sm-3 col-xs-12'>
						<label>Vehicle Plate No.</label>
						<input type="text" id="plate_no" name="plate_no" class='form-control' value="<?php echo $plate_no?>">
					</div>
					<!--<div class='col-md-6 col-sm-6 col-xs-6'>
						<label>Reason for Discrepancy</label><br>
						<input type="radio" name="reason" class="rd1" value='Under Delivery' <?php if($reason=="Under Delivery"){ echo "checked";}?> <?php if(isset($nod)){ 
								echo "disabled";
							}else{
								echo "checked";
							}
							?>>Under Delivery &nbsp  &nbsp
					 	<input type="radio" name="reason" class="rd2" value='Over Delivery' <?php if($reason=="Over Delivery"){ echo "checked";}?>>Over Delivery &nbsp  &nbsp <br>
						<input type="radio" name="reason" class="rd3" value='Damaged' <?php if($reason=="Damaged"){ echo "checked";}?>>Damaged &nbsp  &nbsp &nbsp  &nbsp &nbsp  &nbsp
						<input type="radio" name="reason" class="rd4" value='Not in conformity with specifications' <?php if($reason=="Not in conformity with specifications"){ echo "checked";}?>>Not in conformity with specifications
					</div>-->
					
				</div>
			</div>
			
		</div>

		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='itemlist'>	
				<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#NDItemlist' aria-expanded='true' aria-controls='NDItemlist'>
				Details
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>
			<div id='NDItemlist' class='panel-collapse collapse' role='tabpanel' aria-labelledby='NDItemlist'>
				<div class='panel-body' >
					<div style="overflow-x: scroll">
						<table data-toggle="table" id="item_list_table" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th style="min-width: 100px;">Item Description</th>
									<th style="min-width: 50px;">UoM</th>
									<th style="min-width: 80px;">Qty per PO</th>
									<th style="min-width: 80px;">Qty per DR</th>
									<th style="min-width: 80px;">Actual Qty Received</th>
									<th style="min-width: 100px;">Reason of Discrepancy</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if(isset($nod_details)){
										foreach($nod_details as $row){
				            				echo "<tr>";
				        					echo "<td><input type='hidden' id='item_id[]' name='item_id[]' value='".$row['item_id']."'>(".$row['item_code'] . ") ". $row['item_description']."</td>";
				        					if($row['packaging']){
				        					}else{
				        						$um = $row['item_unit'];
				        					}
				        					echo "<td>".$row['item_unit']."</td>";
				        					echo "<td><input type='hidden' class='form-control' id='quantity_po[]' name='quantity_po[]' value='".$row['quantity_po']."'>".$row['quantity_po']."</td>";
				        					echo "<td><input type='text'class='form-control'  id='quantity_dr[]' name='quantity_dr[]' value='".$row['quantity_dr']."'></td>";
				        					echo "<td><input type='text' class='form-control' id='quantity_received[]' name='quantity_received[]' value='".$row['quantity_received']."'></td>";

				        					echo "<td><select id='remarks[]' name='remarks[]' class='form-control'><option value='".$row['remarks']."'>".$row['remarks']."<option value='Ok'>Ok</option><option value='Under Delivery'>Under Delivery</option><option value='Over Delivery'>Over Delivery</option><option value='Over Delivery w/ Freebies'>Over Delivery w/ Freebies</option><option value='Damaged'>Damaged</option><option value='Not in cormity with specifications'>Not in conformity w/ spec</option></select></td>";

				            				echo "</tr>";
				            			}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='others'>	
				<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#NDOthers' aria-expanded='true' aria-controls='NDOthers'>
				Other/Remarks
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>
			<div id='NDOthers' class='panel-collapse collapse' role='tabpanel' aria-labelledby='NDOthers'>
				<div class='panel-body' >
					<div class='col-md-12 col-sm-12 col-xs-12'>
						<textarea id="other_remarks" name="other_remarks" class='form-control'><?php echo $other_remarks?></textarea>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<div class='col-xs-12 col-sm-12 col-lg-12'>
	<span class='pull-right'>
		<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: validateForm()' />
		<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
	</span>
</div>
<br><br><br>
</form>

<script type="text/javascript">

	$( "#po" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>inventory/po_data",
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				toastr.clear();
			},
			select: function( event, ui ) {
				$( "#po" ).val( ui.item.label );
				$( "#po_id" ).val( ui.item.value );
				
				var po_id = ui.item.value;
				
				$.ajax({
						type:"POST",
						url:"<?php echo HTTP_PATH;?>/inventory/getPOItems/"+po_id,
						success:function(data){

							var table_data = $.parseJSON(data);

							$('#item_list_table tbody').remove();
							 $.each(table_data, function(idx, elem){

							 	if(elem.packaging==''){
							 		$um = elem.unit;
							 	}else{
							 		$um = elem.packaging;
							 	}

						        $("#item_list_table").append("<tr><td><input type='hidden' id='item_id[]' name='item_id[]' value='"+elem.item_id+"'>("+elem.item_code+") "+elem.item_description+"</td><td>"+$um+"</td><td><input type='hidden' class='form-control' id='quantity_po[]' name='quantity_po[]' value='"+elem.quantity+"'><input type='hidden' class='form-control' id='unit[]' name='unit[]' value='"+elem.unit+"'><input type='hidden' class='form-control' id='packaging[]' name='packaging[]' value='"+elem.packaging+"'><input type='hidden' class='form-control' id='unit_price[]' name='unit_price[]' value='"+elem.unit_price+"'>"+elem.quantity+"</td><td><input type='number' class='form-control' id='quantity_dr[]' name='quantity_dr[]' required></td><td><input type='number' class='form-control' id='quantity_received[]' name='quantity_received[]' required></td><td><select id='remarks[]' name='remarks[]' class='form-control'><option value='Ok'>Ok</option><option value='Under Delivery'>Under Delivery</option><option value='Over Delivery'>Over Delivery</option><option value='Over Delivery w/ Freebies'>Over Delivery w/ Freebies</option><option value='Damaged'>Damaged</option><option value='Not in cormity with specifications'>Not in conformity w/ spec</option></select></td></tr>");
						    });

						}
					});

				$.ajax({
						type:"POST",
						url:"<?php echo HTTP_PATH;?>/inventory/getPOInfo/"+po_id,
						success:function(data){
							var po_info = $.parseJSON(data);
							$( "#company_id" ).val( po_info[0].company_id);
							$( "#company" ).val( po_info[0].company_name );
							$( "#supplier_id" ).val( po_info[0].supplier_id );
							$( "#supplier" ).val( po_info[0].supplier_name );
						}
					});

			}
		});

	$(document).on('click', "#remove_row", function(){
	     $(this).closest("tr").remove();
	}); 

	function reason(type){
		if(type=="Under Delivery"){
		 	$('.rd2').attr('disabled','disabled');
		 	$('.rd3').attr('disabled','disabled');
		 	$('.rd4').attr('disabled','disabled');
		}else if(type=="Over Delivery" || type=="Over Delivery w/ Freebies"){
			$('.rd1').attr('disabled','disabled');
		 	$('.rd3').attr('disabled','disabled');
		 	$('.rd4').attr('disabled','disabled');
		}else if(type=="Damaged"){
			$('.rd1').attr('disabled','disabled');
		 	$('.rd2').attr('disabled','disabled');
		 	$('.rd4').attr('disabled','disabled');
		}else if(type=="Not in conformity with specifications"){
			$('.rd1').attr('disabled','disabled');
		 	$('.rd2').attr('disabled','disabled');
		 	$('.rd3').attr('disabled','disabled');
		}
		$('#reason').val(type);
	}

	function validateForm(){

		var gen_selects = document.getElementById('NDGeneral').querySelectorAll("[required]");

    	var gen_flag=0;
        for(var i = 0; i < gen_selects.length; i++){         
            if (gen_selects[i].value==""){
            	gen_flag=1;
            } 
        }

        if(gen_flag==1){
        	toastr['error']("Please fill-out all required* fields in General Information Tab!", "ABAS says:");
			return false;
        }

    	var item_list = document.getElementById('NDItemlist').getElementsByTagName('input');

    	var item_flag=0;
    	for(var i = 0; i < item_list.length; i++){         
            if (item_list[i].value=="" && item_list[i].required==true){
            	item_flag=1;
            } 
        }

        if(item_flag==1){
        	toastr['error']("Please fill-out all required* fields in Details Tab!", "ABAS says:");
			return false;
        }

        var row_count = $('#item_list_table tr').length;

		if(row_count<=1){
        	toastr['error']("Please add at least one item.", "ABAS says:");
			return false;
        }

        if(row_count>0 && gen_flag==0 && item_flag==0) {

        	$('body').addClass('is-loading'); 
			$('#modalDialog').modal('toggle'); 

			document.getElementById("notice_of_discrepancy_form").submit();
			return true;
		}

	}

</script>