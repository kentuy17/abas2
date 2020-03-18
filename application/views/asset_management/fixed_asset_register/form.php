<?php
	$asset_id				="0";
	$asset_name  			="";
	$asset_code             ="";
	$department_name 		= "";
	$item_id				="";
	$item_name				="";
	$control_number         ="";
	$category_id            ="";
	$category_name          ="";
	$category_code          ="";
	$description            ="";
	$asset_code				="";
	$particular				="";
	$date_acquired          ="";
	$useful_life			=0;
	$unit					="";
	$include_lapsing  		=0;
	$unit_price				="";
	$picture                = LINK.'assets/uploads/inventory/item_images/default.jpg';
	$company                ="";
	$company_name           ="";
	$company_id             ="";
	$disabled 				="";

	$title = "Add Fixed Asset";
	$action = HTTP_PATH."Asset_Management/fixed_asset_register/insert";

	if(isset($asset)){

		$asset_id               =$asset->id;
		$item_id				=$asset->item_id;
		//$item_name				=$item[0]['item_name'];
		$asset_name             =$asset->item_name;
		$particular      		=$asset->particular;
		//$particular				=$item[0]['particular'];
		$asset_code             =$asset->asset_code;
		$control_number         =$asset->control_number;
		$department_name 		= $department->name;
		$category_id            =$asset->category_id;
		$category_name          =$category->category;
		$category_code          =$category->code;
		$description			=$asset->description;
		
		$date_acquired          =$asset->date_acquired;
		$unit					=$item[0]['unit'];
		$unit_price				=$asset->purchase_cost;
		$useful_life			=$asset->useful_life;
		$include_lapsing  		=$asset->include_lapsing;
		$status					=$asset->status;
		$company				= $this->Abas->getCompany($asset->company_id);
		$company_name			= $company->name;
		$company_id             = $asset->company_id;
		if($status!='Uassigned'){
		   $disabled               = "readonly";
	    }

	    if($item[0]['picture']!=''){
			$picture                = LINK.'assets/uploads/inventory/item_images/'.$item[0]['picture'];
		}else{
			if($asset->item_id==0){
				if($asset->picture!=''){
					$picture         = LINK.'assets/uploads/asset_management/asset_images/'.$asset->picture;
				}else{
					$picture         = LINK.'assets/uploads/inventory/item_images/default.jpg';
				}
			}else{
					$picture         = LINK.'assets/uploads/inventory/item_images/default.jpg';
			}
		}

		$title = "Edit Fixed Asset";
		$action = HTTP_PATH."Asset_Management/fixed_asset_register/update/".$asset_id  ;

	}

	$company_options = "<option value=''>Select</option>";
	if(!empty($companies)) {
		foreach($companies as $option) {
			if(isset($asset)){
					$company_options	.=	"<option ".($asset->company_id==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
			}
			else{
				$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
		unset($option);
	}

	$department_options = "<option value=''>Select</option>";
	if(!empty($departments)) {
		foreach($departments as $option) {
			if(isset($asset)){
					$department_options	.=	"<option ".($asset->department_id==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
			}
			else{
				$department_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
		unset($option);
	}

	$unit_options = "<option value=''>Select</option>";
	if(!empty($units)) {
		foreach($units as $option) {
			if(isset($asset)){
					$unit_options	.=	"<option ".($asset->unit==$option->unit ? "selected":"")." value='".$option->unit."'>".$option->unit."</option>";
			}
			else{
				$unit_options	.=	"<option value='".$option->unit."'>".$option->unit."</option>";
			}
		}
		unset($option);
	}

	$category_options = "<option value=''>Select</option>";
	if(!empty($categories)) {
		foreach($categories as $option) {
			if(isset($asset)){
					$category_options	.=	"<option ".($asset->category_id==$option->id ? "selected":"")." value='".$option->id."'>".$option->category."</option>";
			}
			else{
				$category_options	.=	"<option value='".$option->id."'>".$option->category."</option>";
			}
		}
		unset($option);
	}

	$location_options = "<option value=''>Select</option>";
	if(!empty($locations)) {
		foreach($locations as $option) {
			if(isset($asset)){
					$location_options	.=	"<option ".($asset->location==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
			}
			else{
				$location_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
		unset($option);
	}
?>
<style type="text/css">
	.trx { font-size:130%;vertical-align:middle;text-align:center;font-weight:bold;color:#ffffff; }
	.trp { text-align:right;font-weight:bold;color:#000000 }
	.tre { text-align:left;font-style:italic;color:#000000 }
</style>
<form class="form-horizontal" role="form" id="assetForm" name="assetForm" action="<?php echo $action; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF() ?>
		<div class="panel panel-primary">
			<div class="panel-heading" role="tab" id="headingOne">
				<strong><?php echo $title;?></strong>
				 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
		</div>
		<div class="panel-body" role="tab">
			<div class='col-sm-5 col-xs-12'>
				<label	for="payee">Picture:</label>
				<img class="center-block img-responsive" id='picture' name='picture' style="width:210px; height:150px;" src="<?php echo $picture; ?>" /><br>
				<?php if(isset($asset) && $asset->item_id==0){?>
					<input class="center-block" type="file" name="picture" id="picture">
				<?php } ?>
			</div>
			<div class='col-sm-7 col-xs-12'>
				<label>Asset Tag:</label>
				<?php if(isset($asset)){ ?>
					<a href='<?php echo HTTP_PATH."Asset_Management/fixed_asset_register/print/".$asset->id?>' class='btn-info btn-sm' target='_blank'>Preview</a>
				<?php } ?>
				<div id="asset_tag" style=" overflow-x: auto" ><br>
					<table border='1' cellpadding="30">
						<tr>
							<td  valign="middle">
								<span class="pull-left"><img src="<?php echo PDF_LINK .'assets/images/AvegaLogo.jpg'?>" width="93px" height="50px"></span>
							</td>
							<td colspan="2" style="background-color:#5d5d5d;color:#FFFFFF">
								<center><label name="asset_code_tag" id="asset_code_tag" class="trx"><?php echo $asset_code ."-".$control_number;?></label></center>
								<input type="hidden" name="asset_code" id="asset_code" value="<?php echo $asset_code?>"> 
								<input type="hidden" name="control_number" id="control_number" value="<?php echo $control_number?>">
								<input type="hidden" name="asset_id" id="asset_id" value="<?php echo $asset_id?>">
							</td>
						</tr>
						<tr>
							<td class='trp'>
								Asset Name: &nbsp
							</td>
							<td class='tre'>
								 &nbsp&nbsp<label name="asset_name_tag" id="asset_name_tag" class="tre"><?php echo $asset_name;?></label>
							</td>
							<td rowspan="4">
								<br>
								<div id="qrcode" style="width:100px; height:100px;vertical-align: middle;margin-left: 20px;margin-right: 20px"></div>
								<br>
							</td>
						</tr>
						<tr>
							<td class='trp'>
								Particular: &nbsp
							</td>
							<td class='tre'>
								 &nbsp&nbsp<label name="particular_tag" id="particular_tag" class="tre"><?php echo $particular;?></label>
							</td>
						</tr>
						<tr>
							<td class='trp'>
								Date Acquired: &nbsp
							</td>
							<td class='tre'>
								&nbsp&nbsp<label name="date_acquired_tag" id="date_acquired_tag" class="tre"><?php echo $date_acquired;?></label>
							</td>
						</tr>
						<tr>
							<td class='trp'>
								Department: &nbsp
							</td>
							<td class='tre'>
								&nbsp&nbsp<label name="department_tag" id="department_tag" class="tre"><?php echo $department_name;?>&nbsp&nbsp</label>
							</td>
						</tr>
					</table>
				</div>
				<br>
				<br>
			</div>
		<div  id='form_inputs'>
			<?php 
				if(isset($asset) && $asset->status=='Assigned'){
					echo '<div class="col-sm-12 col-xs-12"><div class="alert alert-success alertfade in" role="alert">
		                    <strong>Editing is not allowed.</strong><br>
		                    This asset is currently assigned to '.$accountable_to.'
		                  </div></div>';
				}
			?>
			<div class='ol-sm-12 col-xs-12'>
				<hr>
				<label>Company*:</label>
				<select id='company' name='company' class='form-control input-sm' <?php if(isset($asset) && $asset->status!='Unassigned' ){ echo 'readonly';}?> required>
					<?php echo $company_options; ?>
				</select>
			</div>
			<div class='col-sm-12 col-xs-12'>
				<label>Item Name*:</label>
				<input class="form-control input-sm" type="text" name="item_name" id="item_name" value="<?php echo $asset_name;?>" <?php if(isset($asset) && $asset->status!='Unassigned' ){ echo 'readonly';}?> required>
				<input class="form-control input-sm" type="hidden" name="item_id" id="item_id" value="<?php echo $item_id;?>">
			</div>
			<div class='col-sm-12 col-xs-12'>
				<label>Particular (Brand/Make/Model/Color/Size/Serial Part No.)*:</label>
				<input class="form-control input-sm" type="text" id="particular" name="particular" value='<?php echo $particular;?>' <?php if(isset($asset) && $asset->status!='Unassigned' ){ echo 'readonly';}?> required/>
			</div>
			<div class='col-sm-3 col-xs-12'>
				<label>Unit*:</label>
				<select id='unit' name='unit' class='form-control input-sm' <?php if(isset($asset) && $asset->status!='Unassigned' ){ echo 'readonly';}?> required>
					<?php echo $unit_options; ?>
				</select>

			</div>
			<div class='col-sm-3 col-xs-12'>
				<label>Unit Price*:</label>
				<input class="form-control input-sm" type="number" id="unit_price" name="unit_price" value='<?php echo $unit_price;?>' <?php if(isset($asset) && $asset->status!='Unassigned' ){ echo 'readonly';}?> required/>
			</div>
			<div class='col-sm-4 col-xs-12'>
				<label>Category*:</label>
				<select id='category' name='category' class='form-control input-sm' <?php if(isset($asset) && $asset->status!='Unassigned' ){ echo 'readonly';}?> required>
					<?php echo $category_options; ?>
				</select>
			</div>
			<div class='col-sm-2 col-xs-12'>
				<label>Category Code:</label>
				<input type='text' id='category_code' name='category_code' value='<?php echo $category_code?>' class='form-control input-sm' readonly>
			</div>
			<div class='col-sm-6 col-xs-12'>
				<label>Location*:</label>
				<select id='location' name='location' class='form-control input-sm' <?php if(isset($asset) && $asset->status!='Unassigned' ){ echo 'readonly';}?> required>
					<?php echo $location_options; ?>
				</select>
			</div>
			<div class='col-sm-6 col-xs-12'>
				<label>Department*:</label>
				<select id='department' name='department' class='form-control input-sm' <?php if(isset($asset) && $asset->status!='Unassigned'){ echo 'readonly';}?> required>
					<?php echo $department_options; ?>
				</select>
			</div>
			<div class='col-sm-4 col-xs-12'>
				<label>Date Acquired*:</label>
				<input class="form-control input-sm" type="date" id="date_acquired" name="date_acquired" value='<?php echo $date_acquired?>' <?php if(isset($asset) && $asset->status!='Unassigned'){ echo 'readonly';}?> required/>
			</div>
			<div class='col-sm-4 col-xs-12'>
				<label>Useful Life*:</label>
				<input class="form-control input-sm" type="number" id="useful_life" name="useful_life" value='<?php echo $useful_life;?>' <?php if(isset($asset) && $asset->status!='Unassigned'){ echo 'readonly';}?> required/>
			</div>
			<div class='col-sm-4 col-xs-12'>
				<label>Include in Lapsing Schedule?:</label>
				<input class="form-control input-sm" type="checkbox" id="include_lapsing" name="include_lapsing" value='1' <?php if($include_lapsing==1){ echo 'checked';}?> <?php if(isset($asset) && $asset->status!='Unassigned'){ echo 'disabled';}?> required/>
			</div>
			<div class='col-sm-12 col-xs-12'>
				<label>Description/Remarks:</label>
				<textarea class="form-control input-sm" id="description" name="description" <?php if(isset($asset) && $asset->status!='Unassigned'){ echo 'readonly';}?>><?php echo $description;?></textarea>
			</div>
		</div>
	</div>
		<div class="modal-footer">
			<?php if($this->Abas->checkPermissions("asset_management|add_fixed_asset_register",false)): ?>
				<?php if(!isset($asset)){ ?>
					<input class="btn btn-success" type="button" value="Save" onclick="javascript:submitMe()" id="submitbtn">
					<input class="btn btn-danger" type="button" value="Discard" data-dismiss="modal">
				<?php 
					}elseif($asset->status=='Unassigned'){ ?>
					<input class="btn btn-success" type="button" value="Save" onclick="javascript:submitMe()" id="submitbtn">
					<input class="btn btn-danger" type="button" value="Discard" data-dismiss="modal">
				<?php }elseif($asset->status=='Disposed' || $asset->status=='Assigned' || $asset->status=='Loss/Damaged'){ ?>
					<input class="btn btn-danger" type="button" value="Close" data-dismiss="modal">
				<?php } ?>
			<?php endif; ?>
			
		</div>
</form>
<script type="text/javascript" src="<?php echo LINK ?>assets/qr_code_maker/qrcode.js"></script>
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

	$('#location').change(function() 
	{   
	  var vessel_id = document.getElementById('location').value;
	  if(vessel_id!=""){
		  $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH;?>/home/get_company_name/"+vessel_id,
		     success:function(data){
		        var c = $.parseJSON(data);
		        $('#company').val(c.company_id);
		     } 	
		  });
	  }
	});	



	$('#category').change(function() 
	{   
	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>Asset_Management/get_category_code/"+$(this).val(),
	     success:function(data){
	        var category = data; 
	        $("#category_code").val(category.code);
	    
	     }
	  });

    }); 
	
	function preview_asset_tag(){
		var asset_name = $('#item_name').val();
		var asset_particular= $('#particular').val();
		var asset_date_acquired= $('#date_acquired').val();
		var asset_department= $('#department option:selected').text();
		var asset_company = $('#company option:selected').text();
		var asset_location = $('#location option:selected').text();

		if(asset_location.substring(0, asset_location.indexOf('('))!=''){
			asset_location =  asset_location.substring(0, asset_location.indexOf('(') + ''.length);
			asset_location =  asset_location.trim();
		}

		var asset_category_code = $('#category_code').val();
		var matches = asset_company.match(/\b(\w)/g);
		var acronym = matches.join(''); 
		var asset_code1 = acronym+"-"+asset_location+"-"+asset_category_code+"-"+"0000";
		var asset_code2 = acronym+"-"+asset_location+"-"+asset_category_code;
		$("#asset_code_tag").text(asset_code1.toUpperCase());
		$("#asset_code").val(asset_code2.toUpperCase());
		$("#asset_name_tag").text(asset_name);
		$("#particular_tag").text(asset_particular);
		$("#date_acquired_tag").text(asset_date_acquired);
		$("#department_tag").text(asset_department);
	}

	var qrcode = new QRCode(document.getElementById("qrcode"), {
		width : 100,
		height : 100
	});

	function makeCode () {
		var item_id = document.getElementById("item_id");
		var asset_id = document.getElementById("asset_id");
		var asset_code = document.getElementById("asset_code");
		var control_number = document.getElementById("control_number");
		if(!asset_code.value || asset_code.value==0) {
			return;
		}
		qrcode.makeCode(asset_id.value+","+item_id.value+","+asset_code.value+"-"+control_number.value);
	}

	makeCode();

	$("#useful_life").
		on("blur", function () {
		preview_asset_tag();
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			preview_asset_tag();
			makeCode();
			
		}
	});

	$("#category").
		on("blur", function () {
		preview_asset_tag();
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			preview_asset_tag();
			makeCode();
			
		}
	});

	$("#location").
		on("blur", function () {
		preview_asset_tag();
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			preview_asset_tag();
			makeCode();
			
		}
	});

	$("#department").
		on("blur", function () {
		preview_asset_tag();
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			preview_asset_tag();
			makeCode();
			
		}
	});

	$("#date_acquired").
		on("blur", function () {
		preview_asset_tag();
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			preview_asset_tag();
			makeCode();
			
		}
	});

	$(document).ready(function () {
		$( "#item_name" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>Asset_Management/autocomplete_capex",
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				if (ui.content.length === 0) {
					toastr.clear();
					toastr["warning"]("Item not found!", "ABAS Says");
				}
				else {
					toastr.clear();
				}
			},
			select: function( event, ui ) {
				$( this ).val( ui.item.item_name );
				$("#item_id").val( ui.item.value );
				$("#particular").val( ui.item.particular );
				$("#unit").val( ui.item.unit );
				$("#unit_price").val( ui.item.unit_price );
				$("#category").val( ui.item.category_id );
				$("#category_code").val( ui.item.category_code );
				var pic_link = "<?php echo LINK.'assets/uploads/inventory/item_images/'?>";
				$("#picture").attr('src',pic_link+ui.item.picture)
				return false;
			}
		});
	});
	function submitMe(){
		
		var item_name=document.getElementById('item_name').value;
		var particular=document.getElementById('particular').value;
		var loc=document.getElementById('location').value;
		var dep=document.getElementById('department').value;
		var da=document.getElementById('date_acquired').value;
		var ul=document.getElementById('useful_life').value;
		var cat=document.getElementById('category_code').value;
		var asset_code=document.getElementById('asset_code').value;

		var form_inputs = $('#form_inputs').find('input').filter('[required]');
		var form_selects = document.getElementById('form_inputs').getElementsByTagName('select');
		var summary_flag=0;

		for(var x = 0; x < form_inputs.length; x++){
        	if (form_inputs[x].value==""){
            	summary_flag=1;
            }
        }

        for(var y = 0; y < form_selects.length; y++){
        	if (form_selects[y].value==""){
            	summary_flag=1;
            }
        }
	
		if(summary_flag==1) {
			toastr["error"]("Please complete all required fields (*).<br>","ABAS Says:");
			return false;
		}else{
			document.forms['assetForm'].submit();
		}

	}
</script>