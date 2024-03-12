<?php include 'db_connect.php' ?>
<style>
table tr:hover {
	color: #fff;
    background-color: rgba(248,158,60,1); /* Change to desired background color */
}
tr td.sorting_1, tr td.sorting_2, tr td.sorting_3 {
    vertical-align: middle; /* Change to desired background color */
}
</style>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<?php if($_SESSION['login_type'] != 4): ?>
			<form action="excel.php" method="POST" style="display:inline;">
                <select name="export_file_type" class="form_control">
                	<option value="xlsx">.xlsx</option>
                    <option value="xls">.xls</option>
                    <option value="csv">.csv</option>
                </select>
                <button type="submit" name="export_areas_btn" class="btn btn-primary">Export</button>
            </form>
			<?php endif;?>
			<?php if($_SESSION['login_type'] <= 2): ?>
			<div class="card-tools" style="display:inline;">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href=<?= (!$_GET['id']) ? "./index.php?page=new_area" : "./index.php?page=new_location"?>><i class="fa fa-plus"></i> Add New <?= (!$_GET['id']) ? "Area" : "Location"?></a>
			</div>
			<?php endif;?>
		</div>
		<div class="card-body">
			<table class="table table-bordered text-center" id="list" style="table-layout:fixed;font-size:1.1vw;">
				<colgroup>
					<col style="width: 12%;" />
					<col style="width: 11%;" />
					<col style="width: 17%;" />
					<col style="width: 8%;" />
					<col style="width: 9%;" />
					<col style="width: 10%;" />
					<col style="width: 12%;" />
					<?= ($_SESSION['login_type'] <= 2) ? "<col style='width: 8%;' />" : ''?>
				</colgroup>
				<thead>
					<tr>
						<th>Location Code</th>
						<th>Area Name</th>
						<th>Estate/Court/Road</th>
						<th>Active</th>
						<th>Expired</th>
						<th>Connected</th>
						<th>Penetration</th>
						<?= ($_SESSION['login_type'] <= 2) ? "<th>Action</th>" : ''?>
						
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
<script>
	<?php
		$where = ($_GET['id']) ? "WHERE `AreaID`=".$_GET['id'] : '';
		$qry = $conn->query($penetrationquery.$where);
		$data = [];
		while($row = $qry->fetch_assoc()) {
			// $countestate = $conn->query("SELECT * FROM `LocationDetails` WHERE `LocationDetails`.`AreaCode`='".$row['AreaCode']."';")->num_rows;
			// $row['countestate'] = $countestate;
			$data[] = $row;
			// array_push($data, $row);
		}
		// echo json_encode($data);
	?>
	<?= json_encode($data)?>;
	$(document).ready(function(){
		$('#list').DataTable({
			"data": <?= json_encode($data)?>,
			"columns": [
				{ "data": "LocationCode" },
				{ "data": "AreaName" },
				{ "data": "EstateName" },
				{ "data": "Active" },
				{ "data": "Expired" },
				{ "data": "Connected" },
				// { "data": "Penetration" },
				{
					"data": "Penetration",
					"render": function(data, type, row) {
						// Return status based on your logic
						// console.log(row['Penetration']);
						if (row['Penetration'] != null)
						{
							return '<b>'+(row['Penetration']*100).toFixed(0) + '%</b>';
						}
						else
						{
							return "<b>No Homes Passed</b>";
						}
					}
				}
				<?php if ($_SESSION['login_type'] <= 2): ?>
				,{
					"data": "AreaCode",
					"render": function(data, type, row, meta) {
						// Create a dropdown button element
						var dropdownButton = $('<button>')
							.attr('class', 'btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle')
							.attr('type', 'button')
							.attr('data-toggle', 'dropdown')
							.text('Action');

						// Create a dropdown menu
						var dropdownMenu = $('<div>').attr('class', 'dropdown-menu');

						// Add options to the dropdown menu
						<?php if (!$_GET['id']) { ?>
						var locationsOption = $('<a>').attr('class', 'dropdown-item text-center').attr('href', './index.php?page=area_list&id='+row['AreaID']).text('Edit Estates');
						var editOption = $('<a>').attr('class', 'dropdown-item text-center').attr('href', './index.php?page=edit_area&id='+row['AreaID']).text('Edit Area');
						var deleteOption = $('<a>').attr('class', 'dropdown-item text-center delete_area').attr('data-id', row['AreaID']).attr('href', 'javascript:void(0)').text('Delete Area');
						<?php } else { ?>
						var locationsOption = '';
						var editOption = $('<a>').attr('class', 'dropdown-item text-center').attr('href', './index.php?page=edit_location&id='+row['LocationID']).text('Edit');
						var deleteOption = $('<a>').attr('class', 'dropdown-item text-center delete_location').attr('data-id', row['LocationID']).attr('href', 'javascript:void(0)').text('Delete');
						<?php } ?>

						// Append options to the dropdown menu
						dropdownMenu.append(locationsOption, editOption, deleteOption);

						// Wrap dropdown menu with the dropdown button
						dropdownButton.wrap('<div class="dropdown"></div>').parent().append(dropdownMenu);

						// Return the HTML content of the dropdown button
						return dropdownButton.parent().html();
					}

				}
				<?php endif; ?>
			],
			"createdRow": function(row, data, dataIndex) {
				var locationCode = data.LocationCode; // Get the value of LocationCode for this row
				$(row).css("cursor", "pointer"); // Change cursor to pointer to indicate clickability
				$(row).on("click", function() {
					// Redirect or perform action when the row is clicked
					window.location.href = './index.php?page=customer_list&LocationCode='+locationCode;
				});
			},
			"columnDefs": [
				{ 
					"targets": [4], // Apply to column indeces
					"render": function(data, type, row, meta) {
						// Add bold attribute and vertically align middle to the content
						return '<div style="color:green;">' + data + '</div>';
					}
				},
				{ 
					"targets": [5], // Apply to column indeces
					"render": function(data, type, row, meta) {
						// Add bold attribute and vertically align middle to the content
						return '<div style="color:red;">' + data + '</div>';
					}
				},
				{ 
					"targets": [0,1], // Apply to column indeces
					"render": function(data, type, row, meta) {
						// Add bold attribute and vertically align middle to the content
						return '<div style="font-weight:bold;">' + data + '</div>';
					}
				}
			],
			"order": [[0, 'asc']], // Order by the first column (Area Code) in ascending order
    		rowsGroup: [
				0,
				1,
				<?= (!$_GET['id'] && $_SESSION['login_type'] <= 2) ? "8" : "" ?>
				// "dataSrc": [0, 1] // Group by the first and second columns (Area Code and Area Name)
			],
		});
		$('.delete_area').click(function(){
			_conf("Are you sure to delete this area?","delete_area",[$(this).attr('data-id')])
		})
		$('.delete_location').click(function(){
			_conf("Are you sure to delete this?","delete_location",[$(this).attr('data-id')])
		})
		// $('#list').dataTable()
	})
	function delete_area($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_area',
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
    function delete_location($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_location',
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