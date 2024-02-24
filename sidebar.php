  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <style>
      .bounding-box {
      background-image: url('./assets/uploads/liquid_home2.png');
      background-size: contain;
      position: absolute;
      background-position: center;
      background-repeat: no-repeat;
      height: 8%;
      width: 100%;
    }
    </style>
    <div class="dropdown">
   	<a href="./" class="brand-link bounding-box">
        
    </a>
      
    </div>
    <div class="sidebar pb-4 mb-4">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Summary
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_customer">
              <i class="nav-icon fas fa-wifi"></i>
              <p>
                Liquid Home Internet
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_customer_list" class="nav-link nav-new_customer_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Interested Customers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=customer_list" class="nav-link nav-customer_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Existing Customers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=new_customer" class="nav-link nav-new_customer tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Upload File</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=change_plan" class="nav-link nav-change_plan tree-item">
              <i class="nav-icon fas fa-bolt"></i>
              <p>
                Plan Change
              </p>
            </a>
          </li>
          <?php if($_SESSION['login_type'] == 1): ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                Web Portal Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_requests">
              <i class="fas fa-network-wired nav-icon"></i>
              <p>
                Get Home Wi-Fi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=get_internet" class="nav-link nav-get_internet tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>From Existing Customers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=get_internet_new" class="nav-link nav-get_internet_new tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>From New Customers</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=cases_reported" class="nav-link nav-cases_reported tree-item">
              <i class="nav-icon fas fa-wrench"></i>
              <p>
                Cases Reported
              </p>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=chat_requests" class="nav-link nav-chat_requests tree-item">
              <i class="nav-icon fas fa-comment"></i>
              <p>
                Chat Requests
              </p>
            </a>
          </li>
          <?php if($_SESSION['login_type'] == 1): ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_area">
              <i class="nav-icon fas fa-map"></i>
              <p>
                Areas
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_area" class="nav-link nav-new_area tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New Area</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=new_location" class="nav-link nav-new_location tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New Location</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=area_list" class="nav-link nav-area_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=packages" class="nav-link nav-packages tree-item">
              <i class="nav-icon fas fa-tags"></i>
              <p>
                Packages
              </p>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=faqs" class="nav-link nav-faqs tree-item">
              <i class="nav-icon fas fa-question"></i>
              <p>
                FAQs
              </p>
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

  		}
     
  	})
  </script>