<?php
   $id = "";
   $date_requested= "";
   $date_released = "";
   $requested_by = "";
   $purpose = "";
   $amount = "";
   $department = "";
  $type = "";
   
   if(isset($cash)){
   		//var_dump($item[0]['id']);exit;
   		$id = $cash[0]['id'];
   		$requested_by = $cash[0]['requested_by'];
   		$purpose = $cash[0]['purpose'];
   		$amount = $cash[0]['amount'];
   		$department = $cash[0]['department'];
   		$type = $cash[0]['type'];
   		//var_dump($date_released);exit;

   }
   ?>
<style>
   .modal-dialog{width: 400px !important;height: 300px;}
</style>
<form class="form-horizontal" role="form" name="bankForm" id="bankForm" action="<?php echo HTTP_PATH.'finance/cash_for_funding'; ?>" method="post" style="height: 382px;" onsubmit="return confirm('Are you sure you want to approve?');">
<?php echo $this->Mmm->createCSRF() ?>
   <input type="hidden" name="stat" value="1">
   <input type="hidden" name="id" value="<?php echo $id;  ?>">
  <!--- waybill activity --->
   <div style="width:250px; float:left; margin-left:0px;">
      <div class="container">
         <div class="panel panel-primary" style="font-size:12px; width:550px; height:401px">
            <div class="panel-heading" role="tab" id="headingOne">
               <strong>Cash Advance Information&nbsp;</strong>
            </div>
            <div class="panel-body" role="tab">
			<p>Label with asterisk (*) is required</p><hr />
               <div style="width:466px; margin-left:20px; float:left">
			   <div class="row">
			    <div class="col-md-6">
               
				  <div class="form-group">
                     <label>Requested by:</label>
                     <div>
						 <p><?php echo $requested_by;  ?></p>
                     </div>
                  </div>
				  <div class="form-group">
                     <label>Amount:</label>
                     <div>
					  <p><?php echo $amount;  ?></p>
                     </div>
                  </div>
				  <div class="form-group">
                     <label>Purpose:</label>
                     <div>
					     <p><?php echo $purpose;  ?></p>
                     </div>
                  </div>
                  </div>
				  <div class="col-md-6">
				  <div class="form-group">
                     <label>Department:</label>
                     <div>
					 <p><?php echo $department;  ?></p>
                     </div>
                  </div>
				  <div class="form-group">
                     <label>Type of Request:</label>
						<p><?php echo $type;  ?></p>
                  </div>
                  </div>
				  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal" onclick="myFunction()">Close</button>
               <button type="submit" class="btn btn-primary">For Funding Approval</button>
            </div>
         </div>
      </div>
   </div>
</form>



