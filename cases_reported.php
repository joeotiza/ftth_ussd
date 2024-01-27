<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
            <?php
            if (isset($_SESSION['message']))
            {
                echo "<h4>".$_SESSION['message']."</h4>";
                unset ($_SESSION['message']);
            }
            ?>
			<div class="card-tools">
				<form action="excel.php" method="POST">
                    <select name="export_file_type" class="form_control">
                        <option value="xlsx">.xlsx</option>
                        <option value="xls">.xls</option>
                        <option value="csv">.csv</option>
                    </select>
                    <button type="submit" name="export_cases_reported_btn" class="btn btn-primary mt-3">Export</button>
                </form>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">CustomerID</th>
						<th>Name</th>
                        <th>E-Mail Address</th>
						<th>Mobile Number</th>
                        <th>Account No.</th>
                        <th>Case Reported</th>
						<th>Report Time</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//$i = 1;
					$type = array('',"Admin","Project Manager","Employee");
					$qry = $conn->query("SELECT *,concat(FirstName,' ',LastName) as name FROM `customers` RIGHT JOIN `cases_reported`
                     ON `customers`.`haik_Ref`=`cases_reported`.`haik_Ref` order by `time` desc");

					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $row['CustomerID'] ?></th>
						<td><?php echo ucwords($row['name']) ?></td>
						<td><?php echo $row['EMailAddress'] ?></td>
                        <td><?php echo $row['MobileNumber'] ?></td>
                        <td><?php echo $row['haik_Ref'] ?></td>
                        <td><?php echo $row['reported_case'] ?></td>
						<td><?php echo $row['time'] ?></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item delete_case" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Resolve</a>
		                    </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.delete_case').click(function(){
	_conf("Are you sure this case was resolved?","delete_case",[$(this).attr('data-id')])
	})
	})
	function delete_case($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_case',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>