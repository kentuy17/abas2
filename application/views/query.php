<h4>Database Manual Query</h4>
<form action="<?php echo HTTP_PATH."system/query"; ?>" method="POST" id="query_form">
	<?php echo $this->Mmm->createCSRF(); ?>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<label for="sql_query">SQL Query:*</label>
		<textarea class="form-control" id="sql_query" name="sql_query"></textarea>
		<label for="query_purpose">Purpose of the query:*</label>
		<input type="text" id="query_purpose" name="query_purpose" class="form-control" />
		<hr>
			<label for="result">Result:</label>
			<?php
				if(isset($result)){
					echo "<pre>";
						print_r($result);
					echo "</pre>";
				}
			?>
		<hr>
		<input type="button" class="btn btn-success btn-m pull-right" onclick="javascript:submitForm();" value="Submit"/>
	</div>
</form>
<script type="text/javascript">

function submitForm() {

	var sql_query = $('#sql_query').val();
	var purpose = $('#query_purpose').val();
	
	if(sql_query=='' || purpose==''){
		toastr['warning']('Please complete all required fields (*)', "ABAS says:");
		return false;
	}else{
		document.getElementById("query_form").submit();
		return true;
	}
	
}
</script>