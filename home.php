<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>
<!-- Info boxes -->
 <div class="col-12">
          <div class="card">
            <div class="card-body">
              Welcome <?php echo $_SESSION['login_name'] ?>!
            </div>
          </div>
  </div>
  <hr>
  <?php 

    $where = "";
    if($_SESSION['login_type'] == 2){
      $where = " where manager_id = '{$_SESSION['login_id']}' ";
    }elseif($_SESSION['login_type'] == 3){
      $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
    }
     $where2 = "";
    if($_SESSION['login_type'] == 2){
      $where2 = " where p.manager_id = '{$_SESSION['login_id']}' ";
    }elseif($_SESSION['login_type'] == 3){
      $where2 = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
    }
    ?>
        
      <div class="row">
          <div class="col-md-6">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM `get_internet` WHERE request_date > DATE_SUB(NOW(), INTERVAL 1 MONTH);")->num_rows; ?></h3>
                <p>Get Internet Requests in the past one month</p>
              </div>
              <div class="icon">
                <i class="fa fa-wifi"></i>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM `customers`;")->num_rows; ?></h3>
                <p>Recorded Customers</p>
              </div>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM `cases_reported`;")->num_rows; ?></h3>
                <p>Pending Reported Cases</p>
              </div>
              <div class="icon">
                <i class="fa fa-wrench"></i>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM `customer_details`;")->num_rows; ?></h3>
                <p>New Customer Requests</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-plus"></i>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM `plan_change`;")->num_rows; ?></h3>
                <p>Change Plan Requests</p>
              </div>
              <div class="icon">
                <i class="fa fa-bolt"></i>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM `chat`;")->num_rows; ?></h3>
                <p>Chat Requests</p>
              </div>
              <div class="icon">
                <i class="fa fa-comment"></i>
              </div>
            </div>
          </div>
      </div>
