<?php

 //echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png';
	$id = '';
	$wsr_no = '';
	$wsr_date = '';
	$waybill_no = '';
	$wsi_no = '';
	$reference_no = '';
	$from_location = '';
	$from_location_name = '';

	$to_location = '';
	$to_location_name = '';

	$region = '';
	$region_name = '';

	$transaction_type = '';
	$transaction_type_name = '';

	$truck_plate_no = '';
	$bags = '';
	$gross_weight = 0;
	$wsi_gross_weight = 0;
	$net_weight = 0;

	$variety = '';
	$age = '';
	$stock_condition = '';


 if(isset($wsr)){

 	$id = $wsr->id;
	$wsr_no = $wsr->wsr_no;
	$wsr_date = $wsr->wsr_date;
	$waybill_no = $wsr->waybill_no;
	$wsi_no = $wsr->wsi_no;
	$reference_no = $wsr->reference_no;

	$from_location = $wsr->from_location;
	$f = $this->Operation_model->getTruckingLocation($wsr->from_location);
	$from_location_name = $f->name;

	$to_location = $wsr->to_location;
	$l = $this->Operation_model->getTruckingLocation($wsr->to_location);
	$to_location_name = $l->name;


	$region = $wsr->region;
	$r =  $this->Abas->getRegion($wsr->region);

	$region_name = $r[0]->name;

	$transaction_type = $wsr->transaction_type;
	$t =  $this->Operation_model->getTransactionType($wsr->transaction_type);
	$transaction_type_name = $t->transaction_name;


	$truck_plate_no = $wsr->truck_plate_no;
	$bags = $wsr->bags;
	$gross_weight = $wsr->gross_weight;
	$wsi_gross_weight = $wsr->wsi_gross_weight;
	$net_weight = $wsr->net_weight;

	$variety = $wsr->variety;
	$age = $wsr->age;
	$stock_condition = $wsr->stock_condition;


 }

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
                    	content

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
      $(document).ready(function() {
        $('#wsr_date').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_4"
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>



</body>
</html>
