<!DOCTYPE html>
<html>
<head>
	<title>AVega Business Automation System</title>
	<script type="text/javascript" src="<?php echo LINK ?>assets/qr_code_maker/qrcode.js"></script>

	<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap/css/bootstrap.min.css"; ?>">
	<link rel="stylesheet" href="<?php echo LINK."assets/gentelella-master/build/css/custom.min.css"; ?>">

	<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
	<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>

	<script src="<?php echo LINK.'assets/jquery/jQuery.print.js' ?>"></script>
	<script src="<?php echo LINK.'assets/jquery/jquery.printPage.js' ?>"></script>
</head>

<body>
	<input type="hidden" name="item_id" id="item_id"  value='<?php echo $item[0]['id']?>' readonly/>
	<input type="hidden" name="item_code" id="item_code"  value='<?php echo $item[0]['item_code']?>' readonly/>
	<input  type="hidden" name="unit" id="unit" value='<?php echo $item[0]['unit']?>' readonly/>
	<input  type="hidden" name="unit_cost" id="unit_cost" value='<?php echo $item[0]['unit_price']?>' readonly/>
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
			
			<table border='1' cellspacing="0">

				<?php
					$ctr = 1;
						foreach($item as $i){
							
							echo "<tr>";

							for($x=1;$x<=$qty_loc;$x++){
								
								if($x % 10==1){
									echo "<tr>";
								}

									echo '<td style="width:120px;height:200px;">';
									echo "<b><center>Item: ".$item[0]['item_code'].", ".$item[0]['description']." (".$item[0]['unit'].")</center></b><hr>";
									echo'<div id="qrcode'.$ctr.'" style="width:100px; height:100px; margin-top:10px; margin-left:10px;margin-right:10px;margin-bottom:10px"></div>';
									echo '</td>';

								
								$ctr++;
							}
							echo "</tr>";
						}
				?>
			</table>
			
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

		var item_id = document.getElementById("item_id");
		var item_code = document.getElementById("item_code");
		var item_unit = document.getElementById("unit");
		var item_price = document.getElementById("unit_cost");

		qrcode[x].makeCode(item_id.value+","+item_price.value);

	}
</script>