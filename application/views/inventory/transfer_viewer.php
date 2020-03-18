<?php
 //var_dump($inventory_transfer);

?>

 							
<table><tr><td>
							<?php if($this->Abas->checkPermissions("inventory|inventory_transfer",false)){ ?>
                             	<a class="like" href="<?php echo HTTP_PATH ?>inventory/transfer_form" data-toggle="modal" data-target="#modalDialogNorm" title="New Item">
                                    <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Transfer Inventory</button>
                                </a>
								<!---
                                <a class="like" href="<?php echo HTTP_PATH ?>inventory/receiving_form" data-toggle="modal" data-target="#modalDialog" title="Receiving">
                                    <button type="button" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-import"></i> Receive Inventory Transfer</button>
                                </a>
								--->
							
                               
                            <?php } ?>
</td></tr></table>
<table id="datatable-responsive" style="margin-top:10px;font-size:11px" class="table table-striped table-bordered dt-responsive nowrap jambo_table" cellspacing="0" width="100%" >
										<thead>
											<tr>
                                            	<th colspan="9">INVENTORY TRANSFER</th>
                                            </tr>
                                            <tr align="center">

                                                <th width="15%"  data-align="center" data-sortable="true">Date</th>
												<th  width="5%"   data-sortable="true">Transaction Code</th>
                                                <th  width="30%"   data-sortable="true">Company</th>
                                                <th  width="10%"   data-sortable="true">From</th>
												<th width="10%"  data-sortable="true" >To</th>
                                                <th width="10%" class="col-sm-2" >Requested By</th>
												<th width="25%" class="col-sm-3"  data-sortable="true">Remark</th>
                                                <th width="5%" class="col-sm-1" data-sortable="true">Status</th>
												<th width="4%" data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center">Manage</th>
											</tr>
										</thead>
											<tbody>
											<?php
												//var_dump($expenses);
												//exit;
												
												foreach($inventory_transfer as $inventory){

														//get company name
														$company = $this->Abas->getCompany($inventory['company_id']);
														$status	= ($inventory['is_received']==1)?'transfered':'OTW';
															
											?>
												<tr>

                                                    <td align="center"><?php echo date('F j, Y', strtotime($inventory['transfer_date'])); ?></td>
													<td align="center"><?php echo $inventory['id']; ?></td>
                                                    <td align="left"><?php echo $company->name; ?></td>
                                                    <td  align="left"><?php echo $inventory['from_location']; ?></td>
													<td align="left"><?php echo $inventory['to_location']; ?></td>
													<td align="left"><?php echo $inventory['transfered_by']; ?></td>
                                                    <td align="left"><?php echo $inventory['remark']; ?></td>
													<td align="center"><?php echo $status; ?></td>
                                                    
													<td>
                                                    <a class="like" href="<?php echo HTTP_PATH.'inventory/transfer_receiving/'.$inventory['id']; ?>" title="View Inventory Transfer" data-toggle="modal" data-target="#modalDialogNorm">
                										<i class="glyphicon glyphicon-search"></i>
            										</a>
                                                    </td>
												</tr>
											<?php } ?>
										</tbody>
									</table>

<script type="text/javascript">
	$(document).ready(function() {
	 	$('#datatable-responsive').DataTable();
  	});

</script>
</body>

</html>