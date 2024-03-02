<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<form action="excel.php" method="POST">
                    <select name="export_file_type" class="form_control">
                        <option value="xlsx">.xlsx</option>
                        <option value="xls">.xls</option>
                        <option value="csv">.csv</option>
                    </select>
                    <button type="submit" name="export_new_customers_btn" class="btn btn-primary">Export</button>
                </form>
			</div>
		</div>
		<div class="card-body">
			<table class="table table-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Name</th>
						<th>Mobile Number</th>
						<th>Request Time</th>
						<th>Platform</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$type = array('',"Admin","Manager","Agent");
					$qry = $conn->query("SELECT *,concat(FirstName,' ',LastName) as name FROM `customer_details` order by reg_date desc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><?php echo ucwords($row['name']) ?></td>
						<td><?php echo $row['Contact Number'] ?></td>
						<td><?php echo $row['reg_date'] ?></td>
						<td class="text-center"><b><?= ($row['source'] == "WhatsApp") ? "WhatsApp" : $myussdcode?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_customer" href="javascript:void(0)" data-id="<?php echo $row['Customer ID'] ?>">View</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_customer" href="javascript:void(0)" data-id="<?php echo $row['Customer ID'] ?>">Delete</a>
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
	$('.view_customer').click(function(){
		uni_modal("<i class='fa fa-id-card'></i> User Details","view_customer.php?id="+$(this).attr('data-id'))
	})
	$('.delete_customer').click(function(){
	_conf("Are you sure to delete this record?","delete_customer",[$(this).attr('data-id')])
	})
	})
	function delete_customer($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_customer',
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