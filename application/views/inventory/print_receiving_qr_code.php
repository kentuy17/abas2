<!DOCTYPE html>
<html>
<head>
	<title>AVEGA Business Automation System</title>
	<script type="text/javascript" src="<?php echo LINK ?>assets/qr_code_maker/qrcode.js"></script>
	<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap/css/bootstrap.min.css"; ?>">
	<link rel="stylesheet" href="<?php echo LINK."assets/gentelella-master/build/css/custom.min.css"; ?>">
	<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
	<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>
	<script src="<?php echo LINK.'assets/jquery/jQuery.print.js' ?>"></script>
	<script src="<?php echo LINK.'assets/jquery/jquery.printPage.js' ?>"></script>
</head>
<body>
	<div class="panel panel-success" ">
		<div class="panel panel-heading">
			<table>
				<tr><td><p style="font-size:12px">
					Note: You may need to set the correct paper size before printing, you can set paper size in the "More settings" in the print window .
				</p>
				 <div  style="margin-top:-30px; margin-left:710px"> 
					<button id="printMe" type="button" class="btn-sm btn-info" onClick=' $("#qr_codes").print(/*options*/);'> Print </button>  
				</div> 
				</td></tr>
			</table>
		</div>
		<div id="qr_codes" class="panel panel-body" style='width:100%'>
			<centerx>
				<table border='1' cellspacing="10">
					<?php
				
						$ctr = 1;
						foreach($deliveries as $del){
							$item = $this->Inventory_model->getItem($del['item_id']);
							
							echo "<tr>";

							$qty = ($del['quantity']>100)?100:$del['quantity'];//limits qr codes to improve speed
							for($x=1;$x<=$qty;$x++){
								
								if($x % 10==1){
									echo "<tr>";
								}


									echo '<td style="width:120px;height:200px;">';
									echo "<b><center>Item: ".$item[0]['item_code'].", ".$item[0]['description']." (".$item[0]['unit'].") ".$delivery[0]['tdate']."</center></b><hr>";
									echo'<div id="qrcode'.$ctr.'" style="width:100px; height:100px; margin-top:10px; margin-left:10px;margin-right:10px;margin-bottom:10px">
										    	<input type="hidden" name="item_id" id="item_id'.$ctr.'"  value="'.$del['item_id'].'" readonly/>
												<input type="hidden" name="item_code" id="item_code'.$ctr.'"  value="'.$item[0]['item_code'].'" readonly/>
												<input type="hidden" name="unit" id="unit'.$ctr.'"  value="'.$del['unit'].'" readonly/>
												<input type="hidden" name="unit_cost" id="unit_cost'.$ctr.'"  value="'.$del['unit_price'].'" readonly/>
												<input type="hidden" name="delivery_id" id="delivery_id'.$ctr.'"  value="'.$delivery[0]['id'].'" readonly/>
									        </div>';

									echo '</td>';

								
								$ctr++;
							}
							echo "</tr>";
						}
					?>
				</table>
			</centerx>
		</div>
	</div>
</body>
</html>
<script>
	var qrcode = new Array();
	for(x=1;x<=9999;x++){
		 qrcode[x] = new QRCode(document.getElementById("qrcode"+x), {
			width : 100,
			height : 100
		});

		var item_id = document.getElementById("item_id"+x);
		var item_code = document.getElementById("item_code"+x);
		var item_unit = document.getElementById("unit"+x);
		var item_price = document.getElementById("unit_cost"+x);
		var delivery_id = document.getElementById("delivery_id"+x);

		qrcode[x].makeCode(item_id.value+","+item_price.value+","+delivery_id.value);
	}
</script>