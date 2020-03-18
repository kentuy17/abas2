<h2>Accounts Payable Vouchers</h2>
<br>
<ul class="nav nav-tabs" id="approval_tab">   
   	  <li class="active"><a href="<?php echo HTTP_PATH?>/accounting/accounts_payable_voucher/listview_rr" data-target="#rr" class="media_node active span" id="rr_tab" data-toggle="tabajax" rel="tooltip"> For Processing</a></li>
      <li><a href="<?php echo HTTP_PATH?>accounting/accounts_payable_voucher/listview_apv" data-target="#apv" class="media_node span" id="apv_tab" data-toggle="tabajax" rel="tooltip">For Printing</a></li>
 </ul>
<div class="tab-content">
	<div class="tab-pane active" id="rr"></div>
	<div class="tab-pane" id="apv"></div>
</div>

<script type="text/javascript">
$( document ).ready(function() {
	var $this = $('#rr_tab'),
	    loadurl = '<?php echo HTTP_PATH?>/accounting/accounts_payable_voucher/listview_rr',
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
