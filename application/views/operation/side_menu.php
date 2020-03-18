 <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <!-- include user picture here if available -->
               <img src="<?php echo LINK.'assets/images/my_picture.jpg'; ?>" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>User</h2>
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
                <a href="<?php echo HTTP_PATH.'operation/wsr_view/'; ?>"><i class="fa fa-files-o"></i> WSRs </a>                    
                
              </li>
              <li>
                <a><i class="fa fa-list-alt"></i> Billing <span class="fa fa-chevron-down"></span></a>                    
                <ul class="nav child_menu">
                      <li><a href="<?php echo HTTP_PATH.'operation/request_payment_form/'; ?>"><i class="fa fa-wpforms"></i>  Create Request for Payment</a></li>
                      <li><a href="<?php echo HTTP_PATH.'operation/statement_account_form/'; ?>"><i class="fa fa-wpforms"></i> Create SOA</a></li>                      
                    </ul>
              </li>
              <li>
                <a><i class="fa fa-wechat"></i> Chatbox</a>                    
              </li>
                       
            </ul>
              	
  	</div>          	
    
</div>
            