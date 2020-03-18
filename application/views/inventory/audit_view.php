<?php


$title='';
if(isset($_SESSION['abas_login']['user_location'])){

	//set inventory location
	$user_location = $_SESSION['abas_login']['user_location'];

	switch ($user_location) {
		case 'Makati':
			$title = "You are using Makati Inventory System";

			break;
		case 'NRA':
			$title = "You are using NRA Inventory System";

			break;
		case 'Tayud':
			$title = "You are using Tayud Inventory System";

			break;
		case 'Tacloban':
			$title = "You are using Tacloban Inventory System";

			break;
	}

}//else{
	//header("location:".HTTP_PATH."home");die();//user cannot use inventory without user location
//}


//$link = HTTP_PATH.'Inventory/inventory_report_form/';




?>
<?php //$this->load->view('includes_header'); ?>

<script>
 $(document).ready(function() {
 $('#datatable-responsive').DataTable();
  });
</script>


                  <div class="x_title">
                    <h2>Inventory Count</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>

                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                      <li>s
                      <a class="like" href="<?php echo HTTP_PATH ?>inventory/print_audit_form" target="_blank" title="Print Audit Form">
                                	<button type="button" class="btn btn-default btn-xs" style="background:#000; color:#FFFFFF"><i class="glyphicon glyphicon-print"></i> Print Inventory Count Form</button>
                                    </a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                <div class="x_content">

                            <span style="float:left; margin-left:10px; margin-top:-5px">
                               
								<?php if($this->Abas->checkPermissions("inventory|audit",false)): ?>
                                    <a class="like" href="<?php echo HTTP_PATH ?>inventory/audit_form"  data-toggle="modal" data-target="#modalDialog" title="Purchase Order">
                                        <button type="button" class="btn btn-default btn-xs" style="background:#000; color:#FFFFFF"><i class="glyphicon glyphicon-list"></i> Submit Inventory Count</button>
                                    </a>
                              		&nbsp;&nbsp;&nbsp;
                                    
								<?php endif; ?>
                                    
                                 
                            </span>

							<form class="form-horizontal" role="form" id="auditForm" name="auditForm"  action="<?php echo HTTP_PATH.'inventory/add_audit'; ?>" method="post" enctype='multipart/form-data'>
                            	
                                <div style="margin-bottom:20px">
                                	Counted By: <input type="text" name="counted_by" id="counted_by" required>
                                    &nbsp;&nbsp;
                                    <input type="submit" value="Submit">                                  
                                </div>
                            <table id="datatable-responsive" style="margin-top:10px;font-size:12px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%" >
										<thead>
											<tr align="center">

                                                <th width="3%" class="col-sm-1" data-align="center" data-sortable="true">Item Code</th>
												<th  width="28%" class="col-sm-3"  data-sortable="true">Description</th>
												<!--
                                                <th width="10%" class="col-sm-2" >Type</th>
                                                -->
												<th width="10%" class="col-sm-2"  data-sortable="true">Category</th>
                                                <th width="5%" class="col-sm-1" data-align="center"  data-sortable="true">Unit</th>
                                                <th width="3%" class="col-sm-0" data-align="center"    data-sortable="true">Current Qty </th>
                                                <th width="5%" class="col-sm-0" data-align="center"   data-sortable="true">Counted Qty</th>
                                               
												<th width="5%" class="col-sm-0" data-align="center"  data-sortable="true">Difference</th>
                                                <th width="5%" class="col-sm-0" data-align="center"  data-sortable="true">Remarks</th>
                                                
												
											</tr>
										</thead>
											<tbody>
											<?php
												//var_dump($expenses);
												//exit;
												foreach($items as $item){
												
												
												//get category
												$sq = "SELECT * FROM `inventory_category` WHERE id =".$item['category'];
												$r = $this->db->query($sq);
												$cat = $r->result_array();

												//get category
												$sq1 = "SELECT * FROM `inventory_location` WHERE item_id =".$item['id'];

												$r1 = $this->db->query($sq1);
												$qt1 = $r1->result_array();


												if(count($qt1) > 0){
													$tayud_qty = $qt1[0]['tayud_qty'];
													$nra_qty = $qt1[0]['nra_qty'];
													$mkt_qty = $qt1[0]['mkt_qty'];
													$total_qty = $tayud_qty + $nra_qty + $mkt_qty;
												}else{
													$tayud_qty = 0;
													$nra_qty = 0;
													$mkt_qty = 0;
													$total_qty = 0;
												}
												
												
												//manage location qty
												if($_SESSION['abas_login']['user_location']== 'Makati'){
													$qty = $mkt_qty;
												}elseif($_SESSION['abas_login']['user_location']== 'NRA'){
													$qty = $nra_qty;
												}elseif($_SESSION['abas_login']['user_location']== 'Tayud'){
													$qty = $tayud_qty;
												}
												
												//echo $tayud_q1ty.'<br>';
												//var_dump($qt1);

											?>
												<tr>

                                                    <td align="center"><?php echo $item['item_code']; ?></td>
													<td align="left"><?php echo $item['description']; ?></td>													
													<td align="left"><?php echo $cat[0]['category']; ?></td>
                                                     <td align="center"><?php echo strtolower($item['unit']); ?></td>
													<td align="center"><?php echo $qty; ?></td>
                                                    <td align="center"><input type="text" id="<?php echo $item['id'].'_counted'; ?>" name="<?php echo $item['id'].'_counted'; ?>" value="0"  style="width:50px" 
                                                    onKeyPress="                                                    	
                                                       		var q = <?php echo $qty; ?>;
                                                        	var d = '<?php echo $item['id'].'_diff'; ?>';                                                           	var b = q - this.value;	
                                                    	if(event.keyCode==13){
                                                       	 	document.getElementById(d).value = b;                                    
                                                            return false;
                                                            
                                                        }" ></td>                                                    
                                                    <td align="center"><input type="text" id="<?php echo $item['id'].'_diff'; ?>" name="<?php echo $item['id'].'_diff'; ?>" value="0" style="width:50px" ></td>
                                                   
													<td>
                                                    <input type="text" id="<?php echo $item['id'].'_remark'; ?>" name="<?php echo $item['id'].'_remark'; ?>" value="0"  style="width:90px" >
                                                    </td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
							</form>

							</div>
                </div>
           

	 <?php //$this->load->view("includes_footer_scripts"); ?>

<script>







	$('input').on('click', function(){
	  var valeur = 0;
	  $('input:checked').each(function(){
		   if ( $(this).attr('value') > valeur )
		   {
			   valeur =  $(this).attr('value');
		   }
	  });
	  $('.progress-bar').css('width', valeur+'%').attr('aria-valuenow', valeur);
	});

	function newEntry(){
		window.location.assign("<?php echo HTTP_PATH.'Inventory' ?>")
		document.forms['itemForm'].reset();
	}

	function submitMe(){

		var id = document.getElementById('id').value;
		var i = document.getElementById('item_code').value;
		var d = document.getElementById('description').value;
		var p = document.getElementById('particular').value;
		var u = document.getElementById('unit').value;
		var uc = document.getElementById('unit_cost').value;
		var q = document.getElementById('qty').value;


		//if(id !== ''){

			/*
			alert('Editing is not allowed');
			return false;
		}else{*/

			if(i == ''){
				alert('Please enter Item Code');
				document.getElementById('item_code').focus();
				return false;
			}else if(d == ''){
				alert('Please enter Description');
				document.getElementById('description').focus();
				return false;
			}else if(u == ''){
				alert('Please select unit');
				document.getElementById('unit').focus();
				return false;
			}else if(q == ''){
				alert('Please enter quantity on hand');
				document.getElementById('qty').focus();
				return false;
			}else{
				document.forms['itemForm'].submit();
			}

		//}


	}

	function createReport(){

		document.forms['expenseReport'].submit();

	}

</script>
</body>
</html>
