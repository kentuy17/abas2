 <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <!-- include user picture here if available -->
               <img src="<?php echo LINK.'assets/images/my_picture.jpg'; ?>" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2 style="color:#FF9900"><?php echo strtoupper($_SESSION['abas_login']['username']); ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
	<div class="menu_section">
    	<h3>Menu</h3>
            <ul class="nav side-menu">
              
              <li>
                <a href="<?php echo HTTP_PATH.'operation/'; ?>"><i class="fa fa-file-text-o"></i> Contracts</a>                    
              </li>
              <li>
                <a><i class="fa fa-list-alt"></i> Accomplishment Entries <span class="fa fa-chevron-down"></span></a>                    
                <ul class="nav child_menu">
                      <li><a href="<?php echo HTTP_PATH.'operation/wb_view/'; ?>"><i class="fa fa-caret-right"></i>  Waybill</a></li>
                      <li><a href="<?php echo HTTP_PATH.'operation/wsi_view/'; ?>"><i class="fa fa-caret-right "></i> WSI</a></li>                      
                      <li><a href="<?php echo HTTP_PATH.'operation/wsr_view/'; ?>"><i class="fa fa-caret-right"></i> WSR</a></li>                      
                    </ul>
              </li>
              <li style="display:none">
                <a href="<?php echo HTTP_PATH.'operation/wsr_view/'; ?>"><i class="fa fa-files-o"></i> WSRs </a>                    
                
              </li>
              <li>
                <a><i class="fa fa-list-alt"></i> Billing <span class="fa fa-chevron-down"></span></a>                    
                <ul class="nav child_menu">
                      <li><a href="<?php echo HTTP_PATH.'operation/request_payment_form/'; ?>"><i class="fa fa-caret-right"></i>  Request for Payment</a></li>
                      <li><a href="<?php echo HTTP_PATH.'operation/statement_account_form/'; ?>"><i class="fa fa-caret-right"></i> Statement of Account</a></li>  
                      <!---
                      <li><a href="<?php echo HTTP_PATH.'operation/waybill_form/'; ?>"><i class="fa fa-truck"></i>  Trucking</a></li>
                      <li><a href="<?php echo HTTP_PATH.'operation/wsi_form/'; ?>"><i class="fa fa-blind "></i> Handling</a></li>                      
                      <li><a href="<?php echo HTTP_PATH.'operation/bol_form/'; ?>"><i class="fa fa-ship"></i> Voyage</a></li>                       
                      --->
                    </ul>
              </li>
              <li>
                <a><i class="fa fa-wechat"></i> Chatbox</a>                    
              </li>
			  <li>
                <a href="<?php echo HTTP_PATH.'forms/serprovider_view/'; ?>"><i class="fa fa-list-alt"></i> Service Provider</a>                    
              </li>         
            </ul>
              	
  	</div>          	
    
</div>
            