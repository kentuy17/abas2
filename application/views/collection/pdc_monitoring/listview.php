
		<h2 id="glyphicons-glyphs">Post-dated Checks Monitoring</h2>

		<!--<input type='button' value='Print' id='print_pdc' name='print_pdc' class='btn btn-info btn-m' />-->
		<a href="<?php echo HTTP_PATH.CONTROLLER.'/listview/PDC_monitoring'; ?>" class="btn btn-dark force-pageload">Refresh</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-show-print="true" data-url="<?php echo HTTP_PATH.CONTROLLER.'/load/payments_check_breakdown'; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-show-multi-sort="false"   data-sort-name="status" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false" data-export-data-type="all" data-show-export="true" data-export-types="['excel']" data-row-style='rowStyle'>
				<thead>
					<tr>
						<th data-formatter="runningFormatter">#</th>
						<th data-field="ar_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">AR No.</th>
						<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
						<th data-field="received_on" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Date Received</th>
						<th data-field="check_date" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Check Date</th>
						<th data-field="received_from" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Received From</th>
						<th data-field="check_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Check No.</th>
						<th data-field="bank_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Bank</th>
						<th data-field="bank_branch" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Branch</th>
						<th data-field="amount" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Amount</th>
						<th data-field="remarks" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Remarks</th>
						<th data-field="official_receipt_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">OR No.</th>
						<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-cell-style="cellStylex" >Status</th>

					</tr>
				</thead>
			</table>

<script>

$(function () {
    $('#data-table').bootstrapTable({
        showExport: true,
        exportOptions: {
            fileName: 'Post-dated Checks Monitoring'
        }
    });
});

function rowStyle(row, index) {
	var classes = ['active', 'success', 'info', 'warning', 'danger'];

	if(row.status=='Due Today'){
	  return {classes : "info" };
	}
	if(row.status=='Paid'){
	  return {classes : "success" };
	}
	if(row.status=='Due Tomorrow'){
	  return {classes : "warning" };
	}
	if(row.status=='Overdue'){
	  return {classes : "danger" };
	}
	 return {};
}

function runningFormatter(value, row, index) {
    return index+1;
}

function details(value, row, index) {
	return [
		'<a class="btn btn-info btn-xs btn-block force-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" href="<?php echo HTTP_PATH.CONTROLLER.'/view/acknowledgement_receipt'; ?>/'+ row['id'] +'">View</a>'
	].join('');
}


function printPDCx(){
	

	//var data_str = JSON.stringify(visibleRows);

	window.open("<?php echo HTTP_PATH;?>collection/prints/PDC_monitoring","_blank");

	$.ajax({
		type:'POST',
        dataType: "json",
        data: {'tblData':visibleRows},              
        url : '<?php echo HTTP_PATH;?>collection/prints/PDC_monitoring',
        
    });
	console.log(visibleRows);	

	/*var pageTitle = 'Page Title',
                stylesheet = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
                win = window.open('', 'Print', 'width=500,height=300');
            win.document.write('<html><head><title>' + pageTitle + '</title>' +
                '<link rel="stylesheet" href="' + stylesheet + '">' +
                '</head><body>' + visibleRows + '</body></html>');
            win.document.close();
            win.print();
            win.close();
            return false;*/
}

$('#print_pdc').click(function (e) {
    e.preventDefault();

    var visibleRows = $('#data-table').bootstrapTable('getData');
    console.log(visibleRows);	

    $.ajax({
    	url: '<?php echo HTTP_PATH;?>collection/set_print_data',
        type: 'POST',
        dataType: 'json',
        data: {myData:visibleRows}
        
    })
    .done(function(data) {
        console.log(data);
	    })
	    .fail( function(jqXHR, textStatus, errorThrown) {
	        console.log(jqXHR.responseText);
	        return;
	    });
    
    window.open("<?php echo HTTP_PATH;?>collection/prints/PDC_monitoring","_blank");
});

</script>