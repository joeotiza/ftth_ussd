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
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_customer"><i class="fa fa-plus"></i> Upload Customers File</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">Customer ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Mobile Number</th>
						<th>Account Number</th>
						<th>Package</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$type = array('',"Admin","Project Manager","Employee");

					$readCapacity = "IF(`GPONPlan` LIKE \"%100%MBPS%\", '100Mbps',
										IF(`GPONPlan` LIKE \"%65%MBPS%\", '65Mbps',
											IF(`GPONPlan` LIKE \"%60%MBPS%\", '60Mbps',
												IF(`GPONPlan` LIKE \"%50%MBPS%\", '50Mbps',
													IF(`GPONPlan` LIKE \"%40%MBPS%\", '40Mbps',
														IF(`GPONPlan` LIKE \"%25%MBPS%\", '25Mbps',
															IF(`GPONPlan` LIKE \"%10%MBPS%\", '10Mbps',
																IF(`GPONPlan` LIKE \"%5%MBPS%\", '5Mbps',
																	IF(`GPONPlan` LIKE \"%4%MBPS%\", '4Mbps',
																		IF(`GPONPlan` LIKE \"%3%MBPS%\", '3Mbps',
																			'N/A'))))))))))";

					$qry = $conn->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
					TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName, ".$readCapacity." as capacity FROM customers");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $row['Customer ID'] ?></th>
						<td><?php echo ucwords($row['FirstName']. " ".$row['LastName']) ?></td>
						<td><?php echo $row['EMail Address'] ?></td>
						<td><?php echo $row['Contact Number'] ?></td>
                        <td><?php echo $row['Correlation ID'] ?></td>
						<td><?php echo $row['capacity'] ?></td>
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