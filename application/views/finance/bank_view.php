<?php


 $r_tab_active = '';
   $r_in_active = '';
   $c_tab_active = '';
   $c_in_active = '';
   $v_tab_active = '';
   $v_in_active = '';
   $a_tab_active = '';
   $a_in_active = '';
   $p_tab_active = '';
   $p_in_active = '';

if(isset($tab)){
   if($tab == 'bank_accounts'){
   	$c_tab_active = 'class="active"';
   	$c_in_active = 'in active';
   }elseif($tab == 'cash_advance'){
   	$v_tab_active = 'class="active"';
   	$v_in_active = 'in active';
   }elseif($tab == 'supplier_accounts'){
   	$r_tab_active = 'class="active"';
   	$r_in_active = 'in active';
   }elseif($tab == 'service_provider'){
   	$a_tab_active = 'class="active"';
   	$a_in_active = 'in active';
	}elseif($tab == 'voucher'){
   	$p_tab_active = 'class="active"';
   	$p_in_active = 'in active';
   }else{
   	$v_tab_active = 'class="active"';
   	$v_in_active = 'in active';
   }

}
//set location
   if(isset($_SESSION['abas_login']['user_location'])){
			$location 		= $_SESSION['abas_login']['user_location'];
	}else{
		//log user out
		header('Location:' . HTTP_PATH . 'home/logout');
		die();
	}	
?>
<?php $this->load->view('includes_header'); ?>

<script>
 /*
 $(document).ready(function() {
 	$('#datatable-responsive').DataTable();
	$('#datatable-responsive1').DataTable();
	$('#datatable-responsive2').DataTable();
	$('#datatable-responsive3').DataTable();
	$('#datatable-responsive4').DataTable();
  });
  */
</script>

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
            <?php $this->load->view('finance/side_menu_finance'); ?>
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

                <h3>Finance</h3>

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
                    <h2>Bank Accounts</h2>
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
                                 <a class="like" href="<?php echo HTTP_PATH ?>finance/bank_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
                                 <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add Bank</button>
                                 </a>
                                 <hr />
                                 <table id="datatable-responsive2" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Account Name</th>
                                          <th>Account Number</th>
                                          <th>Account Type</th>
                                          <th>Currency</th>
                                          <th>Contact Person</th>
                                          <th>Contact Number</th>
                                          <th>Fax Number</th>
                                          <th>Email</th>
                                          <th>Manage</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                          if( !empty($bank_accounts) ) {
                                              foreach ($bank_accounts as $bank){
                                          ?>
                                       <tr>
                                          <td><?php  echo $bank['name'] ?></td>
                                          <td><?php  echo $bank['account_name'] ?></td>
                                          <td><?php  echo $bank['account_no'] ?></td>
                                          <td><?php  echo $bank['account_type'] ?></td>
                                          <td><?php  echo $bank['currency'] ?></td>
                                          <td><?php  echo $bank['contact_person'] ?></td>
                                          <td><?php  echo $bank['contact_no'] ?></td>
                                          <td><?php  echo $bank['fax_no'] ?></td>
                                          <td><?php  echo $bank['email'] ?></td>
                                          <td align="center">
                                             <a class="like" href="<?php echo HTTP_PATH ?>finance/bank_form/<?php echo $bank['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Create Voucher" data-keyboard="false" data-backdrop="static">
                                             <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                             </a>
                                          </td>
                                       </tr>
									   <?php

											}
										}else{

												echo '<tr><td  colspan="6">No data found</td></tr>';
										}
										?>
                                    </tbody>
                                 </table>
                              </div>

                              
                             
                          
                           <!-- end of tab--->
                        </div>
                </div>
              </div>







            </div>
          </div>
        </div>
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

	 <?php $this->load->view("includes_footer_scripts"); ?>

<script>






/*
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
*/
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
