<h2>Approval Area</h2>
<br>
<ul class="nav nav-tabs" id="approval_tab">   
   	  <li class="active"><a href="<?php echo HTTP_PATH?>/manager/purchase_requests/listview" data-target="#purchase_requests" class="media_node active span" id="requisitions_tab" data-toggle="tabajax" rel="tooltip"> Material/Services Requests </a></li>
      <li><a href="<?php echo HTTP_PATH?>manager/canvass/listview" data-target="#canvass" class="media_node span" id="canvass_tab" data-toggle="tabajax" rel="tooltip">Canvass</a></li>
      <li><a href="<?php echo HTTP_PATH?>manager/purchase_orders/listview" data-target="#purchase_orders" class="media_node span" id="purchase_orders_tab" data-toggle="tabajax" rel="tooltip">Purchase Orders</a></li>
      <li><a href="<?php echo HTTP_PATH?>manager/job_orders/listview" data-target="#job_orders" class="media_node span" id="job_orders_tab" data-toggle="tabajax" rel="tooltip">Job Orders</a></li>
      <li><a href="<?php echo HTTP_PATH?>manager/request_for_payment/listview" data-target="#request_for_payments" class="media_node span" id="request_for_payments_tab" data-toggle="tabajax" rel="tooltip">Request for Payments</a></li>
       <li><a href="<?php echo HTTP_PATH?>manager/accountability_forms/listview" data-target="#accountability_forms" class="media_node span" id="accountability_forms_tab" data-toggle="tabajax" rel="tooltip">Accountability Forms</a></li>
       <li><a href="<?php echo HTTP_PATH?>manager/disposal_slips/listview" data-target="#disposal_slips" class="media_node span" id="disposal_slips_tab" data-toggle="tabajax" rel="tooltip">Disposal Slips</a></li>
      <!--<li><a href="<?php //echo HTTP_PATH?>/manager/check_vouchers/listview" data-target="#check_vouchers" class="media_node span" id="check_vouchers_tab" data-toggle="tabajax" rel="tooltip">Check Vouchers</a></li>-->
 </ul>

<div class="tab-content">
	<div class="tab-pane active" id="purchase_requests"></div>
	<div class="tab-pane" id="canvass"></div>
	<div class="tab-pane" id="purchase_orders"></div>
	<div class="tab-pane" id="job_orders"></div>
	<div class="tab-pane" id="request_for_payments"></div>
	<div class="tab-pane" id="accountability_forms"></div>
	<div class="tab-pane" id="disposal_slips"></div>
	<div class="tab-pane" id="check_vouchers"></div>
</div>

<script type="text/javascript">
$( document ).ready(function() {
	var $this = $('#requisitions_tab'),
	    loadurl = '<?php echo HTTP_PATH?>/manager/purchase_requests/listview',
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