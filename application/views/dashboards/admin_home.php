<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>NTC ADMIN DASHBOARD</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Easy Admin Panel Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="<?php echo base_url(); ?>assets/css/style.css" rel='stylesheet' type='text/css' />
<!-- Graph CSS -->
<link href="<?php echo base_url(); ?>assets/css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<!-- lined-icons -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icon-font.min.css" type='text/css' />
<!-- //lined-icons -->
<!--animate-->
<link href="<?php echo base_url(); ?>assets/css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="<?php echo base_url(); ?>assets/js/wow.min.js"></script>
	<script>
		 new WOW().init();
	</script>
<!--//end-animate-->
<!----webfonts--->
<link href='//fonts.googleapis.com/css?family=Cabin:400,400italic,500,500italic,600,600italic,700,700italic' rel='stylesheet' type='text/css'>
<!---//webfonts---> 
 <!-- Meters graphs -->
<script src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.min.js"></script>
<!-- Placed js at the end of the document so the pages load faster -->
<style>
.image { 
   position: relative; 
   width: 100%; /* for IE 6 */
}

h2 { 
   position: absolute; 
   top: 40px;
   left: 33px;
   width: 100%; 
   color: white;
   line-height: 46px;
}
</style>
</head> 

<body class="sticky-header left-side-collapsed"  onload="initMap()">
    <section>
    <!-- left side start-->
		<div class="left-side sticky-left-side">

			<!--logo and iconic logo start-->
			<div class="logo">
				<h1><a href="<?php echo site_url('dashboard/index') ?>">Easy <span>Admin</span></a></h1>
			</div>
			
		</div>
		<!-- left side end-->
    
		<!-- main content start-->
		<div class="main-content">
			<!-- header-starts -->
			<div class="header-section">
			<div class="menu-right"> 
             	<div style="margin-left:20px"><h3>ADMIN</h3></div>
            </div> 
			<!--toggle button start-->
			
			<!--toggle button end-->

			<!--notification menu start -->
			<div class="menu-right">
				<div class="user-panel-top">  	
					<div class="profile_details_left">
						
                        <ul class="nofitications-dropdown">
							
							
							<div class="clearfix"></div>	
						</ul>
					</div>
					  	
					<div class="clearfix"></div>
				</div>
			  </div>
			<!--notification menu end -->
			</div>
		<!-- //header-ends --> 
   
   
   <div style="margin-left:20px; margin-top:10px">
   		
        <ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#booking">Booking</a></li>
			<li><a data-toggle="tab" href="#drivers">Drivers</a></li>
            <li><a data-toggle="tab" href="#clients">Clients</a></li>
            <li><a data-toggle="tab" href="#accounts">Accounts</a></li>			
        </ul>
		<div class="tab-content">
			<div id="booking" class="tab-pane fade in active">
            	Booking
            </div>
            <div id="drivers" class="tab-pane fade">
            	Drivers
            </div>
            <div id="clients" class="tab-pane fade">
            	Clients
            </div>
            <div id="accounts" class="tab-pane fade">
            	Accounting
            </div>
            
        </div>    
        
   
   
   </div>
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   