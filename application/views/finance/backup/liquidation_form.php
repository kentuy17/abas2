<?php		
		$supplier_id = '';
		$supplier_name = '';
		$name = '';
		$item_name = '';
		$amount = '';
		//$display = '';	
		$total = 0.00;
		$id='';
		
		if(isset($cash)){
   		//var_dump($item[0]['id']);exit;
   		$id = $cash[0]['id'];
   		$amount = $cash[0]['amount'];
   		

   }
?>
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
<script>
function submitMe(){

			var sitems = document.getElementById('sel').value;
			//alert(sitems);
			if(sitems == ''){
				alert('Please enter name and/or amount.');
				document.getElementById('sel').focus();
				return false;
			}else{
				document.forms['tranForm'].submit();
			}
		}
		
		</script>

	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin-bottom:50px;">
		<div class="panel panel-primary">
			<div class="panel-heading">
				
                 </span>
                 <span style="float:right; margin-right:10px; margin-top:-15px;">
					<input class="btn btn-default btn-sm"  value="Cancel" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:10px" onclick="javascript:window.location = '<?php echo site_url("finance/ca_view") ?>';">
				</span>
				<div style="font-size:16px">
					 Purchase Order&nbsp;&nbsp;
                </div>
			</div>
            <div class="panel-body">
			<form class="form-horizontal" role="form" id="tranForm" name="tranForm"  action="<?php echo HTTP_PATH.'finance/add_liquidation'; ?>" method="post" enctype='multipart/form-data'>
							<input type="hidden" name="sel" id="sel" />
                        		<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />		
                        		
								<input type="hidden" id="amount1" name="amount1" value="<?php echo $amount; ?>" style="float:right;margin-top: 19px;width: 100px;" class="form-control input-sm"/>	
								
								<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                                    <input type="hidden" id="selitem" name="selitem" />
                                                    <input type="hidden" id="sels" name="sels" />
                                                    <input type="hidden" id="is_avail" name="is_avail" />
                                                    
													
													</form>
                                	<div style="width:392px; float:left">
                                   <div class="row">  
									<div class="col-md-6">
                                   <label for="type">Supplier:&nbsp;&nbsp;&nbsp;&nbsp;</label>
								   <input type="text" class="form-control input-sm" name="name" id="name" style="width:150px"/>
								   </div>
								   <div class="col-md-6">
                                    <div style="width:200px; float:right">
                                        <label for="unit">Amount:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                       
                                            <input class="form-control input-sm" type="text" name="amount" id="amount" style="width:70px"/>
                                           
                		                    <button onclick="
                									var ids   = new Array();
                                                    var vals  = new Array();                                                                  
                                                
                                                	
                                                	var i = document.getElementById('id').value;
													//alert(id);
                                                	var n = document.getElementById('name').value;
                                                	var a = document.getElementById('amount').value;
                                                	var a1 = document.getElementById('amount1').value;
                                                    var s = document.getElementById('sel').value;								
                                                    //alert(s);
                                                    a = parseInt(a);
                                                	
                                                    if(n == ''){
                                                                alert('Please enter name.');
                                                                return false;
                                                        }else if(a == ''){
                                                                alert('Please enter amount.');
                                                                return false;
                                                        }else{
                                                            
                                                                                                    
                                                            vals = a+'|'+n+'|'+a1+'|'+i+','+s;                                                    
                                                            ids.push(vals);				
                                                            alert(vals);
                                                            document.getElementById('sel').value = ids;	
															//alert(ids);
                                                            document.getElementById('name').value = '';
                                                            document.getElementById('amount').value = '';
                                                            document.getElementById('amount1').value = '';
                                                            var sels = document.getElementById('sel').value;								
                                                            //  alert(sels);                                                 
                                                            $.post('<?php echo site_url("finance/getPO/"); ?>',
                                                                  { 'id':sels },                                                        
                                                                        function(result) {
                                                                           // alert(result);
                                                                            // clear any message that may have already been written
                                                                            $('#selected').html(result);							
                                                                        }
                                                                );							
                                                            
                                                                                                        
                                                        }			
                                           " style="margin-left:110px; margin-top:-30px; float:left" class="btn btn-primary btn-sm">Add</button>
										    <div>
										
                                        </div>
                                    </div>    
                                    </div>    
                                    </div> 
                                </div>  
									
								
                            <br /><br />      
                        <div class="form-group">
							<div id="selected">
								<table class='table table-striped table-bordered table-hover table-condensed' data-toggle='table' style='font-size:12px'>
                                	<tr align='center'>  
										<td width='35%'>Name</td>										
                                         <td width='20%'>Line Total</td>
                                        <td width='5%'>*</td>
										
                                    </tr>
                                    <?php 
										if(isset($po_detail)){
										
											$lineTotal = 0;
											
											$ctr = count($po_detail);
											
											for($i = 0; $i < $ctr; $i++){
											
											$lineTotal = $po_detail[$i]['amount'];
									?>
                                            <tr>
                                                <td align="left"><?php  echo $po_detail[$i]['name']; ?></td>
                                              									
                                                <td align="right"><?php  echo number_format($lineTotal,2); ?></td>
                                                <td align="center"><i class="graphicon graphicon-remove"></i>Delete</td>
                                            </tr>
                                    <?php
											$total = $total + $lineTotal;
											}
										}
									?>
                                    
                                    <tr align="right">
                                    	
                                        <td>Total:</td>
                                        <td>Php <?php echo number_format($total,2); ?></td>
										<td></td>
                                    </tr>
									<tr align="right">
                                    	
                                        <td>Total:</td>
                                        <td>Php <?php echo number_format($total,2); ?></td>
										<td></td>
                                    </tr>
									<tr align="right">
                                    	
                                        <td>Total:</td>
                                        <td>Php <?php echo number_format($total,2); ?></td>
										<td></td>
                                    </tr>
                                    
                                </table>
							</div>
						</div> 
			
                </div>  
<span style="float:right; margin-right:40px; margin-top:3px">
													<input class="btn btn-success btn-sm" type="button"  value="Save" onclick="javascript:submitMe()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">
													<input class="btn btn-danger btn-sm"  value="Cancel" onclick="javascript:newEntry()" style="width:100px; margin-left:10px; margin-top:10px">
												</span>							
            </div>          
		</div>
		
<script>
	function delItem(id){
	
		var se = document.getElementById('sel').value;		
		//alert(se);
				
		de = se.replace(id, "");
		
		document.getElementById('sel').value = de;		
		//alert(se2);
		
													$.post('<?php echo site_url("finance/getPO/"); ?>',
                                                          { 'id':de },                                                        
                                                                function(result) {
                                                                    //alert(result);
                                                                    // clear any message that may have already been written
                                                                    $('#selected').html(result);							
                                                                }
                                                        );							
	}
</script>