

<div class="panel panel-primary">
	<div class="panel-heading">
    	Select your report criteria:
       
    </div>
    <div class="panel-body">
		<form class="form-horizontal" role="form" id="expenseReport" name="expenseReport"  action="<?php echo HTTP_PATH.'Accounting/expense_report'; ?>" method="post" enctype='multipart/form-data'>
                <!--- waybill activity --->
                <div style="width:250px; float:left; margin-left:0px; margin-top:10px">
                	<div class="container">
  						
							
                                	
                                    <div style="width:200px; margin-left:20px; float:left">
                                       
                                        <div class="form-group">
                                            <label for="vessel">Vessel:</label>
                                            <div>
                                                <select class="form-control input-sm" name="vessel" id="vessel">
                                                    <option ></option>                                                    
                                                    	<?php 
															//var_dump($suppliers->id); 
															foreach($vessels as $vessel){														 
														?>
                                                        	<option value="<?php echo $vessel->id ?>"><?php echo $vessel->name; ?></option>
                                                            
                                                            
                                                        <?php } ?>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="include_on">Type:</label>
                                            <div>
                                                <select class="form-control input-sm" name="include_on" id="include_on">
                                                    <option ></option>
                                                    <option  value="Cost of Vessel">Cost of Vessel</option>
                                                    <option  value="Operational Expenses">Operational Expenses</option>
                                                    <option  value="Repairs and Maintenance">Repairs and Maintenance</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="classification">Classification:</label>
                                            <div>
                                                <select class="form-control input-sm" name="classification" id="classification">
                                                    <option ></option>
                                                    <?php 
															//var_dump($suppliers->id); 
															foreach($classifications as $classification){
															 
														?>
                                                        	<option value="<?php echo $classification['id'] ?>"><?php echo $classification['name']; ?></option>
                                                            
                                                        <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div style="width:300px; margin-left:70px; float:left">
                                        
                                       <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="font-size:14px">Select Inclusive date:</legend>
                                            <div class="form-group">
                                            
                                            <div style="float:left; margin-left:20px">
                                                From: <input class="form-control input-sm" type="date" name="from_date" id="from_date"  style="width:150px" />
                                                
                                                
                                            </div>
                                            <div style="float:right">
                                            	To: &nbsp;<input class="form-control input-sm" type="date" name="to_date" id="to_date" style="width:150px" />
                                            </div>    
                                        </div>
                                        </fieldset>
                                       
                                       <div class="form-group">
                                       		
                                            
                                            <div style="margin-left:30px; margin-top:60px">
					<input class="btn btn-success btn-sm" type="button"  value="Create Report" onclick="javascript:createReport()" id="submitbtn" style="width:100px; ">
					<input class="btn btn-default btn-sm"  value="Cancel" onclick="javascript:newEntry()" style="width:100px; ">
											</div>
                                            
                                       </div>
                                        
                                                                      
                                         
                                        
                                         
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                 	
                                    
                                    
                     
                                </div>
                        
                        	
                      </form>     	 	
    </div>
    

</div>