<?php
 /*  $id = "";
   $name = "";
   $acc_name = "";
   $acc_no = "";
   $acc_type = "";
   $currency = "";
   $cont_person = "";
   $cont_no = "";
   $fax_no = "";
   $email = "";
   $stat = "";
   
   if(isset($bank)){
   		//var_dump($item[0]['id']);exit;
   		$name = $bank[0]['name'];
   		$acc_name = $bank[0]['account_name'];
   		$acc_no = $bank[0]['account_no'];
   		$acc_type = $bank[0]['account_type'];
   		$currency = $bank[0]['currency'];
   		$cont_person = $bank[0]['contact_person'];
   		$cont_no = $bank[0]['contact_no'];
   		$fax_no = $bank[0]['fax_no'];
   		$email = $bank[0]['email'];
   		$stat = $bank[0]['stat'];
   }
   */
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <!-- Meta, title, CSS, favicons, etc. -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>ABAS-Operation Management System</title>
      <?php $this->load->view('includes_header'); ?>
   </head>
   <body class="nav-md">
      <div class="container body">
         <div class="main_container">
            <div class="col-md-3 left_col">
               <div class="left_col scroll-view">
                  <div class="navbar nav_title" style="border: 0;">
                     <a href="<?php echo LINK ?>" class="site_title"><img src="<?php echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="45px" style="margin-top:-5px" align="absmiddle" class="img-circle"> <span>ABAS v1</span></a>
                  </div>
                  <div class="clearfix"></div>
                  <!-- sidebar menu -->
                  <?php $this->load->view('accounting/side_menu_accounting'); ?>
                  <!-- /sidebar menu -->
                  <!-- /menu footer buttons -->
                  <?php $this->load->view('operation/footer_button'); ?>
                  <!-- /menu footer buttons -->
               </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
               <div class="nav_menu"  style="float:left; margin-left:0px; margin-top:0px">
                  <!-- toggle side menu -->
                  <div class="nav toggle">
                     <a id="menu_toggle"><i class="fa fa-bars"></i></a>             
                  </div>
                  <nav>
                     <div class="nav" style="float:left; margin-left:0px; margin-top:5px">
                        <h3>Accounting System</h3>
                     </div>
                     <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="javascript:;">
                              <div><i class="fa fa-sign-out pull-right" style="margin-top:8px"></i>  Log Out</div>
                           </a>
                        </li>
                        <li >
                           <a >
                           <strong style="color:#FF0000">Notifications</strong>
                           <i class="fa fa-envelope" style="color:#FF0000"></i>
                           </a>
                        </li>
                     </ul>
                     </li>
                     </ul>
                  </nav>
               </div>
            </div>
            <!-- /top navigation -->
            <!-- page content -->
            <div class="right_col" role="main">
               <!-- top tiles -->
               <div class="row tile_count" style="height:50px; color:#FF6600">
                  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                  </div>
               </div>
               <!-- /top tiles -->
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <div class="x_panel">
                        <div class="x_title">
                           <h2>Dashboard</h2>
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
                           <a class="like" href="<?php echo HTTP_PATH ?>forms/vessel_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
                           <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add Vessel</button>
                           </a>
                           <hr />
                           <table id="datatable-responsive-vessel" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                              <thead>
                                 <tr>
                                    <th>Company</th>                                    
                                    <th>Photo Path</th>                                    
                                    <th>Name</th>                                    
                                    <th>Ex Name</th>                                    
                                    <th>Price Sold</th>                                    
                                    <th>Price Paid</th>                                    
                                                                     
                                    <th>Manage</th> 
								</div>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php 
                                    if( !empty($vesselList) ) {
                                        foreach ($vesselList as $vessel){										
                                   ?>
                                 <tr>                                
                                    <td><?php  echo $vessel['company'] ?></td>
                                    <td><?php  echo $vessel['photo_path'] ?></td>
                                    <td><?php  echo $vessel['name'] ?></td>
                                    <td><?php  echo $vessel['ex_name'] ?></td>
                                    <td><?php  echo $vessel['price_sold'] ?></td>
                                    <td><?php  echo $vessel['price_paid'] ?></td>
									
                                    <td align="center">
										<a class="like" href="<?php echo HTTP_PATH ?>forms/vessel_show/<?php echo $vessel['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
                                       <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-eye-open"></i></button>
                                       <a class="like" href="<?php echo HTTP_PATH ?>forms/vessel_form/<?php echo $vessel['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-keyboard="false" data-backdrop="static">
                                       <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                       </a>
									   
                                       </a>
                                    </td>
                                    <?php } } ?>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
		 
		 <script>
<?php $this->Abas->display_messages(); ?>
</script>
         <!-- /page content -->
         <!-- footer content -->
         <footer>
            <div class="pull-right">
               <strong>AVEGAiT2015</strong>
            </div>
            <div class="clearfix"></div>
         </footer>
         <!-- /footer content -->
      </div>
      </div>
      <!-- Modal -->
      <?php $this->load->view("includes_footer_scripts"); ?>  
      <script>
         $(document).ready(function() {
           $('#wsr_date').daterangepicker({
             singleDatePicker: true,
             calender_style: "picker_4"
           }, function(start, end, label) {
             console.log(start.toISOString(), end.toISOString(), label);
           });
         });
         
         function myFunction() {
         location.reload();
         }
      </script>
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

        $('#datatable-responsive-vessel').DataTable();

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



   </body>
</html>