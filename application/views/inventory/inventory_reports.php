<?php


$title='';
if(isset($_SESSION['abas_login']['user_location'])){

	//set inventory location
	$user_location = $_SESSION['abas_login']['user_location'];

	switch ($user_location) {
		case 'Makati':
			$title = "You are using Makati Inventory System";

			break;
		case 'NRA':
			$title = "You are using NRA Inventory System";

			break;
		case 'Tayud':
			$title = "You are using Tayud Inventory System";

			break;
		case 'Tacloban':
			$title = "You are using Tacloban Inventory System";

			break;
	}

}else{
	header("location:".HTTP_PATH."home");die();//user cannot use inventory without user location
}

$id = "";
$payee = "";
$payee_name = "";
$voucher_no = "";
$voucher_date = "";
$particular = "";
$amount = "";
$reference_no = "";
$voucher_no = "";
$vessel_id = "";
$vessel_name = "";
$include_on = "";
$classification = "";
$classification_name = "";

if(isset($viewExpense)){
	//var_dump($viewExpense[0]['id']);
	$id = $viewExpense[0]['id'];
	$voucher_no = $viewExpense[0]['check_voucher_no'];
	$voucher_date = $viewExpense[0]['check_voucher_date'];
	$particular = $viewExpense[0]['particulars'];;
	$amount = $viewExpense[0]['amount_in_php'];;
	$reference_no = $viewExpense[0]['reference_no'];
	$vessel_id = $viewExpense[0]['vessel_id'];
	if($vessel_id!=''){
		$v = $this->Abas->getVessel($vessel_id);
		$vessel_name = $v->name;
	}
	$payee = $viewExpense[0]['account_id'];
	if($payee!=''){
		$p = $this->Accounting_model->getSuppliers($payee);
		if(count($p) > 0){
			$payee_name = $p[0]['name'];
		}
	}
	$classification = $viewExpense[0]['expense_classification_id'];;
	if($classification!=''){
		$c = $this->Accounting_model->getExpenseClassification($classification);
		if(count($c) > 0){
			$classification_name = $c[0]['name'];
		}
	}
	$include_on = $viewExpense[0]['include_on'];
}
$link = HTTP_PATH.'Inventory/inventory_report_form/';




?>

<script type="text/javascript">


	function issueReport(){

			var ves = document.getElementById('vessel').value;
			//alert(ves);

			if(ves ==''){
				alert('Please select vessel.');
				document.getElementById('vessel').focus();
				return false;
			}else{
				document.forms['issueReport'].submit();
			}
		}


</script>


