<?php 
   //for tab
   
   $r_tab_active = '';
   $r_in_active = '';
   $c_tab_active = '';
   $c_in_active = '';
   $v_tab_active = '';
   $v_in_active = '';
   $a_tab_active = '';
   $a_in_active = '';
   $ve_tab_active = '';
   $ve_in_active = '';
   $t_tab_active = '';
   $t_in_active = '';
   $cr_tab_active = '';
   $cr_in_active = '';
   $cl_tab_active = '';
   $cl_in_active = '';
   $co_tab_active = '';
   $co_in_active = '';
   $rp_tab_active = '';
   $rp_in_active = '';
   
   
   
   ?>

               
           
            <!-- /top navigation -->
            <!-- page content -->
            
                 
                        <div class="x_title">
                           <h2>Request For Payment</h2>
                           <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                              <li><a class="close-link"><i class="fa fa-close"></i></a>
                              </li>
                           </ul>
                           <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                           
                           
							 
							  
							  <br>
						    <a href="<?php echo HTTP_PATH.'accounting/request_payment_form'; ?>" data-toggle="modal" data-target="#modalDialog" title="Add New Request" style="float:left; margin-left:10px; margin-top:10px">
                            
								<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add Request Payment</button>
							</a>
                           
							<hr />
							<table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                              <thead>
                                 <tr>                                                                        
                                    <th width="5%">Request Date</th>
                                    <th width="15%">Payee</th>                                    
                                    <th width="10%">Amount</th>
                                    <th width="50%">Purpose</th>                                    
                                    <th width="5%">Reference No</th>
                                    <th width="10%">Remark</th>
                                    <th width="5%">Status</th>
                                    <!---<th width="5%" style="display:none">Manage</th>--->
                                 </tr>
                              </thead>
                              <tbody>
							   <?php 
								if( !empty($requestpaymentList) ) {
                                    foreach ($requestpaymentList as $request_payment)
									
                                    {
										$id = $request_payment['id']; 
										$reference_no = $request_payment['reference_no']; 
										$requested_by = $request_payment['requested_by']; 
										$request_date = $request_payment['request_date']; 
										$particular = $request_payment['particular']; 
										$amount = $request_payment['amount']; 
										$type = $request_payment['type']; 
										$payee = $request_payment['payee']; 
										$remark = $request_payment['remark']; 
										$status = $request_payment['status']; 
										
										if($request_payment['payee_type'] == 'Supplier'){											
											
											if($request_payment['payee']!=''){
												$p = $this->Abas->getSupplier($request_payment['payee']);												
												$payee = $p['name'];
											}
												
										}
										if($request_payment['payee_type'] == 'Employee'){
											$p = $this->Abas->getEmployee($request_payment['payee']);
											$payee = $p['full_name'];
										}
										
										if($request_payment['payee_type'] == 'Others'){
											
											$payee = $request_payment['payee_others'];
										}
										//var_dump($request_payment);exit;										
										//var_dump($portList);exit;
										//echo (empty($bank)? "<h2 style='margin-top:30px;'><center>You have no Reservations!</center></h2>": ""); //result not empty	
                                    ?>
                                 <tr>
                                    <td align="center"><?php  echo date('F j, Y', strtotime($request_date)) ?></td>
                                    <td><?php  echo $payee; ?></td> 
                                    <td align="right"><?php  echo number_format($amount,2); ?>&nbsp;</td>
                                    <td align="left"><?php  echo $request_payment['purpose']; ?></td>                                                               
                                    <td><?php  echo $reference_no; ?></td>
                                    <td align="left"><?php  echo $remark; ?></td>
                                    <td><?php  echo $status; ?></td>                     
                                    <!---                             
									<td align="center" style="display:none">
                                       <a class="like" href="<?php echo HTTP_PATH ?>accounting/ap_clearing_form/<?php echo $id; ?>/non-po" data-toggle="modal" data-target="#modalDialog" data-keyboard="false">
                                       <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                       </a>
                                       
                                    </td>
                                    --->
                                 </tr>
								<?php } } ?>
                              </tbody>
                           </table>
</div>						   							  
                           </div>
                           <!-- end of tab--->   
                       
            
         <!-- /page content -->
         <!-- footer content -->
         
      
  
<!-- /Datatables -->
    <script>
      $(document).ready(function() {
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").DataTable({
              dom: "Bfrtip",
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();

        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        var $datatable = $('#datatable-checkbox');

        $datatable.dataTable({
          'order': [[ 1, 'asc' ]],
          'columnDefs': [
            { orderable: false, targets: [0] }
          ]
        });
        $datatable.on('draw.dt', function() {
          $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
          });
        });

        TableManageButtons.init();
      });
    </script>