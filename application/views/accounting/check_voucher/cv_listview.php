<?php 
	if($this->Abas->checkPermissions("accounting|export_check_voucher",FALSE)){
		echo '<br><a href="#" class="btn btn-success exclude-pageload" onclick="exportSelected();">Export to UB Template</a>';
	}
?>
<form role="form" id="export_form" action="<?php echo HTTP_PATH.'accounting/check_voucher/export_template'; ?>" name="export_form" method="post">
	<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.'accounting/check_voucher/load_cv'?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[100, 200, 500, 1000, 2500, 5000]" data-page-size="100" data-search="true" data-filter-control="true" data-filter-strict-search="false">
		<thead>
			<tr>
				<th data-field="export_cv" data-formatter="export_cv" data-events="operateEvents" data-align="center" data-align="center">Export?</th>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
				<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
				<th data-field="company_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-field="payee_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payee</th>
				<th data-field="payee_type" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payee Type</th>
				<th data-field="bank_account" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Bank Account</th>
				<th data-field="check_num" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Check No.</th>
				<th data-field="check_date" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Check Date</th>
				<th data-field="amount" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Check Amount</th>
				<th data-field="transaction_type" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Trans Type</th>
				<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
				<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
				<th data-field="verified_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Verified By</th>
				<th data-field="verified_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Verified On</th>
				<th data-field="approved_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Approved By</th>
				<th data-field="approved_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Approved On</th>
				<th data-field="status" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
				<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
			</tr>
		</thead>
	</table>
</form>
<script type="text/javascript">
	function exportSelected(){
	   var export_ids = [];
	   $('.selectMe').each(function(){
  			if($(this).prop('checked')==true){
  				export_ids.push($(this).val());
  			}
   	   });
      if(export_ids.length>0){
	      bootbox.confirm({
				title: "Export",
				size: 'small',
			    message: 'This will export the selected CV to the UB template. Do you want to proceed?',
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
			    		$('#export_form').submit();
						/*$.ajax({
						  url: '<?php //echo HTTP_PATH."accounting/check_voucher/export"; ?>',
						  data: {exported_ids: export_ids},
						  type: 'post',
						  success: function(data) {
						    //download_file('<?php //echo WPATH."assets/downloads/accounting/ub_exports/"?>',data+'.txt');
							toastr['success']("Check Voucher(s) have been succesfully exported!", "ABAS says:");
						  }
						});*/
			    	}
			    }
		  });
      }else{
      	 toastr['warning']("Please select the CV to be exported!", "ABAS says:");
      }
    }
    function voidCheckVoucher(){
	      bootbox.confirm({
				title: "Void Check Voucher",
				size: 'small',
			    message: 'This will void the CV and reverses its related entries. Are you sure you want proceed?',
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
			    		
			    	}
			    }
		  });
      
    }
	function export_cv(value, row, index) {
		return [
			'<input type="checkbox" class="form-control selectMe" name="cv_id[]" value="'+ row['id'] +'">'
		].join('');
	}
	function details(value, row, index) {
		return [
			'<div class="btn-group"><button type="button" class="btn btn-info btn-xs ">Print</a></button><button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu" role="menu"><li><a target="_blank" href="<?php echo HTTP_PATH.'accounting/check_voucher/print'; ?>/'+ row['id'] +'">Letter</a></li><li><a target="_blank" href="<?php echo HTTP_PATH.'accounting/check_voucher/print'; ?>/'+ row['id'] +'/legal">Legal</a></li></ul></div>'//,
			//'<a class="btn btn-info btn-xs btn-block force-pageload" target="_blank" href="<?php //echo HTTP_PATH.'accounting/check_voucher/print'; ?>/'+ row['id'] +'">Print</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});
	function download_file(fileURL, fileName) {
	    // for non-IE
	    if (!window.ActiveXObject) {
	        var save = document.createElement('a');
	        save.href = fileURL;
	        save.target = '_blank';
	        var filename = fileURL.substring(fileURL.lastIndexOf('/')+1);
	        save.download = fileName || filename;
		       if ( navigator.userAgent.toLowerCase().match(/(ipad|iphone|safari)/) && navigator.userAgent.search("Chrome") < 0) {
					document.location = save.href; 
		// window event not working here
				}else{
			        var evt = new MouseEvent('click', {
			            'view': window,
			            'bubbles': true,
			            'cancelable': false
			        });
			        save.dispatchEvent(evt);
			        (window.URL || window.webkitURL).revokeObjectURL(save.href);
				}	
	    }

	    // for IE < 11
	    else if ( !! window.ActiveXObject && document.execCommand)     {
	        var _window = window.open(fileURL, '_blank');
	        _window.document.close();
	        _window.document.execCommand('SaveAs', true, fileName || fileURL)
	        _window.close();
	    }
	}
</script>