<style>#content{ margin-top:-20px; }</style>
<div class="panel-group" id="content">
	<div class="panel panel-default">
		<div class="panel-heading" >
        	&nbsp;
			<span style="float:left; margin-left:0px; margin-top:-10px">
			<h4><strong><span style="background:#000099; color:#FFFFFF">INVENT</span><span style="background:#FF0000; color:#F4F4F4">ORY</span></strong></h4>
            </span>
            <span style="float:right; margin-right:20px; margin-top:-5px">
				
                 
                 <a class="like" href="<?php echo HTTP_PATH ?>inventory"  title="Report">
					<button type="button" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-list-alt"></i> Back</button>
                </a>
				

            </span>
		</div>
		
        
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" >
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span style="float:right; margin-right:10px; margin-top:-15px">
					<!---
                    <input class="btn btn-success btn-sm" type="button"  value="Save" onclick="javascript:checkform()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">
					<input class="btn btn-default btn-sm"  value="Cancel" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:10px">	--->
				</span>
				<div style="font-size:16px">
					<span class="glyphicon glyphicon-list"></span>  Inventory Reports
                    
                   
                    </span>
                </div>
			</div>
		</div>
        	
            	
            
		<div class="panel panel-info" style="font-size:11px; width:95%; margin-left:30px;margin-top:20px" >
			
            
            <div class="panel-heading" role="tab" id="headingOne">
			<h5 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				<i class="glyphicon glyphicon-export"></i>  Issuance
				</a>
			</h5>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					
					<div style="width:250px; margin-left:30px; float:left; display:table;">
						<form class="form-horizontal" role="form" id="issueReport" name="issueReport" target="_blank"  action="<?php echo HTTP_PATH.'inventory/inventory_report_result'; ?>" method="post">
                        <div class="form-group">
							<label for="last_name">By Vessel:</label>
							<select class="form-control input-sm" name="vessel" id="vessel">
									<option></option>
									<?php

									foreach($vessels as $vessel){ ?>
								<option value="<?php echo $vessel->id; ?>"><?php echo $vessel->name; ?></option>
								<?php } ?>
								</select>
						</div>
						<div class="form-group">
							<label for="last_name">By Location:</label>
							<select class="form-control input-sm" name="location" id="location">
																<option></option>
																<?php 
																	
																	foreach($locals as $loc){ 
																		
																		//if($loc['location_name'] != $user_location){														
																
																?>	
																	
                                                                    
                                                                	<option value="<?php echo $loc['location_name'] ?>">
																		<?php echo $loc['location_name'] ?>
                                                                    </option>
                                                                    
                                                                <?php 	
																		//} 
																
																	} 
																
																?>	
															</select>
						</div>
						<div class="form-group">
							<label for="birth_date">Select Inclusive Dates:</label>
							<div>
								From: <input class="form-control input-sm" type="text" name="from_date" id="from_date" placeholder="From" style="width:100px"/>
                                <span style="float:right; margin-top:-46px; margin-right:55px">To: <input class="form-control input-sm" type="text" name="to_date" id="to_date" placeholder="To" style="width:100px"/></span>
								<script>
									$("#from_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});
                                	$("#to_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});
                                </script>
							</div>
						</div>
						
						
                       	
                        <span style=" margin-top:20px">
							<input class="btn btn-success btn-sm" type="button"  value="Create" onclick="
                            //var ves = document.getElementById('vessel').value;
                            //alert(ves);
                
                            
                                document.forms['issueReport'].submit();
                            " id="submitbtn" style="width:80px; margin-left:-15px; margin-top:10px">
                            
							
						</span>
                        	<input type="hidden" name="action" id="action" value="issuance" />
                        </form>
					</div>
					<!---end of div float left --->
					<!---div float right --->
					<div style="width:250px; margin-right:130px; float:right; margin-top:25px">
						
					</div>
					<!---end div float right --->
				</div>
			</div>
            
            
            <div class="panel-heading" role="tab" id="headingOne">
			<h5 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
				<i class="glyphicon glyphicon-import"></i>  Receiving
				</a>
			</h5>
			</div>
			<div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					
					<div style="width:250px; margin-left:30px; float:left; display:table;">
						<form class="form-horizontal" role="form" id="receiveReport" name="receiveReport" target="_blank"  action="<?php echo HTTP_PATH.'inventory/inventory_report_result'; ?>" method="post">
                        <div class="form-group">
							<label for="last_name">By Supplier:</label>
							<select class="form-control input-sm" name="supplier" id="supplier">
																<option></option>
                                                                <option value="0001">Avegabros Integrated</option>
																<?php foreach($supplier as $sup){ ?>
                                                                <option value="<?php echo $sup['id']; ?>"><?php echo $sup['name']; ?></option>
                                                                <?php } ?>
															</select>
						</div>
						
                        <div class="form-group">
							<label for="last_name">By Location:</label>
							<select class="form-control input-sm" name="location" id="location">
																<option></option>
																<?php 
																	
																	foreach($locals as $loc){ 
																		
																		//if($loc['location_name'] != $user_location){														
																
																?>	
																	
                                                                    
                                                                	<option value="<?php echo $loc['location_name'] ?>">
																		<?php echo $loc['location_name'] ?>
                                                                    </option>
                                                                    
                                                                <?php 	
																		//} 
																
																	} 
																
																?>	
															</select>
						</div>
						
						<div class="form-group">
							<label for="birth_date">Select Inclusive Dates:</label>
							<div>
								From: <input class="form-control input-sm" type="text" name="rfrom_date" id="rfrom_date" placeholder="From" style="width:100px"/>
                                <span style="float:right; margin-top:-46px; margin-right:55px">To: <input class="form-control input-sm" type="text" name="rto_date" id="rto_date" placeholder="To" style="width:100px"/></span>
								<script>
									$("#rfrom_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});
                                	$("#rto_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});
                                </script>
							</div>
						</div>
						
						
                       	
                        <span style=" margin-top:20px">
							<input class="btn btn-success btn-sm" type="button"  value="Create" onclick="
                             var sp = document.getElementById('supplier').value;
                            //alert(ves);
                
                            /*if(sp ==''){
                                alert('Please select supplier.');
                                document.getElementById('vessel').focus();
                                return false;
                            }else{*/
                                document.forms['receiveReport'].submit();
                            //}
                            
                            " id="submitbtn" style="width:80px; margin-left:-15px; margin-top:10px">
                            
							
						</span>
                        	<input type="hidden" name="action" id="action" value="receiving" />
                        </form>
					</div>
					<!---end of div float left --->
					<!---div float right --->
					<div style="width:250px; margin-right:130px; float:right; margin-top:25px">
						
					</div>
					<!---end div float right --->
				</div>
			</div>
            
            
            <div class="panel-heading" role="tab" id="headingOne">
			<h5 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
				<i class="glyphicon glyphicon-transfer"></i>  Inventory Transfer
				</a>
			</h5>
			</div>
			<div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					
					<div style="width:250px; margin-left:30px; float:left; display:table;">
						<form class="form-horizontal" role="form" id="transferReport" name="transferReport" target="_blank"  action="<?php echo HTTP_PATH.'inventory/inventory_report_result'; ?>" method="post">
                        <div class="form-group">
							<label for="last_name">From Location:</label>
							<select class="form-control input-sm" name="tfrom_location" id="tfrom_location">
																<option></option>
																<?php 
																	
																	foreach($locals as $loc){ 
																		
																		if($loc['location_name'] != $user_location){														
																
																?>	
																	
                                                                    
                                                                	<option value="<?php echo $loc['location_name'] ?>">
																		<?php echo $loc['location_name'] ?>
                                                                    </option>
                                                                    
                                                                <?php 	
																		} 
																
																	} 
																
																?>	
															</select>
						</div>
                        <div class="form-group">
							<label for="last_name">To Location:</label>
							<select class="form-control input-sm" name="tto_location" id="tto_location">
																<option></option>
																<?php 
																	
																	foreach($locals as $loc){ 
																		
																		if($loc['location_name'] != $user_location){														
																
																?>	
																	
                                                                    
                                                                	<option value="<?php echo $loc['location_name'] ?>">
																		<?php echo $loc['location_name'] ?>
                                                                    </option>
                                                                    
                                                                <?php 	
																		} 
																
																	} 
																
																?>	
															</select>
						</div>
                        
                       
						
						<div class="form-group">
							<label for="birth_date">Select Inclusive Dates:</label>
							<div>
								From: <input class="form-control input-sm" type="text" name="tfrom_date" id="tfrom_date" placeholder="From" style="width:100px"/>
                                <span style="float:right; margin-top:-46px; margin-right:55px">To: <input class="form-control input-sm" type="text" name="tto_date" id="tto_date" placeholder="To" style="width:100px"/></span>
								<script>
									$("#tfrom_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});
                                	$("#tto_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});
                                </script>
							</div>
						</div>
						
						
                       	
                        <span style=" margin-top:20px">
							<input class="btn btn-success btn-sm" type="button"  value="Create" onclick="
                            	 var fl = document.getElementById('tfrom_location').value;
                                 var tl = document.getElementById('tto_location').value;
                                alert(fl);
                    
                                if(fl ==''){
                                    alert('Please select origin.');
                                    document.getElementById('tfrom_location').focus();
                                    return false;
                                }else if(tl ==''){
                                    alert('Please select destination.');
                                    document.getElementById('tto_location').focus();
                                    return false;    
                                }else{
                                    document.forms['transferReport'].submit();
                                }
                            
                            
                            " id="submitbtn" style="width:80px; margin-left:-15px; margin-top:10px">
                            
							
						</span>
                        	<input type="hidden" name="action" id="action" value="transfer" />
                        </form>
					</div>
					<!---end of div float left --->
					<!---div float right --->
					<div style="width:250px; margin-right:130px; float:right; margin-top:25px">
						
					</div>
					<!---end div float right --->
				</div>
			</div>
            
            
            
            <div class="panel-heading" role="tab" id="headingOne">
			<h5 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" aria-controls="collapse4">
				<i class="glyphicon glyphicon-transfer"></i>  Physical Count Report
				</a>
			</h5>
			</div>
			<div id="collapse4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					
					<div style="width:250px; margin-left:30px; float:left; display:table;">
						<form class="form-horizontal" role="form" id="physicalReport" name="physicalReport" target="_blank"  action="<?php echo HTTP_PATH.'inventory/inventory_report_result'; ?>" method="post">
                        <div class="form-group">
							<label for="last_name">Select Location:</label>
							<select class="form-control input-sm" name="site_location" id="site_location">
																<option></option>
																<?php 
																	
																	foreach($locals as $loc){ 
																		
																														
																
																?>	
																	
                                                                    
                                                                	<option value="<?php echo $loc['location_name'] ?>">
																		<?php echo $loc['location_name'] ?>
                                                                    </option>
                                                                    
                                                                <?php 	
																		
																
																	} 
																
																?>	
															</select>
						</div>
                        
                        					
                       	
                        <span style=" margin-top:20px">
							<input type="hidden" name="action" id="action" value="count" />
                            <input class="btn btn-success btn-sm" type="button"  value="Create" onclick="
                            	 var siteLoc = document.getElementById('site_location').value;                         
                    			
                                if(siteLoc ==''){
                                    alert('Please select location.');
                                    document.getElementById('tfrom_location').focus();
                                    return false;                                
                                }else{
                                    document.forms['physicalReport'].submit();
                                }
                            
                            
                            " id="submitbtn" style="width:80px; margin-left:-15px; margin-top:10px">
                            
							
						</span>
                        	
                        </form>
					</div>
					<!---end of div float left --->
					<!---div float right --->
					<div style="width:250px; margin-right:130px; float:right; margin-top:25px">
						
					</div>
					<!---end div float right --->
				</div>
			</div>
            
            <div class="panel-heading" role="tab" id="headingOne">
			<h5 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="false" aria-controls="collapse5">
				<i class="glyphicon glyphicon-transfer"></i>  Purchase Order
				</a>
			</h5>
			</div>
			<div id="collapse5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					
					<div style="width:250px; margin-left:30px; float:left; display:table;">
						<form class="form-horizontal" role="form" id="poReport" name="poReport" target="_blank"  action="<?php echo HTTP_PATH.'inventory/inventory_report_result'; ?>" method="post">
                        
                        		
                        
                        <div class="form-group">
							<label for="last_name">By Company:</label>
								<select class="form-control input-sm" name="pcompany" id="pcompany">
									<option></option>
									<?php

									foreach($companies as $comp){ ?>
								<option value="<?php echo $comp->id; ?>"><?php echo $comp->name; ?></option>
								<?php } ?>
								</select>
						</div>
                        
                        <div class="form-group">
							<label for="birth_date">Select Inclusive Dates:</label>
							<div>
								From: <input class="form-control input-sm" type="text" name="pfrom_date" id="pfrom_date" placeholder="From" style="width:100px"/>
                                <span style="float:right; margin-top:-46px; margin-right:55px">To: <input class="form-control input-sm" type="text" name="pto_date" id="pto_date" placeholder="To" style="width:100px"/></span>
								<script>
									$("#pfrom_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});
                                	$("#pto_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});
                                </script>
							</div>
						</div>
						
                        
                        					
                       	
                        <span style=" margin-top:20px">
							<input type="hidden" name="action" id="action" value="po" />
                            <input class="btn btn-success btn-sm" type="button"  value="Create" onclick="

                                    document.forms['poReport'].submit();

                            
                            
                            " id="submitbtn" style="width:80px; margin-left:-15px; margin-top:10px">
                            
							
						</span>
                        	
                        </form>
					</div>
					<!---end of div float left --->
					<!---div float right --->
					<div style="width:250px; margin-right:130px; float:right; margin-top:25px">
						
					</div>
					<!---end div float right --->
				</div>
			</div>
            
		</div>
		
        
        
        
        
	</div>
        
        
        
     </div>   
        
</div>


<script>


	function operateFormatter(value, row, index) {
		id = row['id']; //alert(id);
		return [
            '<a class="like" href="<?php echo HTTP_PATH.'inventory/item_form/'; ?>'+row['id']+'" title="View" data-toggle="modal" data-target="#modalDialog">',
                '<i class="glyphicon glyphicon-pencil"></i>',
            '</a>',
        ].join('');
    }
	window.operateEvents = {
        'click .like': function (e, value, row, index) {
            p = row["sid"];
			var wid = 940;
			var leg = 680;
			var left = (screen.width/2)-(wid/2);
            var top = (screen.height/2)-(leg/2);
        },
        'click .edit': function (e, value, row, index) {
			p = row["sid"];
        }
    };




	$('input').on('click', function(){
	  var valeur = 0;
	  $('input:checked').each(function(){
		   if ( $(this).attr('value') > valeur )
		   {
			   valeur =  $(this).attr('value');
		   }
	  });
	  $('.progress-bar').css('width', valeur+'%').attr('aria-valuenow', valeur);
	});

	function newEntry(){
		window.location.assign("<?php echo HTTP_PATH.'Inventory' ?>")
		document.forms['itemForm'].reset();
	}

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

	function createReport(){

		document.forms['expenseReport'].submit();

	}

</script>
