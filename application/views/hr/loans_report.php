

<div class="panel panel-primary">
	<div class="panel-heading">
    	Select loan report criteria:
       
    </div>
    <div class="panel-body">
		<form class="form-horizontal" role="form" id="expenseReport" name="expenseReport"  action="<?php echo HTTP_PATH.'Hr/loans_report_result'; ?>" method="post" enctype='multipart/form-data'>
                <!--- waybill activity --->
                <div style="width:250px; float:left; margin-left:0px; margin-top:10px">
                	<div class="container">
  						
							
                                	
                                    <div style="width:200px; margin-left:20px; float:left">
                                        <div class="form-group">
                                       		Outstanding Loan Report Tool:
                                       	</div>
                                        <div class="form-group">
                                            <label for="vessel">Select Loan Type:</label>
                                            <div>
                                                <select class='form-control' name='loanType'>
                                                    <option></option>
                                                    <option value="ELF">ELF Loan</option>
                                                    <option value="SSS">SSS Loan</option>
                                                    <option value="PagIbig">Pag-Ibig Loan</option>
                                                    <option value="Cash Advance">Cash Advance</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                       		
                                            
                                           <div style="margin-left:0px; margin-top:30px">
					<input class="btn btn-success btn-sm" type="submit"  value="Create Report" id="submitbtn" style="width:100px;margin-left:0px; margin-top:10px ">
					<input class="btn btn-default btn-sm"  value="Cancel" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:10px">
											</div>
                                         
                                            
                                       </div>
                                        
                                        
                                    </div>
                                    
                                    <div style="width:300px; margin-left:70px; float:left">
                                        
                                       
                                       
                                       
                                        
                                                                      
                                         
                                         
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                 	
                                    
                                    
                     
                                </div>
                        
                        	
                      </form>     	 	
    </div>
    

</div>