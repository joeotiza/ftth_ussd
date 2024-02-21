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
			<form action="excel.php" method="POST">
                <select name="export_file_type" class="form_control">
                	<option value="xlsx">.xlsx</option>
                    <option value="xls">.xls</option>
                    <option value="csv">.csv</option>
                </select>
                <button type="submit" name="export_customers_btn" class="btn btn-primary mt-3">Export</button>
            </form>
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_customer"><i class="fa fa-plus"></i> Upload Customers File</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">Account ID</th>
						<th>Status</th>
						<th>Current Package</th>
						<th>Name</th>
						<th>Mobile Number</th>
						<th>E-Mail</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$type = array('',"Admin","Project Manager","Employee");

					$qry = $conn->query($customersquery);
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $row['Account_ID'] ?></th>
						<td><b><?php echo $row['Status'] ?></b></td>
						<td><?php echo $row['Current_Package'] ?></td>
						<td><?php echo ucwords($row['FirstName']. " ".$row['LastName']) ?></td>
						<td><?php echo $row['MobileNumber'] ?></td>
						<td><?php echo $row['Email'] ?></td>
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
	$('.view_user').click(function(){
		uni_modal("<i class='fa fa-id-card'></i> User Details","view_user.php?id="+$(this).attr('data-id'))
	})
	$('.delete_user').click(function(){
	_conf("Are you sure to delete this user?","delete_user",[$(this).attr('data-id')])
	})
	})
	function delete_user($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_user',
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