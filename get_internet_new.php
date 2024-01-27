<?php include 'db_connect.php' ?>
 <div class="col-md-12">
        <div class="card card-outline card-success">
          <div class="card-header">
            <b>Project Progress</b>
            <div class="card-tools">
              <form action="excel.php" method="POST">
                    <select name="export_file_type" class="form_control">
                        <option value="xlsx">.xlsx</option>
                        <option value="xls">.xls</option>
                        <option value="csv">.csv</option>
                    </select>
                    <button type="submit" name="export_get_internet_new_btn" class="btn btn-primary mt-3">Export</button>
              </form>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive" id="printable">
              <table class="table m-0 table-bordered">
               <!--  <colgroup>
                  <col width="5%">
                  <col width="30%">
                  <col width="35%">
                  <col width="15%">
                  <col width="15%">
                </colgroup> -->
                <thead>
                  <th>#</th>
                  <th>Customer Name</th>
                  <th>Mobile Number</th>
                  <th>Location</th>
                  <th>Requested Capacity</th>
                  <th>Request Time</th>
                </thead>
                <tbody>
                <?php
                $i = 1;
                $qry = $conn->query("SELECT *,concat(FirstName,' ',LastName) as name FROM `get_internet` INNER JOIN `customer_details` ON `get_internet`.`CustomerID`=`customer_details`.`CustomerID` order by `request_date` desc");
                while($row= $qry->fetch_assoc()):
                  ?>
                  <tr>
                      <td>
                         <?php echo $i++ ?>
                      </td>
                      <td>
                        <?php echo ucwords($row['name']) ?>
                      </td>
                      <td class="text-center">
                      	<?php echo $row['MobileNumber'] ?>
                      </td>
                      <td class="text-center">
                      	<?php echo $row['Location'] ?>
                      </td>
                      <td class="text-center">
                      	<?php echo $row['Capacity'] ?>
                      </td>
                      <td class="text-center">
                      	<?php echo $row['request_date'] ?>
                      </td>
                  </tr>
                <?php endwhile; ?>
                </tbody>  
              </table>
            </div>
          </div>
        </div>
        </div>
<script>
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