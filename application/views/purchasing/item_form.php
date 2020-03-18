

<?php
	var_dump($this->uri->segment(3));	
	//var_dump($item);exit;  //ssi1@globe.com.ph, noli - 09178707140
	$item_code = "";
	$description = "";
	$particular = "";
	$unit = "";
	$unit_cost = "";
	$id = "";
	
	$category = "";	
	$categoryid = "";
	$sub_category = "";
	$sub_categoryid= "";
	
	$classification = "";
	$classification_name = "";
	$reorder = "";
	$qty = 0;
	$location = ""; 
	$stock_location = "";
	$account_type =  "";
	
	$display = '';
	
	
	if(isset($item)){
		//var_dump($item[0]['id']);exit;
		$item_code = $item[0]['item_code'];
		$description = $item[0]['description'];
		$particular = $item[0]['particular'];
		$unit = $item[0]['unit'];
		$unit_cost = $item[0]['unit_price'];
		$id = $item[0]['id'];
		$account_type = $item[0]['account_type'];
		$classification = "";
		$classification_name = "";
		$reorder = $item[0]['reorder_level'];
		
		$location = $item[0]['location'];
		$stock_location = $item[0]['stock_location'];
		$display = 'disabled="disabled"';
		
		//get category
		$sql4 = "SELECT id, category FROM inventory_category WHERE id =".$item[0]['category'];		
		$r4 = $this->db->query($sql4);
		$chk4 = $r4->result_array();
		$category = $chk4[0]['category'];
		$categoryid = $chk4[0]['id'];
		
		//get subcategory
		
		if($item[0]['sub_category'] != 0){
			$sql = "SELECT id, category FROM inventory_category WHERE id =".$item[0]['sub_category'];		
			//var_dump($sql); exit;
			$r = $this->db->query($sql);
			$chk = $r->result_array();
			$sub_category = $chk[0]['category'];
			$sub_categoryid = $chk[0]['id'];
		}
		//get total qty;		
		$sql3 = "SELECT (tayud_qty + nra_qty + mkt_qty + tac_qty) as total FROM `inventory_location` WHERE item_id = $id";		
		$r3 = $this->db->query($sql3);
		$chk = $r3->result_array();
		$qty = $chk[0]['total'];					
	}
	
?>

<script type="text/javascript">
	 
		$(document).ready(function () {
		
				$(function() {				
			 
					$( "#description" ).autocomplete({
					  source: "index.php/inventory/itemData",
					  minLength: 2,
					  focus: function( event, ui ) {
						$( "#description" ).val( ui.item.label );
						return false;
						},
					  select: function( event, ui ) {
						if(this.value == ui.item.label){
							alert('Item already exits!');
						}
						
						return false;
					  }
					});
				  });
				  
				  $('[data-toggle="popover"]').popover();    
				  	
		});
		
		
		function submitMe(){

		var id = document.getElementById('id').value;
		var i = document.getElementById('item_code').value;
		var d = document.getElementById('description').value;
		var p = document.getElementById('particular').value;
		var u = document.getElementById('unit').value;
		var uc = document.getElementById('unit_cost').value;
		var q = document.getElementById('qty').value;


		//if(id !== ''){

			/*
			alert('Editing is not allowed');
			return false;
		}else{*/

			if(i == ''){
				alert('Please enter Item Code');
				document.getElementById('item_code').focus();
				return false;
			}else if(d == ''){
				alert('Please enter Description');
				document.getElementById('description').focus();
				return false;
			}else if(u == ''){
				alert('Please select unit');
				document.getElementById('unit').focus();
				return false;
			}else if(q == ''){
				alert('Please enter quantity on hand');
				document.getElementById('qty').focus();
				return false;
			}else{
				document.forms['itemForm'].submit();
			}

		//}


	}
		
		
</script>		
<style>
#changeStatus {
	background-color: #DDD;
	float: left;
	position: absolute;
}
#changeStatus .form-group {
    margin: 15px;
}
</style>


