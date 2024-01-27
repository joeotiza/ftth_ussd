<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$type_arr = array('',"Admin","Project Manager","Employee");
	$qry = $conn->query("SELECT *,concat(FirstName,' ',LastName) as name FROM `customer_details` where CustomerID = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<div class="card card-widget widget-user shadow">
      <div class="widget-user-header bg-dark">
        <h3 class="widget-user-username"><?php echo ucwords($name) ?></h3>
        <h5 class="widget-user-desc"><?php echo $MobileNumber ?></h5>
      </div>
      <div class="card-footer">
        <div class="container-fluid">
			<table>
				<thead>
					<tr>
						<th>&nbspRequest Time&nbsp</th>
						<th>&nbspLocation&nbsp</th>
						<th>&nbspCapacity</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$qry = $conn->query("SELECT * FROM `get_internet` WHERE CustomerID=".$_GET['id']);
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<td>&nbsp<?php echo $row['request_date'] ?>&nbsp</td>
						<td>&nbsp<?php echo $row['Location'] ?>&nbsp</td>
						<td>&nbsp<?php echo $row['Capacity'] ?>&nbsp</td>
					</tr>	
					<?php endwhile; ?>
				</tbody>
			</table>
        </div>
    </div>
	</div>
</div>
<div class="modal-footer display p-0 m-0">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>