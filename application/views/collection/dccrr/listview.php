
		<h2 id="glyphicons-glyphs">Daily Cash and Checks Recieved Report</h2>

		<!--<input type="button" class="btn btn-success btn-m exclude-pageload" onclick="javascript:createDCCRR();" value="Add"/>-->
		<?php if($this->Abas->checkPermissions("finance|add_dccrr",false)): ?>
		<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/DCCRR';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Add</a>
		<?php endif ?>

		<a href="<?php echo HTTP_PATH.CONTROLLER.'/listview/DCCRR'; ?>" class="btn btn-dark force-pageload">Refresh</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.CONTROLLER.'/load/payments_daily_report'; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="true">
				<thead>
					<tr>

						<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>

						<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
					
						<th data-field="company_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>

						<th data-field="created_by" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>

						<th data-field="created_on" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Date</th>

						<th data-field="total_ending_balance" data-align="right" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false"> Ending Balance</th>

						<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
						<?php if($this->Abas->checkPermissions("finance|add_dccrr",false)): ?>
						<th data-field="manage" data-formatter="manage" data-events="operateEvents" data-align="center" data-align="center">Details</th>
						<?php endif ?>

					</tr>
				</thead>
			</table>

<script>


	function createDCCRR(){
		bootbox.prompt({
		    title: "Please specify the date of the DCCRR you will be creating?",
		    inputType: 'date',
		    callback: function (result) {

		        if(result==null || result==""){
		    		console.log("Do nothing");
		    	}else{

		    		 window.location.href = "<?php echo HTTP_PATH.CONTROLLER;?>/insert/DCCRR/"+result;
		    		
		    	}
		    }
		});
	}


	function voidDCCRR(id){
		bootbox.confirm({
			title: "Void DCCRR",
			size: 'small',
		    message: "Are you sure you want to void this DCCRR?",
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn-success'
		        },
		        cancel: {
		            label: 'No',
		            className: 'btn-danger'
		        }
		    },
		    callback: function (result) {
		    	if(result){
			        window.location.href = "<?php echo HTTP_PATH.CONTROLLER.'/cancel/DCCRR/';?>" + id;
		    	}
		    }
		});
	}

	function manage(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.CONTROLLER.'/prints/DCCRR'; ?>/'+ row['id'] +'" target="_blank">Print</a><a class="btn btn-danger btn-xs btn-block" onclick="javascript:voidDCCRR('+ row['id'] +');">Void</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

	

</script>