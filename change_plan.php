<?php include'db_connect.php';
if($_SESSION['login_type'] == 4): 
{
	// header('Location: index.php?page=home');
	echo "<script type='text/javascript'>location.href = 'index.php';</script>";
	exit(0);
}
endif; ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
            <?php
            if (isset($_SESSION['message']))
            {
                echo "<h4 style='color:red; font-weight:bold;'>".$_SESSION['message']."</h4>";
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
                    <button type="submit" name="export_change_plan_btn" class="btn btn-primary">Export</button>
                </form>
			</div>
		</div>
		<div class="card-body">
			<table class="table table-hover table-bordered" id="list" style="table-layout:fixed;font-size:0.9vw;">
				<colgroup>
                  <col width="9%">
                  <col width="10%">
                  <col width="19%">
                  <col width="9%">
                  <col width="9%">
                  <col width="7%">
                  <col width="7%">
                  <col width="14%">
				  <col width="9%">
				  <col width="9%">
                </colgroup>
				<thead>
					<tr>
						<th class="text-center">Customer ID</th>
						<th>Name</th>
                        <th>E-Mail Address</th>
						<th>Mobile Number</th>
                        <th>Account No.</th>
                        <th>From</th>
                        <th>To</th>
						<th>Request Time</th>
						<th>Platform</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//$i = 1;
					$type = array('',"Admin","Manager","Agent");
					$qry = $conn->query("SELECT * FROM `customers` RIGHT JOIN `plan_change`
                     ON `customers`.`Correlation ID`=`plan_change`.`Correlation ID` WHERE `Service Status` LIKE 'Active' ");

					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><b><?php echo $row['Customer ID'] ?></b></td>
						<td><?php echo ucwords($row['Customer Name']) ?></td>
						<td><?php echo $row['EMail Address'] ?></td>
                        <td><?php echo $row['Contact Number'] ?></td>
                        <td><?php echo $row['Correlation ID'] ?></td>
                        <td><?php echo $row['from_mbps'] ?></td>
                        <td><?php echo $row['to_mbps'] ?></td>
						<td><?php echo $row['request_time'] ?></td>
						<td class="text-center"><b><?= ($row['source'] == "WhatsApp") ? "WhatsApp" : $myussdcode?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item delete_change" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Resolve</a>
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
		$('#list').dataTable({order: [[7, 'desc']]})
		$('.delete_change').click(function(){
		_conf("Are you sure this plan change was resolved?","delete_change",[$(this).attr('data-id')])
		})
	})
	function delete_change($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_change',
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