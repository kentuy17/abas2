<?php
	
	
	//var_dump($report);
	//var_dump($location);
	//exit;

?>



<!DOCTYPE html>
<html>
<head>
	<title><?php if(ENVIRONMENT=="development") { echo "DEV - "; } ?>AVEGA Business Automation System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />

	<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap/css/bootstrap.min.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/global.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/style.css"; ?>" />

	<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"-->
	<link rel="stylesheet" href="<?php echo LINK; ?>assets/bootstrap-table-master/src/bootstrap-table.css">
	<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
	<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>

	<script src="<?php echo LINK.'assets/bootstrap/js/bootstrap.min.js'; ?>"></script>
	<script src="<?php echo LINK; ?>assets/bootstrap-table-master/src/bootstrap-table.js"></script>
	<script src="<?php echo LINK; ?>assets/toastr/toastr.js"></script>
	<script src="<?php echo LINK; ?>assets/stickUp.min.js"></script>



<style>

#header{margin-top:30px}
#title{ font-size:16px; font-weight:600}
#ttype{ font-size:18px; font-weight:600; margin-top:20px}
#rr_no{ margin-top:-20px; float:right; font-weight:600;}
#receive_from{ margin-top:10px; float:left}
#date{ margin-top:10px; margin-right:100px;  float:right}
#po_no{ margin-top:30px;; margin-left:-100px; float:left}
#pr_no{ margin-top:30px; margin-right:-50px; float:right}
#si_no{ margin-top:50px; margin-left:-100px; float:left}
#dr_no{ margin-top:50px; margin-right:-50px; float:right}
#items{ margin-top:20px;}
#received_by{ margin-top:80px; float:left}
#inspected_by{ margin-top:80px; margin-left:200px; float:left}
#noted_by{ margin-top:-20px; margin-left:500px; float:left; width:150px}
#copy{ margin-top:0px; font-size:12px; font-weight:600; float:left; position:absolute}


</style>

</head>
<body>

