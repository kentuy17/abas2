<?php		
		
		
		$amount = '';
		$total_liquidation = '';
		//$display = '';	
		
		
		
		$id= $cash_advance[0]['id'];
		$requested_by = $this->Abas->getEmployee($cash_advance[0]['requested_by']);
		$ca_amount = $cash_advance[0]['amount'];
		$bal = $cash_advance[0]['amount'];
		$total_liquidated = 0;
		
		if(count($cash_liquidation)){
			$ctr = count($cash_liquidation);
			//get total liquidated amount
			for($i = 0 ; $i < $ctr ; $i++){
				$amount = $cash_liquidation[$i]['amount'];
				$total_liquidated = $total_liquidated + $amount;
			}
			
			$bal = $ca_amount - $total_liquidated;
			
		}
		
		
?>

<!---
<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>
<script src="<?php echo LINK; ?>assets/toastr/toastr.js"></script>   
--->
<script type="text/javascript">
		
				
		function submitMe(){

			var sitems = document.getElementById('sels').value;
			
			if(sitems == ''){
				alert('Please enter items.');				
				document.getElementById('particular').focus();
				return false;
			}else{
				if(confirm('You are about to save this liquidation.')){
					document.forms['tranForm'].submit();
				}
			}
		}
		
		
		function delItem(id){
		
		var camount = <?php echo $ca_amount ; ?>;
		var se = document.getElementById('sels').value;
		var bal = document.getElementById('balance').value;		
				
		de = se.replace(id, "");
		
		document.getElementById('sels').value = de;			

		
			$.post('<?php echo HTTP_PATH."finance/getLiquidation/"; ?>',
				  { 'id':de,'ca':camount,'bal':bal},                                                        
						function(result) {
							//alert(result);
							// clear any message that may have already been written
							$('#selected').html(result);							
						}
			);							
		
		}
		
		
		</script>