<form class="form-horizontal" role="form" id="itemForm" name="itemForm"  action="<?php echo HTTP_PATH.'inventory/addItem'; ?>" method="post" enctype='multipart/form-data'>
								<?php echo $this->Mmm->createCSRF() ?>
								<div style="float:left; margin-left:0px; margin-top:0px">
									<div class="container">
										<div class="panel panel-primary" style="font-size:12px; ">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>Item Information&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab" >
												<div style="width:200px; margin-left:20px; float:left">
													<div class="form-group">
														<label for="voucher_no">Item Code:</label>
														<div>
															<input class="form-control input-sm" type="text" name="item_code" id="item_code" value="<?php echo $item_code;  ?>" />
														</div>
													</div>
													<div class="form-group">
														<label  for="payee">Description:</label>
														<div>
															
                                                            <input class="form-control input-sm" type="text" name="description" id="description" value="<?php echo $description;  ?>" />
														</div>
													</div>
													<div class="form-group">
														<label  for="particular">Particular:</label>
														<div>
															<input class="form-control input-sm" type="text" id="particular" name="particular"  value="<?php echo $particular; ?>"/>
														</div>
													</div>
													<div class="form-group">
														<label for="category">Category:                                                        <span class="glyphicon glyphicon-plus-sign" title="Add Category"></span></label>
														<div>
															<select class="form-control input-sm" name="category" id="category">
																<option value="<?php echo $categoryid; ?>"><?php echo $category; ?></option>
																<?php foreach($categories as $category){ ?>
                                                                <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                                                                <?php } ?>
															</select>
                                                            
														</div>
													</div>		
                                                    <div class="form-group">
														<label  for="subcategory">Sub-Category:</label>
														<div id="subcategory">
															<select class="form-control input-sm" name="sub_category" id="sub_category">
																<option value="<?php echo $sub_categoryid; ?>"><?php echo $sub_category; ?></option>
																<?php foreach($sub_categories as $sub_category){ ?>
                                                                <option value="<?php echo $sub_category['id']; ?>"><?php echo $sub_category['category']; ?></option>
                                                                <?php } ?>
															</select>
														</div>
													</div>
                                                    		
													<div class="form-group" style="display:none">
														<label  for="amount">Item Location:</label>
														<div>
															<select class="form-control input-sm" name="location" id="location">
																<option value="<?php echo $location; ?>"><?php echo $location; ?></option>
																<option value="Tayud">Tayud</option>
                                                                <option value="NRA">NRA</option>
                                                                <option value="Makati">Makati</option>
                                                                <option value="Tacloban">Tacloban</option>
                                                                <option value="Direct Delivery">Direct Delivery</option>                                                               
															</select>
														</div>
													</div>
													
													<div class="form-group">
														<!--
                                                        <label for="classification">Classification:</label>
														<div>
															<select class="form-control input-sm" name="classification" id="classification">
																<option value="<?php echo $classification; ?>"><?php echo $classification_name; ?></option>
																<?php foreach($classifications as $classification){ ?>
																	<option value="<?php echo $classification['id'] ?>"><?php echo $classification['name']; ?></option>
																<?php } ?>
															</select>
														</div>
                                                        -->
													</div>
												</div>
												<div style="width:200px; margin-left:70px; float:left">
													
                                                    <div class="form-group">
														<label  for="amount">Unit: <i class="glyphicon glyphicon-plus-sign" style="color:#009900" title="Add unit"></i></label>
														<div>
															<select class="form-control input-sm" name="unit" id="unit">
																<option value="<?php echo $unit ?>"><?php echo $unit ?></option>
																<?php foreach($units as $unit) { ?>
                                                                <option value="<?php echo $unit['unit']; ?>"><?php echo $unit['unit']; ?></option>
                                                                <?php } ?>
                                                                
															</select>
														</div>
													</div>
                                                    <div class="form-group">
														<label  for="amount">Unit Price:</label>
														<div>
															<input class="form-control input-sm" type="number"  min="0.01" step="0.01" max="2500" name="unit_cost" id="unit_cost" value="<?php echo $unit_cost; ?>" />
														</div>
													</div>
                                                    <div class="form-group">
														<label  for="amount">Qty on Hand:</label>
														<div>
															<input class="form-control input-sm" type="number" name="qty" id="qty" value="<?php echo $qty; ?>" <?php echo $display; ?> />
                                                            
                                                            
														</div>
													</div>
                                                    <div class="form-group">
														<label  for="amount">Reorder Level:</label>
														<div>
															<input class="form-control input-sm" type="number" name="reorder" id="reorder" value="<?php echo $reorder; ?>" />
														</div>
													</div>
                                                    <div class="form-group">
														<label  for="amount">Stock Location (rack location):</label>
														<div>
															<input class="form-control input-sm" type="text" name="stock_location" id="stock_location" value="<?php echo $stock_location; ?>" />
														</div>
													</div>
                                                    <div class="form-group">
														<div class="checkbox">
                                                         &nbsp;
														 <?php 
															$disc = '';
															if( $discontinued = 1){
																$disc = 'checked="checked"';
															}
														 ?>
                                                          <!--
                                                          <label><input  type="checkbox" name="discontinued" id="discontinued" <?php echo $disc; ?> value="<?php echo $discontinued; ?>"  /> Discontinued</label>
                                                          -->
                                                        </div>
                                                        
                                                        
													</div>
												</div>

												<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                                <input  type="hidden"  name="qty2" id="qty2" value="<?php echo $qty; ?>" />
												<span style="float:left; margin-left:2px; margin-top:-10px">
													<?php if($this->Abas->checkPermissions("inventory|create",false)): ?>
                                                    <input class="btn btn-success btn-sm" type="button"  value="Save" onclick="javascript:submitMe()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">
                                                    <?php endif; ?>
													<input class="btn btn-default btn-sm"  type="button" value="Cancel" onclick="javascript:newEntry()" style="width:100px; margin-left:10px; margin-top:10px">
												</span>

											</div>
										</div>
									</div>
								</form>

<!-- modal --->

<!-- Category add form -->






<script>
	
	$(function() {
    function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }
 
    $( "#description" ).autocomplete({
		  source: "/inventory/itemData/",
		  minLength: 2,
		  select: function( event, ui ) {
			log( ui.item ?
			  "Selected: " + ui.item.value + " aka " + ui.item.id :
			  "Nothing selected, input was " + this.value );
		  }
		});
	  });
	
	function validateEmail(email) {
		var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function validateRadio (radios)	{
		for (var i = 0; i < radios.length; i++)	{
			if (radios[i].checked) {return true;}
		}
		return false;
	}

	var changed = false;
	
	$(".status-change-input").blur(function() {
		var effdate	=	$("#change_effective_date");
		var revdate	=	$("#status_review_date");
		var remark	=	$("#status_remarks");
		/*
		alert(
			"Remark:"+remark.val()+"\n"+
			"Effdate:"+effdate.val()+"\n"+
			"Revdate:"+revdate.val()+"\n"
		);
		//*/
		if(remark.val()!=null && effdate.val()!=null && remark.val()!="" && effdate.val()!="") {
			toastr['success']("Form filled up!", "ABAS says");
			$("#changeStatus").addClass("hide");
			$("#statusDetails").removeClass("hide");
			return true;
		}
		else {
			return false;
		}
	});
	$( "#statusDetails" ).hover(
		function() {
			$("#changeStatus").removeClass( "hide" );
		}, function() {
			$( "#changeStatus" ).addClass( "hide" );
		}
	);

</script>
