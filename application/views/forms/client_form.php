<?php
    $id = "";
	$company = "";
	$address = ""; 
	$city = "";
	$province = "";
	$country = "";
	$contact_no = "";
	$fax_no = "";
	$email = "";
	$website = "";
	$contact_person = "";
	$position = "";
	$lead_person = "";
	$tin_no = "";
   
   if(isset($client)){
		$id = $client->id; 
		$company = $client->company; 
		$address = $client->address; 
		$city = $client->city; 
		$province = $client->province; 
		$country = $client->country; 
		$contact_no = $client->contact_no; 
		$fax_no = $client->fax_no; 
		$email = $client->email; 
		$website = $client->website; 
		$contact_person = $client->contact_person; 
		$position = $client->position; 
		$lead_person = $client->lead_person; 
		$tin_no = $client->tin_no; 
   }
   ?>
<style>
   .modal-dialog{width: 400px !important;height: 300px;}
</style>
<form class="form-horizontal" role="form" name="bankForm" id="bankForm" action="<?php echo HTTP_PATH.'forms/add_client'; ?>" method="post" style="height: 400px;">
<?php echo $this->Mmm->createCSRF() ?>
   <input type="hidden" name="stat" value="1">
   <input type="hidden" name="id" value="<?php echo $id;  ?>">
   <!--- waybill activity --->
   <div style="width:250px; float:left; margin-left:0px;">
      <div class="container">
         <div class="panel panel-primary" style="font-size:12px; width:550px; height:614px">
            <div class="panel-heading" role="tab" id="headingOne">
               <strong>Client Information&nbsp;</strong>
            </div>
            <div class="panel-body" role="tab">
   <p>Label with asterisk (*) is required</p><hr />
               <div style="width:200px; margin-left:20px; float:left">
                  <div class="form-group">
                     <label>Company: *</label>
                     <div>
                        <input class="form-control input-sm" name="company" class="form-control abas_Formcontrol" value="<?php echo $company;  ?>" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Address:</label>
                     <div>
                        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input class="form-control input-sm ui-autocomplete-input" type="text" name="address" class="form-control abas_Formcontrol" value="<?php echo $address;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label>City: </label>
                     <div>
                        <input class="ui-helper-hidden-accessible"></span><input class="form-control input-sm ui-autocomplete-input" type="text" name="city" class="form-control" value="<?php echo $city;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Province:</label>
                     <div>
						<input class="ui-helper-hidden-accessible"></span><input class="form-control input-sm ui-autocomplete-input" type="text" name="province" class="form-control" value="<?php echo $province;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Country:</label>
                     <div id="subcategory">
                        <input type="text" name="country" class="form-control input-sm ui-autocomplete-input" value="<?php echo $country;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Contact No:</label>
                     <div>
                        <input type="text" name="contact_no" class="form-control input-sm ui-autocomplete-input" value="<?php echo $contact_no;  ?>">   
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Fax No:</label>
                     <div>
                        <input type="text" name="fax_no" class="form-control input-sm ui-autocomplete-input" value="<?php echo $fax_no;  ?>">   
                     </div>
                  </div>
               </div>
               <div style="width:200px; margin-left:70px; float:left">
                  <div class="form-group">
                     <label>Email: </label>
                     <div>
                        <input type="email" name="email" class="form-control input-sm ui-autocomplete-input" value="<?php echo $email;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Website:</label>
                     <div>
                        <input type="text" name="website" class="form-control input-sm" value="<?php echo $website;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Contact Person:</label>
                     <div>
                        <input type="text" name="contact_person" class="form-control input-sm" value="<?php echo $contact_person;  ?>">
                     </div>
                  </div>
				<div class="form-group">
                     <label>Position:</label>
                     <div>
                        <input type="text" name="position" class="form-control input-sm" value="<?php echo $position;  ?>">
                     </div>
                  </div>
				<div class="form-group">
                     <label>Lead Person:</label>
                     <div>
                        <input type="text" name="lead_person" class="form-control input-sm" value="<?php echo $lead_person;  ?>">
                     </div>
                  </div>
				<div class="form-group">
                     <label>TIN No:</label>
                     <div>
                        <input type="text" name="tin_no" class="form-control input-sm" value="<?php echo $tin_no;  ?>">
                     </div>
                  </div>
      
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default btn-xs" data-dismiss="modal" onclick="myFunction()">Close</button>
               <button type="submit" class="btn btn-primary btn-xs">Save</button>
            </div>
         </div>
      </div>
   </div>
</form>