<style>
#changeStatus {
	background-color: #DDD;
	float: left;
	position: absolute;
}<a href="item_form.php">Welcome to CodeIgniter</a>
#changeStatus .form-group {
    margin: 15px;
}
</style>

	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin-bottom:50px;">
		<div class="panel panel-primary">
			<div class="panel-heading">               
                
				<div style="font-size:16px">
					 Liquidation Form&nbsp;&nbsp;
                </div>
			</div>
           
            <div class="panel-body">
            
            <div style="font-size:20px; font-weight:600">
            	<p >
                	<div>Request #: <?php echo $requested_by['id']; ?></div>
                    <div>Account of: <?php echo $requested_by['full_name']; ?></div>
                    <div>Amount for Liquidation: Php <?php echo number_format($ca_amount); ?></div>
                    
                </p>
                 <hr />
            </div>
								<form class="form-horizontal" role="form" id="tranForm" name="tranForm"  action="<?php echo HTTP_PATH.'finance/add_liquidation'; ?>" method="post" enctype='multipart/form-data' >
									<?php echo $this->Mmm->createCSRF() ?>                              
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />		                 
                                    <input type="hidden" id="sels" name="sels" />
                                    <input type="hidden" id="ca_amount" name="ca_amount" value="<?php echo $ca_amount ?>" />
                                    <input type="hidden" id="balance" name="balance" value="<?php echo $bal ?>" />
                                    <input type="hidden" id="return" name="return" value="0" />
                                </form>

                                	<div style="width:800px; float:left">
                                   <div class="row">  
										<div class="col-md-8">
                                       		<div style="float:left;width:200px;">
                                            
                                       			<label for="department">Vessel / Office:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                               <select class="form-control input-sm" name="department" id="department" style="width:150px">
                                                    <option></option>
                                                    <?php foreach($department as $dep){ ?>
                                                    <option value="<?php echo $dep->id ?>"><?php echo $dep->name ?></option>
                                                    <?php } ?>
                                               </select>
                                               
                                            </div>
                                            
                                            <div style="float:left;width:200px;">
                                            <label for="type">Expense Type:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                               <select class="form-control input-sm" name="type" id="type" style="width:150px">
                                                    <option></option>
                                                    
                                                    <?php foreach($type as $t){ ?>
                                                    <option value="<?php echo $t['id'] ?>"><?php echo $t['name'] ?></option>
                                                    <?php } ?>
                                               </select>
                                            
                                            </div>
                                            
                                   		</div>
                                   		
								   		<div class="col-md-12">
                                           <div style="float:left;width:200px;">
                                           <label for="type">Particular:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                <input type="text" class="form-control input-sm" name="particular" id="particular" style="width:150px"/>                             </div>
                                         	<div style="float:left;width:200px;">
                                             <label for="type">Receipt # (optional):&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                       <input type="text" class="form-control input-sm" name="receipt" id="receipt" style="width:150px"/>
                                            </div> 
                                            
                                            <div style=float:left;"width:200px;">
                                        	<label for="unit">Amount:&nbsp;&nbsp;(press enter to add)&nbsp;&nbsp;</label>
                                       		
                                            <input class="form-control input-sm" type="text" name="amount" id="amount" style="width:70px; background:#ECE0B0"
                                            	onkeypress="
                                                	if(event.keyCode == 13){
                                                    	                                                        
                                                    	var vals  = new Array();                                  
                                                		var ids = new Array();                                  
                                                        //var i = document.getElementById('id').value;                                                
                                                        var d = document.getElementById('department').value;
                                                        var t = document.getElementById('type').value;
                                                        var p = document.getElementById('particular').value;
                                                        var r = document.getElementById('receipt').value;                                
                                                        var ca_amount = document.getElementById('ca_amount').value;                                                        var sels = document.getElementById('sels').value;								
                                                        var a = document.getElementById('amount').value;					
                                                        var bal = document.getElementById('balance').value;                                              							
                                                                                         	
                                                    	if(d == ''){
                                                                alert('Please select department.');
                                                                document.getElementById('department').focus();
                                                                return false;
                                                        }else if(t == ''){
                                                                alert('Please select expense type.');
                                                                document.getElementById('type').focus();
                                                                return false;
                                                        }else if(p == ''){                                                                
                                                                alert('Please enter particular.');
                                                                document.getElementById('particular').focus();
                                                                return false;
                                                        }else if(a == ''){
                                                                alert('Please enter amount.');
                                                                document.getElementById('amount').focus();
                                                                return false;        
                                                        }else{                                                           
                                                                                                    
                                                            vals = a+'|'+r+'|'+p+'|'+d+'|'+t+','+sels;                                  
							
                                                            ids.push(vals);		                                                          	
                                                            document.getElementById('sels').value = ids;
                                                          	                    
                                                           
                                                              $.post('<?php echo HTTP_PATH."finance/getLiquidation/"; ?>',
                                                                              { 'id':vals,'ca':ca_amount,'bal':bal },
                                                                                    function(result) {
                                                                                       // alert(result);
                                                                                        // clear any message that may have already been written
                                                                                        $('#selected').html(result);
                                                                                        document.getElementById('amount').value = '';
                                                                                        document.getElementById('receipt').value = '';
                                                                                       	document.getElementById('particular').value = '';
                                                                                        document.getElementById('particular').focus();
                                                                                    }
                                                               );
                                                               
                                                        }  
                                                            
                                          }                  				  
                                                           

                                                      
                                                "/>
                                           
                		                   
										    <div>
                                                                                            
                                   		</div>
  								   		
                                    </div>    
                                    </div>    
                                    </div> 
                                </div>  
									
								
                            <br /><br /><br /><br />      <br /><br />      
                        <div class="form-group">
							<div id="selected" style="margin-top:20px">
								<table class='table table-striped table-bordered table-hover table-condensed' data-toggle='table' style='font-size:12px'>
                                	<tr style='font-weight:600; background:#000; color:#FFF'>  	
										<td width='15%'>Department</td>
                                        <td width='15%'>Expense Class</td>
                                        <td width='15%'>Receipt</td>										
                                        <td width='45%'>Particular</td>																				
                                       	<td width='20%'>Amount</td>	
                                        <td width='5%'>*</td>
                                    </tr>
                                    
									<tr>
                                    	<td align="left">&nbsp;</td>
                                        <td align="left">&nbsp;</td>
                                        <td align="left">&nbsp;</td>
                                        <td align="left">&nbsp;</td>
                                        <td align="right">&nbsp;</td>
                                        <td align='center'><a href='#' id='0' 
										onclick='javascript:delItem(this.id);' title='Remove'><i class='fa fa-minus-square'></i></a></td>	
                                  	</tr>
                                    
                           		</table>
                                <table align="right" style='font-size:16px; margin-right:50px'>         
                                    <tr style="display:none">
                                    	<td></td>
                                        <td align="right"> Total Liquidation:</td>
                                        <td align="right"> Php  <?php echo number_format($total_liquidated,2); ?>&nbsp;&nbsp;</td>
										<td width="60px">&nbsp;</td>
                                    </tr>
									
									<tr style='color:#FF0000'>
                                    	<td>&nbsp;</td>
                                        <td align="right"> Balance:</td>
                                        <td align="right"> Php  <?php echo number_format($bal,2); ?>&nbsp;&nbsp;</td>
										<td>&nbsp;</td>
                                    </tr>
                                    
                                </table>
							</div>
                            
                           <?php 
						   		//list of past liquidation
								if(count($cash_liquidation)){ 
							?> 
                            <br />
                            <div style="float:left; margin-left:30px; margin-top:20px; font-size:10px">
                            	<h5>   <a href="#" onclick="$('#past_liquidation').toggle();">		
                            	<i class="fa fa-search"></i>   History of Liquidation:</a></h5>
                            </div>
                            <div id="past_liquidation" style="margin-top:40px; display:none">
                            	
                                
                                <table class='table table-bordered table-striped table-hover' data-toggle='table' style='font-size:12px'>
                                	<thead>
										<tr style='font-weight:600; background:#000; color:#FFF'>  										
											<td width='15%'>Receipt</td>										
											<td width='45%'>Particular</td>																				
											<td width='20%'>Amount</td>	
											<td width='5%'>*</td>
										</tr>
									</thead>
									<tbody>
                                    
									<?php 
										$tot= 0;
										foreach($cash_liquidation as $cl){ 
									
									?>
                                    <tr>
                                    	<td align="left">&nbsp;<?php echo $cl['receipt_no'] ?></td>
                                        <td align="left">&nbsp;<?php echo $cl['particular'] ?></td>
                                        <td align="right">&nbsp;<?php echo number_format($cl['amount'],2) ?></td>
                                        <td align='center'>*</td>	
                                  	</tr>
                                    <?php 
										$tot = $tot +$cl['amount'];
									} ?>
                                    <tr style="font-weight:600">
                                    	<td colspan="2" align="right">&nbsp;Total:&nbsp;</td>
                                        <td align="right"><?php echo number_format($tot,2)?></td>
                                        <td></td>
                                    </tr>
                                    
                           		</table>
                                
                            </div>
                            <?php }?>
						</div> 
			
                </div>  
					
                    <div style="float:right; margin-right:40px; margin-top:5px">
						<span style="float:left; color:#003399;">
                        <input type="checkbox" name="returned" id="returned" value="<?php echo $bal ?>" onclick=" if($(this).is(':checked')) {  
                                                         $('#return').val(1);                                                     
                                                    }
                                                    else{
                                                         $('#return').val(0);
                                                    }    " />&nbsp;<strong>(check if balance is returned)</strong>
                        </span>
                        <span style="float:right">
                        <input class="btn btn-success btn-sm" type="button"  value="Save" onclick="javascript:submitMe()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">
						<input class="btn btn-default btn-sm"  value="Cancel" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:10px" onclick="javascript:window.location = '<?php echo HTTP_PATH."finance/accounts_view##cash_advance"; ?>';">
                        </span>
					</div>							
                    
            </div>          
		</div>
		
