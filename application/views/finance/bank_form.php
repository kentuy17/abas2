<?php
   $id = "";
   $name = "";
   $account_name = "";
   $account_no = "";
   $account_type = "";
   $currency = "";
   $contact_person = "";
   $contact_no = "";
   $fax_no = "";
   $email = "";
   $stat = "";
   $account_code = "";
   
   if(isset($bank)){
   		//var_dump($item[0]['id']);exit;
   		$id = $bank[0]['id'];
   		$name = $bank[0]['name'];
   		$account_name = $bank[0]['account_name'];
   		$account_no = $bank[0]['account_no'];
   		$account_type = $bank[0]['account_type'];
   		$currency = $bank[0]['currency'];
   		$contact_person = $bank[0]['contact_person'];
   		$contact_no = $bank[0]['contact_no'];
   		$fax_no = $bank[0]['fax_no'];
   		$email = $bank[0]['email'];
   		$stat = $bank[0]['stat'];
		$account_code = $bank[0]['account_code'];
   }
   ?>
<style>
   .modal-dialog{width: 400px !important;height: 300px;}
</style>
<form class="form-horizontal" role="form" name="bankForm" id="bankForm" action="<?php echo HTTP_PATH.'finance/add_bank'; ?>" method="post" style="height: 400px;">
	<?php echo $this->Mmm->createCSRF() ?>
   <input type="hidden" name="stat" value="1">
   <input type="hidden" name="id" value="<?php echo $id;  ?>">
   <!--- waybill activity --->
   <div style="width:250px; float:left; margin-left:0px;">
      <div class="container">
         <div class="panel panel-primary" style="font-size:12px; width:550px; height:510px">
            <div class="panel-heading" role="tab" id="headingOne">
               <strong>Bank Information&nbsp;</strong>
            </div>
            <div class="panel-body" role="tab">
			<p>Label with asterisk (*) is required</p><hr />
               <div style="width:200px; margin-left:20px; float:left">
                  <div class="form-group">
                     <label for="voucher_no">Name: *</label>
                     <div>
                        <input class="form-control input-sm" name="name" class="form-control abas_Formcontrol" value="<?php echo $name;  ?>" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="payee">Account Name: *</label>
                     <div>
                        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input class="form-control input-sm ui-autocomplete-input" type="text" name="account_name" class="form-control abas_Formcontrol" value="<?php echo $account_name;  ?>" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="particular">Account Number: *</label>
                     <div>
                        <input class="ui-helper-hidden-accessible"></span><input class="form-control input-sm ui-autocomplete-input" type="text" name="account_no" class="form-control" value="<?php echo $account_no;  ?>" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="account_code">Account Code (code used in Chart of Accounts): *</label>
                     <div>
                        <input class="ui-helper-hidden-accessible"></span><input class="form-control input-sm ui-autocomplete-input" type="text" name="account_code" class="form-control" value="<?php echo $account_code; ?>" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="category">Account Type: *                                                        </label>
                     <div>
					 <select name="account_type"  class="form-control input-sm ui-autocomplete-input" value="<?php echo $account_type;  ?>" required>
						  <option value="Checking account">Checking account</option>
						  <option value="Savings account">Savings account</option>
						  <option value="Individual retirement account">Individual retirement account</option>
						</select>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="subcategory">Currency: *</label>
                     <div id="subcategory">
                        <input type="text" name="currency" class="form-control input-sm ui-autocomplete-input" value="<?php echo $currency;  ?>" required>
                     </div>
                  </div>
                  <div class="form-group" style="display:none">
                     <label for="amount">Contact Person:</label>
                     <div>
                        <input type="text" name="contact_person" class="form-control input-sm ui-autocomplete-input" value="<?php echo $contact_person;  ?>">			
                     </div>
                  </div>
                  <div class="form-group">
                  </div>
               </div>
               <div style="width:200px; margin-left:70px; float:left">
                  <div class="form-group">
                     <label for="amount">Contact Person:</label>
                     <div>
                        <input class="form-control input-sm" type="text" name="contact_person" id="contact_person" value="<?php echo $contact_person;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="amount">Contact Number: </label>
                     <div>
                        <input type="text" name="contact_no" class="form-control input-sm ui-autocomplete-input" value="<?php echo $contact_no;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="amount">Fax Number:</label>
                     <div>
                        <input type="text" name="fax_no" class="form-control input-sm" value="<?php echo $fax_no;  ?>">
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="amount">Email:</label>
                     <div>
                        <input type="email" name="email" class="form-control input-sm" value="<?php echo $email;  ?>">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default btn-xs" data-dismiss="modal" onclick="myFunction()">Close</button>
               <button type="submit" class="btn btn-primary btn-xs">Save Bank</button>
            </div>
         </div>
      </div>
   </div>
</form>