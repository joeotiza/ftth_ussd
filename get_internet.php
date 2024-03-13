<?php include 'db_connect.php';
if($_SESSION['login_type'] == 4): 
{
  // header('Location: index.php?page=home');
  echo "<script type='text/javascript'>location.href = 'index.php';</script>";
  exit(0);
}
endif; ?>
 <div class="col-md-12">
        <div class="card card-outline card-success">
          <div class="card-header">
            <div class="card-tools">
                <form action="excel.php" method="POST">
                    <select name="export_file_type" class="form_control">
                        <option value="xlsx">.xlsx</option>
                        <option value="xls">.xls</option>
                        <option value="csv">.csv</option>
                    </select>
                    <button type="submit" name="export_get_internet_btn" class="btn btn-primary">Export</button>
                </form>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive" id="printable">
              <table class="table m-0 table-bordered table-hover" id="list" style="table-layout:fixed;font-size:0.9vw;">
                <colgroup>
                  <col width="9%">
                  <col width="16%">
                  <col width="11%">
                  <col width="10%">
                  <col width="22%">
                  <col width="8%">
                  <col width="14%">
                  <col width="10%">
                </colgroup>
                <thead>
                  <th>Customer ID</th>
                  <th>Customer Name</th>
                  <th>Mobile Number</th>
                  <th>Area</th>
                  <th>Address</th>
                  <th>Capacity</th>
                  <th>Request Time</th>
                  <th>Platform</th>
                </thead>
                <tbody>
                <?php
                $qry = $conn->query("SELECT `get_internet`.`Customer ID`, `Customer Name`, `Contact Number`, `EMail Address` AS `Email`, `Area`, `Capacity`, `request_date`, `source`, `get_internet`.`Address` as `myAddress`
                FROM `get_internet` 
                INNER JOIN `customers` ON `get_internet`.`Customer ID`=`customers`.`Customer ID`
                WHERE `Service Status` LIKE 'Active'
                UNION
                SELECT `get_internet`.`Customer ID`, CONCAT(`FirstName`,' ',`LastName`) AS `Customer Name`, `Contact Number`, `Email`, `Area`, `Capacity`, `reg_date` AS `request_date`, `get_internet`.`source`, `Address` AS `myAddress`
                FROM `get_internet` INNER JOIN `customer_details` ON `get_internet`.`Customer ID`=`customer_details`.`Customer ID` ");
                while($row= $qry->fetch_assoc()):
                  ?>
                  <tr>
                      <td>
                         <?php echo $row['Customer ID'] ?>
                      </td>
                      <td>
                        <?php echo ucwords($row['Customer Name']) ?>
                      </td>
                      <td class="text-center">
                      	<?php echo $row['Contact Number'] ?>
                      </td>
                      <td class="text-center">
                      	<?php echo $row['Area'] ?>
                      </td>
                      <td class="text-left">
                      	<?php echo $row['myAddress'] ?>
                      </td>
                      <td class="text-center">
                      	<?php echo $row['Capacity'] ?>
                      </td>
                      <td class="text-center">
                      	<?php echo $row['request_date'] ?>
                      </td>
                      <td class="text-center"><b><?= ($row['source'] == "WhatsApp") ? "WhatsApp" : $myussdcode?></b></td>
                  </tr>
                <?php endwhile; ?>
                </tbody>  
              </table>
            </div>
          </div>
        </div>
        </div>
<script>
  $(document).ready(function(){
    $('#list').dataTable({order: [[6, 'desc']]})
  })
	$('#print').click(function(){
		start_load()
		var _h = $('head').clone()
		var _p = $('#printable').clone()
		var _d = "<p class='text-center'><b>Project Progress Report as of (<?php echo date("F d, Y") ?>)</b></p>"
		_p.prepend(_d)
		_p.prepend(_h)
		var nw = window.open("","","width=900,height=600")
		nw.document.write(_p.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
			nw.close()
			end_load()
		},750)
	})
</script>