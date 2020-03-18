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
                <a href="<?php echo HTTP_PATH.'inventory/'; ?>"><i class="fa fa-list-alt"></i> Home</a>
              </li>
              <li>
                <a href="<?php echo HTTP_PATH.'inventory/audit_view'; ?>"><i class="fa fa-list-alt"></i> Inventory Audit</a>
              </li>
              
			  <!--<li style="display:none">
				<a href="<?php //echo HTTP_PATH.'inventory/delivery_history'; ?>"><i class="fa fa-list-alt"></i>Delivery History</a>
			  </li>
			  <li style="display:none">
			  <a href="<?php //echo HTTP_PATH.'inventory/issuance_history'; ?>"><i class="fa fa-list-alt"></i>Issuance History</a>
			  </li>-->

        <li>
            <a><span class="glyphicon glyphicon-chevron-down"></span> Transaction History</a>
              <ul class="nav child_menu">
                  <li><a href="<?php echo HTTP_PATH.'inventory/transaction_history/delivery'; ?>">Delivery</a></li>
                  <li><a href="<?php echo HTTP_PATH.'inventory/transaction_history/issuance'; ?>">Issuance</a></li>
                  <li><a href="<?php echo HTTP_PATH.'inventory/transaction_history/transfer'; ?>">Transfer</a></li>
              </ul>
        </li>

			  <li>
			  <a href="<?php echo HTTP_PATH.'inventory/report_type'; ?>"><i class="fa fa-list-alt"></i> Inventory Report</a>
			  </li>
              <li>
                <a href="<?php echo HTTP_PATH.'purchasing/'; ?>"><i class="fa fa-columns"></i> Purchasing</a>
              </li>
              <!---
              <li>
                <a href="<?php echo HTTP_PATH.'accounting/voucher_view/'; ?>"><i class="fa fa-file-text-o"></i> Deliveries</a>
              </li>
              <li>
                <a href="<?php echo HTTP_PATH.'accounting/cashier_view/'; ?>"><i class="fa fa-share"></i> Issuances</a>
              </li>
             --->

              <li>
                <a><i class="fa fa-wechat"></i> Chatbox</a>
              </li>
			   <li>
                    	<a href="<?php echo HTTP_PATH."home/logout"; ?>"><i class="fa fa-user"></i> Logout</a>
                    </li>

            </ul>

  	</div>

</div>
