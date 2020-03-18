


	<div class="panel panel-danger">
		<div class="panel-heading">
			<span class="close" aria-hidden="true" data-dismiss="modal">&times;</span>
			<h4><strong>Search Voucher</strong></h4>
		</div>
		<form class="form-horizontal" role="form" name="search_voucher" id="search_voucher" action="<?php echo HTTP_PATH.'accounting/search_cv_voucher/'; ?>" method="post">
	
        <div class="modal-body">
			<div class="col-md-8 well">
            	
				<?php echo $this->Mmm->createCSRF() ?>
				<p style="color:#577686; margin-left:10px">Please enter either transaction code or check number.</p>
				<div class="col-xs-12 col-sm-6">
					<label>Enter Transaction Code:</label>
					<input class="form-control input-sm" type="text" placeholder="Enter transaction code" name="transaction_code" id="transaction_code">
                    
				</div>
				
				<div class="col-xs-12 col-sm-6">
					<label for="check_no">Check Number: </label>
					<input class="form-control input-sm" type="text" placeholder="Check Number" name="check_no" id="check_no">
                    
				</div>
				
				<div class="col-xs-4">
                	&nbsp;<br />
					<input type='button' value='Search' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
				</div>
			</div>
			
              
			<div class="clearfix"></div>

		</div>
	</form>
	</div>

<script>
	
	
	function checkInput() {
		
		
		var check_number=document.getElementById('check_no').value;
		var transaction_code=document.getElementById('transaction_code');
		
		if(check_number=="" && transaction_code==""){
			alert('Please enter transaction code or check number.');
		}		
		else {
			document.getElementById("search_voucher").submit();
			return true;
		}
	}
</script>