<div align="center" style="width:800px; margin-left:0px; margin-top:-20px">

    <div id="header">
    
    <div id="items">
    	<div id="table">
        	<table style="width:800px; " >
            	<tr>
                	<td colspan="3" align="left">&nbsp;</td>
                </tr>
                <tr>
                	<td colspan="3" id="title" align="center">INVENTORY COUNT SHEET</td>
                </tr>
                
                <tr>
                	
                    <td align="center"  colspan="3">AS OF: __________________</td>
                    
                    
                </tr>
                
                <tr>
                	<td align="left"><strong>&nbsp;
															
															
														</strong></td>
                    <td align="left"></td>
                    <td align="right"></td>
                    
                </tr>
                <?php if(isset($location) && $location != ''){  ?>
                <tr>
                	<td align="left"><strong> SITE/BRANCH: <?php echo $location;  ?>
															
															
														</strong></td>
                    <td align="left"></td>
                    <td align="right"></td>
                    
                </tr>
               <?php } ?>
               
              
               <tr>
                	
                    <td align="left">INVENTORY LOCATION: _________________________________ </td>
                    <td align="right">DATE: _________________________________ </td>
                    
                </tr>     
                
                               
               <?php 
				//for issuance
				
					if(isset($company)){ 
				
				?>
                 <tr>
                	<td align="left"><strong> COMPANY: <?php  echo $company->name; ?>
															
															
														</strong></td>
                    <td align="left"></td>
                    <td align="right"></td>
                    
                </tr>
                <?php } ?>
                
               
            </table>
            <br>
            
           
            
            <table style="width:800px; border:thin 1px #000000 solid; font-size:12px" border="1px">
            	
                <thead>
                	<tr align="center" style="font-weight:600">                    
                    <td width="5%">#</td>
                    <td width="8%">Item Code</td>
                    <td width="30%">Description</td>
                   
                    <td width="7%">Unit</td>
                    <td width="7%">Qty on Hand</td>
                    <td width="7%">Actual Count</td>
                    <td width="7%">Variance</td>
                    <td width="28%">Remark</td>                    
                    
                    </tr>
                </thead>
                <tbody>
                	<?php
												
												$ctr = 1;
												foreach($items as $item){
												
												
												//get category
												//$sq = "SELECT * FROM `inventory_category` WHERE id =".$item['category'];
												//$r = $this->db->query($sq);
												//$cat = $r->result_array();

												//get category
												$sq1 = "SELECT * FROM `inventory_location` WHERE item_id =".$item['id'];

												$r1 = $this->db->query($sq1);
												$qt1 = $r1->result_array();


												if(count($qt1) > 0){
													$tayud_qty = $qt1[0]['tayud_qty'];
													$nra_qty = $qt1[0]['nra_qty'];
													$mkt_qty = $qt1[0]['mkt_qty'];
													$total_qty = $tayud_qty + $nra_qty + $mkt_qty;
												}else{
													$tayud_qty = 0;
													$nra_qty = 0;
													$mkt_qty = 0;
													$total_qty = 0;
												}
												
												
												//manage location qty
												if($_SESSION['abas_login']['user_location']== 'Makati'){
													$qty = $mkt_qty;
												}elseif($_SESSION['abas_login']['user_location']== 'NRA'){
													$qty = $nra_qty;
												}elseif($_SESSION['abas_login']['user_location']== 'Tayud'){
													$qty = $tayud_qty;
												}
												
												//echo $tayud_q1ty.'<br>';
												//var_dump($qt1);

											?>
												<tr>
													<td align="center"><?php echo $ctr; ?></td>
                                                    <td align="center"><?php echo $item['item_code']; ?></td>
													<td align="left"><?php echo $item['description']; ?></td>													
													
                                                    <td align="left"><?php echo strtolower($item['unit']);  ?></td>
                                                    <td align="center"><?php echo $qty; ?></td>
													<td align="center">&nbsp;</td>
                                                    <td align="center">&nbsp;</td>                                                    
                                                    <td align="center">&nbsp;</td>                                                   
													<td>&nbsp;</td>
												</tr>
                                                
                                                
											<?php 
												
												$ctr++;
											
											} ?>
                    
                </tbody>
                
            </table>
           
             
                      
           
        	<table style="width:800px; margin-top:40px; font-size:12px" >
            	
                <tr>
                	<td align="left">Counted By:_____________________</td>
                    <td align="center"><span  style="margin-right:30px">Verified By:_____________________</span></td>
                    <td align="left"></td>
                    <td align="right">Noted By:______________________</td>
                    
                </tr>
                <tr>
                	<td align="center"><span  style="margin-right:0px">Warehouseman</span></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left">&nbsp;</td>
                    
                </tr>
               
                <tr>
                	<td align="left"></td>
                    <td align="left"></td>
                    <td align="left"></td>
                    <td align="left">&nbsp;</td>
                    
                </tr>
                
                
                
            </table>
            <br>
        </div>        
    </div>
    
    
    
</div>


<!--	
    <div id="header">
    <div id="title">AVEGA BROS INTEGRATED SHIPPING CORP.</div>
    <div>J. De Veyra St. NRA, Cebu City</div>
    <div id="ttype">RECEIVING REPORT</div>
    <div id="rr_no">09230</div>
    <div id="recieve_from">Received From:</div>
    <div id="date">Date:</div>
    <div id="po_no">PO No.:</div>
    <div id="pr_no">PR No.:</div>
    <div id="si_no">SI No.:</div>
    <div id="dr_no">DR No.:</div>
    <div id="items">
    put table here
    </div>
    <div id="received_by">Received by:</div>
    <div id="inspected_by">Inspected by:</div>
    <div id="noted_by">Noted by:</div>
    <div id="copy">Warehouse Copy</div>

-->


</div>



<table>

</table>


</div>

</body>
<script>
<?php $this->Abas->display_messages(); ?>
</script>
<script>
// resets the modal upon close so there is no need to write new markup for each modal dialog
$('body').on('hidden.bs.modal', '.modal', function () {
	$(this).removeData('bs.modal');
	$(".modal-content").html("<p class='loading-text'>Loading Content...</p>");
});

function showNotifications() {
	// toastr['info']("This is a notification");
	<?php if(isset($_SESSION['abas_login'])) echo $this->Abas->getNotifications(); ?>
}

$(function() {
	var	$window = $(window),
	$body = $('body');

	// Disable animations/transitions until the page has loaded.
	$body.addClass('is-loading');

	$window.on('load', function() {
		window.setTimeout(function() {
			$body.removeClass('is-loading');
		}, 0);
	});
});
</script>
</html>
