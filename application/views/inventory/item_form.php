<?php
	$item_id				="";
	$item_code				="";
	$asset_code				="";
	$description			="";
	$particular				="";
	$unit					="";
	$unit_cost				="";
	$type                   ="";
	$picture                = LINK.'assets/uploads/inventory/item_images/default.jpg';
	$id						="";
	$qty					=0;
	$category				="";
	$categoryid				="";
	$sub_category			="";
	$sub_categoryid			="";
	$classification			="";
	$classification_name	="";
	$reorder				="";
	$qty					=0;
	$location				=$_SESSION['abas_login']['user_location'];
	$stock_location			="";
	$account_type			="";
	$display				='';

	/*$vessel_options = "<option value=''>Select</option>";
	if(isset($item)){
		foreach($vessels as $vessel) {
			$vessel_options	.=	"<option ".($item[0]['stock_location']==$vessel->id ? "selected":"")." value='".$vessel->id."'>".$vessel->name."</option>";
		}
	}else{
		foreach($vessels as $vessel) {
			$vessel_options	.=	"<option value='".$vessel->id."'>".$vessel->name."</option>";
		}
	}*/


	if(isset($item)){
		$item_id				=$item[0]['id'];
		$item_code				=$item[0]['item_code'];
		$asset_code				=$item[0]['asset_code'];
		$description			=$item[0]['description'];
		$particular				=$item[0]['particular'];
		$unit					=$item[0]['unit'];
		$unit_cost				=$item[0]['unit_price'];
		$id						=$item[0]['id'];
		$type                   =$item[0]['type'];
		if($item[0]['picture']!=NULL){
			$picture				= LINK.'assets/uploads/inventory/item_images/'.$item[0]['picture'];
		}else{
			$picture				= LINK.'assets/uploads/inventory/item_images/default.jpg';
		}
		$account_type			=$item[0]['account_type'];
		$classification			="";
		$classification_name	="";
		$reorder				=$item[0]['reorder_level'];
		$location				=$_SESSION['abas_login']['user_location'];
		$stock_location			=$item[0]['stock_location'];
		$display				='';
		$sql4					="SELECT id, category FROM inventory_category WHERE id	=".$item[0]['category'];
		$r4						=$this->db->query($sql4);
		$chk4					=$r4->result_array();
		$category				=$chk4[0]['category'];
		$categoryid				=$chk4[0]['id'];
		if($item[0]['sub_category']!=0){
			$sql				="SELECT id, category FROM inventory_category WHERE id	=".$item[0]['sub_category'];
			$r					=$this->db->query($sql);
			$chk				=$r->result_array();
			$sub_category		=$chk[0]['category'];
			$sub_categoryid		=$chk[0]['id'];
		}
		$sql3					="SELECT (tayud_qty + nra_qty + mkt_qty + tac_qty) as total_qty FROM `inventory_location` WHERE item_id=$id";
		$r3						=$this->db->query($sql3);
		$c						=$r3->result_array();
		if(isset($c[0]['total_qty'])){
			$total_qty			=$c[0]['total_qty'];

		}
		else{
			$total_qty			=0 ;
		}
		$sqQty					="SELECT * FROM inventory_location WHERE item_id=".$id;
		$r4						=$this->db->query($sqQty);
		$q						=$r4->result_array();
		if($q){
			switch ($location) {
				case 'Makati':
					$qty=$q[0]['mkt_qty'];
				break;
				case 'NRA':
					$qty=$q[0]['nra_qty'];
				break;
				case 'Tayud':
					$qty=$q[0]['tayud_qty'];
				break;
				case 'Tacloban':
					$qty=$q[0]['tac_qty'];
				break;
				case 'Direct Delivery':
					$qty=$q[0]['direct_qty'];
				break;
			}
		}
	}
