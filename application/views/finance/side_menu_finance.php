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
              <!-- Finance -->
              	<?php if($this->Abas->checkPermissions("finance|fund_approval",false)): ?>

                   <!---
                   <li><a><i class="fa fa-list-alt" aria-hidden="true"></i> Accounts Management <span class="fa fa-chevron-down"></span></a>                       <ul class="nav child_menu">
                          <li><a href="<?php echo HTTP_PATH.'finance/'; ?>"><i class="fa fa-thumbs-o-up"></i> For Funding Approval</a></li>
                          <li><a href="<?php echo HTTP_PATH.'finance/accounts_view/'; ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> Banks Accounts</a></li>
                          <li><a href="#">Bank Transactions</a></li>
                          <li><a href="#">Bank Recon</a></li>

                        </ul>
                  </li>
                  --->
                 	<li><a href="<?php echo HTTP_PATH.'finance/'; ?>"><i class="fa fa-thumbs-o-up"></i> For Funding Approval</a></li>
                 	<li><a href="<?php echo HTTP_PATH.'finance/bank_view/'; ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> Banks Accounts</a></li>
                   	<li><a href="#"><i class="fa fa-list-alt" aria-hidden="true"></i> Bank Transactions</a></li>
                  	<li><a href="<?php echo HTTP_PATH.'finance/bank_recon_view/'; ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> Bank Recon</a></li>
					<li><a href="<?php echo HTTP_PATH.'finance/billing/'; ?>"><i class="fa fa-list-alt" aria-hidden="true"></i>Billing and Collection</a></li>

            	<?php endif; ?>

                <?php if($this->Abas->checkPermissions("finance|cashiering",false)): ?>
                  <li>
                    <a href="<?php echo HTTP_PATH.'finance/accounts_view/'; ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> Disbursement</a>
                  </li>
              	<?php endif; ?>



              <li>
                <a><i class="fa fa-wechat"></i> Chatbox</a>
              </li>
			  <li>
                <a href="<?php echo HTTP_PATH.'home/logout'; ?>"><i class="fa fa-user"></i> Logout</a>
              </li>

               <!-- end of Finance -->
            </ul>

  	</div>

</div>
