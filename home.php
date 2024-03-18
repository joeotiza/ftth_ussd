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
  <style>
    .small-box
    {
      height:85%;
    }
  </style>
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
        
      <div class="row" style="display:flex;">
        <div class="col-md-6" style="flex:1;">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner text-center">
                <label for="" class="control-label">Area</label>
                <select name="AreaName" id="AreaName" class="custom-select custom-select-sm" style="width:50%;">
                  <option value="All">All</option>
                </select>
              </div>
              <table class="table table-borderless">
                <tr>
                  <td class="text-center"><div class="btn btn-warning btn-sm btn-flat" style="color:#fff;">Connected: <b id="ConnectedCount"></b></div></td>
                  <td class="text-center"><div class="btn btn-success btn-sm btn-flat">Active: <b id="ActiveCount"></b></div></td>
                  <td class="text-center"><div class="btn btn-danger btn-sm btn-flat">Expired: <b id="ExpiredCount"></b></div></td>
                </tr>
              </table>
              <div class="icon">
                <i class="fa fa-wifi"></i>
              </div>
            </div>
        </div>
        <div class="col-md-6" style="flex:1;">
          <div class="small-box bg-light shadow-sm border">
            <div class="inner">
              <h3 id="Penetration"></h3>
              <p>Penetration (<b id="AreaHomes"></b> Homes Passed)</p>
            </div>
            <div class="icon">
              <i class="fa fa-map-location-dot"></i>
            </div>
          </div>
        </div>
      </div>
        <div class="row" style="display:flex;">
          <div class="col-md-6" style="flex:1;">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3 id="connectedcustomers"></h3>
                <p>Recorded Customers</p>
              </div><p></p>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
            </div>
          </div>
          <div class="col-md-6" style="flex:1;">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM `get_internet` WHERE `request_date` > DATE_SUB(CURDATE(), INTERVAL 1 MONTH);")->num_rows; ?></h3>
                <p>Get Home Interenet requests in the past month</p>
              </div>
              <div class="icon">
                <i class="fa fa-house-signal"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="row" style="display:flex;">
          <div class="col-md-6" style="flex:1;">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM `plan_change`;")->num_rows; ?></h3>
                <p>Change Plan Requests</p>
              </div>
              <div class="icon">
                <i class="fa fa-shuffle"></i>
              </div>
            </div>
          </div>
          <div class="col-md-6" style="flex:1;">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM `cases_reported`;")->num_rows; ?></h3>
                <p>Reported Cases</p>
              </div>
              <div class="icon">
                <i class="fa fa-triangle-exclamation"></i>
              </div>
            </div>
          </div>
        </div>
      <script>
        var selectArea = document.getElementById("AreaName");
        var activecount = document.getElementById("ActiveCount");
        var expiredcount = document.getElementById("ExpiredCount");
        var connectedcount = document.getElementById("ConnectedCount");
        var penetration = document.getElementById("Penetration");
        var homescount = document.getElementById("AreaHomes");

        var totalconnected = 0;
        var totalactive = 0;
        var totalexpired = 0;
        var totalhomes = 0;
        const querydata = [];
        const homesdata = [];
          <?php $qry = $conn->query($summaryquery);
					  while($row= $qry->fetch_assoc()): ?>
            querydata.push({name: '<?=$row['AreaName']?>', connected: <?=$row['Connected']?>, active: <?=$row['Active']?>, expired: <?=$row['Expired']?>});
            totalconnected += parseInt(<?=$row['Connected']?>);
            totalactive += parseInt(<?=$row['Active']?>);
            totalexpired += parseInt(<?=$row['Expired']?>);
          <?php endwhile;?>

          <?php $qry = $conn->query("SELECT `AreaName`, SUM(`homes`) AS `Homes` FROM `LocationDetails`
                        LEFT JOIN `AreaDetails`
                        ON `LocationDetails`.`AreaCode`=`AreaDetails`.`AreaCode`
                        GROUP BY `AreaName` ");
					  while($row= $qry->fetch_assoc()): ?>
              homesdata.push({name: '<?=$row['AreaName']?>', homes: <?=$row['Homes']?>});
              totalhomes += parseInt(<?=$row['Homes']?>);
          <?php endwhile;?>

          // Perform left join and store the result in the original querydata array
          querydata.forEach(queryItem => {
              // Find matching item in homesdata based on name
              let matchingHome = homesdata.find(homeItem => homeItem.name === queryItem.name);
              // If matching home is found, update the queryItem with homes value, otherwise, keep the queryItem as is
              if (matchingHome) {
                  queryItem.homes = matchingHome.homes;
              }
              if (queryItem.name === "(Blanks)"){
                queryItem.homes = 0;
              }
          });

          activecount.innerHTML = totalactive.toLocaleString();
          connectedcount.innerHTML = totalconnected.toLocaleString();
          expiredcount.innerHTML = totalexpired.toLocaleString();
          homescount.innerHTML = totalhomes.toLocaleString();
          penetration.innerHTML = (totalconnected/totalhomes*100).toFixed(0)+'%';
          document.getElementById("connectedcustomers").innerHTML = totalconnected.toLocaleString();

          console.log(querydata);
          var optionsData = [
            {value: "All", text:"All"}];
            querydata.forEach((element, index, array) => {
              optionsData.push({value: element.name, text: element.name})
            });

          // Function to populate select element
          function populateSelect() {
            var selectElement = document.getElementById("AreaName");

            // Clear existing options
            selectElement.innerHTML = "";

            // Add new options
            optionsData.forEach(function(option) {
              var optionElement = document.createElement("option");
              optionElement.value = option.value;
              optionElement.textContent = option.text;
              selectElement.appendChild(optionElement);
            });
          }

          // Call the populateSelect function when the page loads
          document.addEventListener("DOMContentLoaded", populateSelect);

          selectArea.addEventListener("change", function() {
            // Get the selected option
            var selectedOption = selectArea.options[selectArea.selectedIndex];
            // console.log(selectedOption.text);
            const myarea = querydata.find(querydata => querydata.name === selectedOption.text);
            // console.log(myarea.connected);
            if (selectedOption.text === 'All')
            {
              activecount.innerHTML = totalactive.toLocaleString();
              connectedcount.innerHTML = totalconnected.toLocaleString();
              expiredcount.innerHTML = totalexpired.toLocaleString();
              homescount.innerHTML = totalhomes.toLocaleString();
              penetration.innerHTML = (totalconnected/totalhomes*100).toFixed(0)+'%';
            }
            else
            {
              activecount.innerHTML = myarea.active.toLocaleString();
              connectedcount.innerHTML = myarea.connected.toLocaleString();
              expiredcount.innerHTML = myarea.expired.toLocaleString();
              homescount.innerHTML = myarea.homes.toLocaleString();
              if (myarea.homes != 0){
                penetration.innerHTML = (myarea.connected/myarea.homes*100).toFixed(0)+'%';
              }
              else{
                penetration.innerHTML = "__";
              }
            }
          });
      </script>
