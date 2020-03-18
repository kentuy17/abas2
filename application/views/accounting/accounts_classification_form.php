<div class="panel panel-primary">
	<div class="panel-heading">
		<h4 class="panel-title">
			Add New Account classification
		</h4>
	</div>
	<div class="panel-body">
		<form action="" role='form' method='POST' id='chart_of_accounts_form' enctype='multipart/form-data'>
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class='col-xs-12 col-sm-6'>
				<label for='financial_statement_code'>Financial Statement Code</label>
				<input type='text' id='financial_statement_code' name='financial_statement_code' placeholder='Financial Statement Code' class='form-control' value='' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='general_ledger_code'>General Ledger Code</label>
				<input type='text' id='general_ledger_code' name='general_ledger_code' placeholder='General Ledger Code' class='form-control' value='<?php echo $a['general_ledger_code']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-12'>
				<label for='name'>Account Name</label>
				<input type='text' id='name' name='name'  placeholder='Account Name' class='form-control' value='<?php echo $a['name']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='general_ledger_code'>Account Classification</label>
				<select class="form-control" name="classification">
					<?php foreach ($classification as $row) { ?>
						<option value="<?=$row->id?>"><?=$row->name?></option>
					<?php } ?>
				</select>
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='general_ledger_code'>Account Type</label>
				<select class="form-control" name="type">
					<option value="Opex">Opex</option>
					<option value="Capex">Capex</option>
				</select>
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<label for='description'>Description</label>
				<textarea name="description" id="description" class="form-control"><?php echo $a['description']; ?></textarea>
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkCOAform()' />
				</div>
		</form>
	</div>
</div>