?>
<script type="text/javascript" src="<?php echo LINK ?>assets/qr_code_maker/qrcode.js"></script>
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
					if(this.value	==	ui.item.label){
						alert('Item already exits!');
					}
					return false;
					}
				});
			});
			$('[data-toggle="popover"]').popover();
		});
		function submitMe(){
			
			var d=document.getElementById('description').value;
			var p=document.getElementById('particular').value;
			var u=document.getElementById('unit').value;
			var c=document.getElementById('category').value;
			var msg="";
			if(d==''){
				msg += 'Please enter description.<br>';
			}
			//if(p==''){
			//	msg += 'Please enter particular.<br>';
			//}
			if(u==''){
				msg += 'Please select unit.<br>';
			}
			if(c==''){
				msg += 'Please select category.<br>';
			}
			
			if(msg!="") {
				toastr["error"](msg,"ABAS Says:");
				return false;
			}else{
				document.forms['itemForm'].submit();
			}

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
<form class="form-horizontal" role="form" id="itemForm" name="itemForm"	action="<?php echo HTTP_PATH.'inventory/addItem'; ?>" method="post" enctype='multipart/form-data'>
<?php echo $this->Mmm->createCSRF() ?>

		<div class="panel panel-primary">
			<div class="panel-heading" role="tab" id="headingOne">
				<strong>Item Information</strong>
				 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
		</div>
			<div class="panel-body" role="tab" >
				<div class='col-sm-6 col-xs-12'>
					<label	for="payee">Picture:</label>
					<img class="center-block img-responsive" style="width:210px; height:150px;" src="<?php echo $picture; ?>" />
					<br>
					<?php if($_SESSION['abas_login']['role']=="Inventory" || $_SESSION['abas_login']['role']=="Purchasing"  || $_SESSION['abas_login']['role']=="Administrator"){ ?>
					<input class="center-block" type="file" name="picture" id="picture">
					<?php } ?>
				</div>
				<?php if($_SESSION['abas_login']['role']=="Inventory" || $_SESSION['abas_login']['role']=="Administrator"){ ?>
					<div class='col-sm-6 col-xs-12'>
						<input class="form-control input-sm" type="hidden" name="item_id" id="item_id" value="<?php echo $item_id;	?>" />
						<label for="item_code">Item Code:</label>
						<input class="form-control input-sm" type="text" name="item_code" id="item_code" value="<?php echo $item_code;	?>" style="text-align:center;font-size: 200%;"/>
					</div>
					<?php if(isset($item)){ ?>
						<div class='col-sm-6 col-xs-12' ">
							<label for="qr_code">QR Code:&nbsp</label>
							<div id="qrcode" style="width:100px; height:100px; margin-top:0px;margin-left:90px;"></div>
							<span class='pull-right'><a href='<?php echo HTTP_PATH.'inventory/print_qr_code/'.$item[0]['id']; ?>' target='_blank' class='btn-xs btn-info exclude-pageload'>Preview</a></span>
						</div>
					<?php } ?>
				<?php } ?>
				<div class='col-sm-12 col-xs-12'>
					<hr>
					<label	for="payee">Item Name:*</label>
					<input class="form-control input-sm" type="text" name="description" id="description" value="<?php echo $description;?>" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>/>
				</div>
				<div class='col-sm-12 col-xs-12'>
					<label	for="particular">Particular (Brand/Make/Model/Color/Size/Serial Part No.):</label>
					<input class="form-control input-sm" type="text" id="particular" name="particular"	value='<?php echo $particular; ?>' <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>/>
				</div>
				<div class='col-sm-6 col-xs-12'>
					<label for="category">Category:*</label>
					<select class="form-control input-sm" name="category" id="category" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
						<option value="<?php echo $categoryid; ?>"><?php echo $category; ?></option>
						<?php foreach($categories as $category){ ?>
							<option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class='col-sm-6 col-xs-12'>
					<label>Type:</label>
					<select class="form-control input-sm" name="type" id="type" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Accounting" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
						<option value=''>Select</option>
						<option value='Non-Capex' <?php if($type=='Non-Capex'){ echo 'selected';} ?>>Non-Capex (For consumables)</option>
						<option value='Capex' <?php if($type=='Capex'){ echo 'selected';} ?>>Capex (For Fixed Asset inclusion)</option>
					</select>
				</div>
				<div class="form-group" style="display:none">
					<label>Sub-Category:</label>
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
					<label>Item Location:</label>
					<select class="form-control input-sm" name="location" id="location">
						<option value="<?php echo $location; ?>"><?php echo $location; ?></option>
						<option value="Tayud">Tayud</option>
						<option value="NRA">NRA</option>
						<option value="Makati">Makati</option>
						<option value="Tacloban">Tacloban</option>
						<option value="Direct Delivery">Direct Delivery</option>
					</select>
				</div>
					<div class='col-sm-6 col-xs-12'>
						<label>Unit:*</label>
						<select class="form-control input-sm" name="unit" id="unit" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
							<option value="<?php echo $unit ?>"><?php echo $unit ?></option>
							<?php foreach($units as $unit) { ?>
								<option value="<?php echo $unit['unit']; ?>"><?php echo $unit['unit']; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class='col-sm-6 col-xs-12'>
						<label>Unit Price:</label>
						<input class="form-control input-sm" type="number" min="0.01" step="0.01" max="5000" name="unit_cost" id="unit_cost" value="<?php echo $unit_cost;?>" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Accounting" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
					</div>
					<div class='col-sm-6 col-xs-12'>
						<label>Initial Qty on Stock:</label>
						<input class="form-control input-sm" type="number" name="qty" id="qty" value="<?php echo $qty;?>"  <?php if($_SESSION['abas_login']['role']=="Inventory" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
					</div>
					<div class='col-sm-6 col-xs-12'>
						<label>Reorder Level:</label>
						<input class="form-control input-sm" type="number" name="reorder" id="reorder" value="<?php echo $reorder; ?>" <?php if($_SESSION['abas_login']['role']=="Inventory" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
					</div>
					<div class='col-sm-8 col-xs-8'>
						<label>Stock Location (Rack/Shelf/Storage Room No.):</label>
						<!--<select class="form-control input-sm" name="stock_location" id="stock_location" <?php //if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Accounting" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
							<?php //echo $vessel_options;?>
						</select>-->
						<input class="form-control input-sm" type="text" name="stock_location" id="stock_location" value="<?php echo $stock_location; ?>" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Accounting" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
					</div>
					<div class='col-sm-4 col-xs-4'>
						<label>Status:</label>
						<select class="form-control input-sm" name="stat" id="stat" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Accounting" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
							<option value='1' <?php (isset($item) && $item[0]['stat']==1)?'selected':''?>>Active</option>
							<option value='0' <?php (isset($item) && $item[0]['stat']==0)?'selected':''?>>Inactive</option>
						</select>
					</div>
			</div>
			<div class='modal-footer'>
				<span class='pull-right'>
					<?php if($this->Abas->checkPermissions("inventory|edit_item",false)): ?>
					<input class="btn btn-success" type="button" value="Save" onclick="javascript:submitMe()" id="submitbtn">
					<?php endif; ?>
					<input class="btn btn-danger" type="button" value="Cancel" data-dismiss="modal">
				</span>
			</div>
	</div>
			

<input type="hidden" id="fromModule" name="fromModule" value="<?php echo $_SERVER['HTTP_REFERER'] ?>" />
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
<input	type="hidden" name="qty2" id="qty2" value="<?php echo $qty; ?>" />
</form>
<script type="text/javascript">

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
	function validateRadio (radios)	{
		for (var i=0; i < radios.length; i++) {
			if (radios[i].checked) {return true;}
		}
		return false;
	}
	var changed=false;
	$(".status-change-input").blur(function() {
		var effdate=$("#change_effective_date");
		var revdate=$("#status_review_date");
		var remark=$("#status_remarks");
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


	var qrcode = new QRCode(document.getElementById("qrcode"), {
		width : 100,
		height : 100
	});

	function makeCode () {		
		var item_id = document.getElementById("item_id");
		var item_code = document.getElementById("item_code");
		var item_unit = document.getElementById("unit");
		var item_price = document.getElementById("unit_cost");
		
		if(!item_code.value || item_code.value==0) {
			return;
		}

		if(!item_unit.value){
			return;
		}

		qrcode.makeCode(item_id.value+","+item_price.value);
	}

	makeCode();

	$("#item_code").
		on("blur", function () {
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			makeCode();
		}
	});

	$("#unit").
		on("blur", function () {
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			makeCode();
		}
	});

	$("#unit_cost").
		on("blur", function () {
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			makeCode();
		}
	});

</script>