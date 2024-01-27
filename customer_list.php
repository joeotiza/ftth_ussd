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
						<th class="text-center">CustomerID</th>
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

					$readCapacity = "IF(LOCATE(\" 100MBPS \", `PrismPackageName`) > 0, '100Mbps',
										IF(LOCATE(\" 50MBPS \", `PrismPackageName`) > 0, '50Mbps',
											IF(LOCATE(\" 25MBPS \", `PrismPackageName`) > 0, '25Mbps',
												IF(LOCATE(\" 10MBPS \", `PrismPackageName`) > 0, '10Mbps',
													IF(LOCATE(\" 5MBPS \", `PrismPackageName`) > 0, '5Mbps',
														'N/A')))))";

					$qry = $conn->query("SELECT *,concat(FirstName,' ',LastName) as name, ".$readCapacity." as capacity FROM customers order by concat(firstname,' ',lastname) asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $row['CustomerID'] ?></th>
						<td><?php echo ucwords($row['name']) ?></td>
						<td><?php echo $row['EMailAddress'] ?></td>
						<td><?php echo $row['MobileNumber'] ?></td>
                        <td><?php echo $row['haik_Ref'] ?></td>
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