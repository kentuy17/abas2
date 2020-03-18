<?php 
if(isset($asset)){
	$asset_id               =$asset->id;
	$item_id				=$asset->item_id;
	$item_name				=$item[0]['item_name'];
	$asset_name             =$item_name;
	$asset_code             =$asset->asset_code;
	$control_number         =$asset->control_number;
	$department_name 		= $department->name;
	$description			=$asset->description;
	$particular				=$item[0]['particular'];
	$date_acquired          =$asset->date_acquired;
	$ctr=1;
}
?>
<h2>QR Code</h2>
<h4>For Asset: 
<?php
	echo $item[0]['item_name'].", ".$item[0]['particular']. " (".$asset->asset_code.")";
?>
</h4>
<style type="text/css">
	.trx { font-size:130%;vertical-align:middle;text-align:center;font-weight:bold;color:#ffffff; }
	.trp { text-align:right;font-weight:bold;color:#000000 }
	.tre { text-align:left;font-style:italic;color:#000000 }
</style>
	<input type="hidden" name="item_id" id="item_id"  value='<?php echo $item[0]['id']?>' readonly/>
	<input type="hidden" name="item_code" id="item_code"  value='<?php echo $item[0]['item_code']?>' readonly/>
	<input  type="hidden" name="unit" id="unit" value='<?php echo $item[0]['unit']?>' readonly/>
	<input  type="hidden" name="unit_cost" id="unit_cost" value='<?php echo $item[0]['unit_price']?>' readonly/>
	<div class="panel panel-success">
		<div class="panel panel-heading">
			<div class='pull-right'>
				<button id="printMe" type="button" class="btn btn-sm btn-info" onClick=' $("#asset_tag").print(/*options*/);'> Print </button> 
			</div>
			<p style="font-size:12px">
				Note: You may need to set the correct paper size before printing, you can set paper size in the "More settings" in the print window .
			</p>
		</div>
		<center>
		<div id="asset_tag" class="panel panel-body" style='width:100%;height:100%'>
			<div class='col-sm-12 col-xs-12 col-md-12 col-lg-12'>
					<table border='1' cellspacing="10" >
						<?php for($x=1;$x<=4;$x++){ ?>
						<tr>
							<td  valign="middle">
								<span class="pull-left"><img src="<?php echo PDF_LINK .'assets/images/AvegaLogo.jpg'?>" width="100px" height="50px"></span>
							</td>
							<td colspan="2" style="background-color:#5d5d5d;color:#FFFFFF">
								<center><label name="asset_code_tag" id="asset_code_tag" class="trx"><?php echo $asset_code ."-".$control_number;?></label></center>
								<input type="hidden" name="asset_code" id="asset_code" value="<?php echo $asset_code?>"> 
								<input type="hidden" name="control_number" id="control_number" value="<?php echo $control_number?>">
								<input type="hidden" name="asset_id" id="asset_id" value="<?php echo $asset_id?>">
								<input type="hidden" name="item_id" id="item_id" value="<?php echo $item_id?>">
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
								<?php 
									echo '<div id="qrcode'.$ctr.'" style="width:100px; height:100px;vertical-align: middle;margin-left: 20px;margin-right: 20px;margin-top: 20px;;margin-bottom: 20px"></div>';
								?>
								
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
						<?php $ctr++; } ?>
					</table>
			</div>
		</div>
	</center>
	</div>
<script type="text/javascript" src="<?php echo LINK ?>assets/qr_code_maker/qrcode.js"></script>
<script type="text/javascript">
	var qrcode = new Array();
	for(x=1;x<=4;x++){
	qrcode[x] = new QRCode(document.getElementById("qrcode"+x), {
		width : 100,
		height : 100
	});
	var item_id = document.getElementById("item_id");
	var asset_id = document.getElementById("asset_id");
	var asset_code = document.getElementById("asset_code");
	var control_number = document.getElementById("control_number");
	
	qrcode[x].makeCode(asset_id.value+","+item_id.value+","+asset_code.value+"-"+control_number.value);
	}
</script>