<h2>Check Vouchers</h2>
<br>
<ul class="nav nav-tabs" id="approval_tab">   
   	  <li class="active"><a href="<?php echo HTTP_PATH?>/accounting/check_voucher/listview_apv" data-target="#apv" class="media_node active span" id="apv_tab" data-toggle="tabajax" rel="tooltip"> For Processing (PO) </a></li>
      <li><a href="<?php echo HTTP_PATH?>accounting/check_voucher/listview_rfp" data-target="#rfp" class="media_node span" id="rfp_tab" data-toggle="tabajax" rel="tooltip">For Processing (Non-PO)</a></li>
      <li><a href="<?php echo HTTP_PATH?>accounting/check_voucher/listview_cv" data-target="#cv" class="media_node span" id="cv_tab" data-toggle="tabajax" rel="tooltip">For Printing</a></li>
 </ul>

<div class="tab-content">
	<div class="tab-pane active" id="apv"></div>
	<div class="tab-pane" id="rfp"></div>
	<div class="tab-pane" id="cv"></div>
</div>

<script type="text/javascript">
$( document ).ready(function() {
	var $this = $('#apv_tab'),
	    loadurl = '<?php echo HTTP_PATH?>/accounting/check_voucher/listview_apv',
	    targ = $this.attr('data-target');
	    $.get(loadurl, function(data) {
	        $(targ).html(data);
	    });
	    $this.tab('show');
	    return false;
});

	$('[data-toggle="tabajax"]').click(function(e) {
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');

	    $.get(loadurl, function(data) {
	        $(targ).html(data);
	    });

	    $this.tab('show');
	    return false;
	});
</script>
