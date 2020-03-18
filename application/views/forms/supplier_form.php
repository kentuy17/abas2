<?php
   $id = "";
   $name = "";
   $address = "";
   $contact_person = "";
   $telephone_no = "";
   $fax_no = "";
   $email = "";
   $category = "";
   $bank_name = "";
   $bank_account_no = "";
   $tin = "";
   $status = "";
   
   if(isset($supplier)){
   		//var_dump($item[0]['id']);exit;
   		$id = $supplier[0]['id'];
   		$name = $supplier[0]['name'];
   		$address = $supplier[0]['address'];
   		$contact_person = $supplier[0]['contact_person'];
   		$telephone_no = $supplier[0]['telephone_no'];
   		$fax_no = $supplier[0]['fax_no'];
   		$email = $supplier[0]['email'];
   		$category = $supplier[0]['category'];
   		$bank_name = $supplier[0]['bank_name'];
   		$bank_account_no = $supplier[0]['bank_account_no'];
   		$tin = $supplier[0]['tin'];
   		$status = $supplier[0]['status'];

   }
   ?>
<style>
   .modal-dialog{width: 400px !important;height: 300px;}
</style>
<form class="form-horizontal" role="form" name="bankForm" id="bankForm" action="<?php echo HTTP_PATH.'forms/add_supplier'; ?>" method="post" style="height: 400px;">
<?php echo $this->Mmm->createCSRF() ?>
   <input type="hidden" name="stat" value="1">
   <input type="hidden" name="id" value="<?php echo $id;  ?>">
   <!--- waybill activity --->
   <div style="width:250px; float:left; margin-left:0px;">
      <div class="container">
         <div class="panel panel-primary" style="font-size:12px; width:550px; height:565px">
            <div class="panel-heading" role="tab" id="headingOne">
               <strong>Supplier Information&nbsp;</strong>
            </div>
            <div class="panel-body" role="tab">
			<p>Label with asterisk (*) is required</p><hr />
               <div style="width:200px; margin-left:20px; float:left">
                  <div class="form-group">
                     <label>Name: *</label>
                     <div>
                        <input class="form-control input-sm" name="name" class="form-control abas_Formcontrol" value="<?php echo $name;  ?>" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Address: *</label>
                     <div>
                        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input class="form-control input-sm ui-autocomplete-input" type="text" name="address" class="form-control abas_Formcontrol" value="<?php echo $address;  ?>" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Contact Person: *</label>
                     <div>
                        <input class="ui-helper-hidden-accessible"></span><input class="form-control input-sm ui-autocomplete-input" type="text" name="contact_person" class="form-control" value="<?php echo $contact_person;  ?>" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Telephone Number: *                                                        </label>
                     <div>
					 <input class="ui-helper-hidden-accessible"></span><input class="form-control input-sm ui-autocomplete-input" type="text" name="telephone_no" class="form-control" value="<?php echo $telephone_no;  ?>" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Fax Number:</label>
                     <div id="subcategory">
                        <input type="text" name="fax_no" class="form-control input-sm ui-autocomplete-input" value="<?php echo $fax_no;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Email:</label>
                     <div>
                        <input type="email" name="email" class="form-control input-sm ui-autocomplete-input" value="<?php echo $email;  ?>">			
                     </div>
                  </div>
                  <div class="form-group">
                  </div>
               </div>
               <div style="width:200px; margin-left:70px; float:left">
                  <div class="form-group">
                     <label>Category: </label>
                     <div>
                        <input type="text" name="category" class="form-control input-sm ui-autocomplete-input" value="<?php echo $category;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Bank Name:</label>
                     <div>
                        <input type="text" name="bank_name" class="form-control input-sm" value="<?php echo $bank_name;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label>Bank Account Number:</label>
                     <div>
                        <input type="text" name="bank_account_no" class="form-control input-sm" value="<?php echo $bank_account_no;  ?>">
                     </div>
                  </div>
				  <div class="form-group">
                     <label>TIN Number:</label>
                     <div>
                        <input type="text" name="tin" class="form-control input-sm" value="<?php echo $tin;  ?>">
                     </div>
                  </div>
				  <div class="form-group">
                     <label>Status:</label>
                     <div>
                        <input type="text" name="status" class="form-control input-sm" value="<?php echo $status;  ?>">
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