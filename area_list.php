<?php include 'db_connect.php' ?>
<style>
tr td.sorting_1, tr td.sorting_2, tr td.sorting_3 {
    vertical-align: middle; /* Change to desired background color */
}
.card-tools{
	padding: 7px;
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
				<a class="btn btn-primary before-check" href="./index.php?page=new_area"><i class="fa fa-plus"></i> Add New Area</a>
			</div>
			<div class="card-tools" style="display:inline;">
				<a class="btn btn-danger after-check delete-area" id="deleteArea"><i class="fa fa-trash-can"></i> Delete Area</a>
			</div>
			<div class="card-tools" style="display:inline;">
				<a class="btn btn-warning after-check" id="editArea"><i class="fa fa-pen-to-square"></i> Edit Area</a>
			</div>
			<div class="card-tools" style="display:inline;">
				<a class="btn btn-primary after-check" id="newLocation"><i class="fa fa-plus"></i> Add New Location</a>
			</div>
			<div class="card-tools" style="display:inline;">
				<a class="btn btn-warning after-check" id="editLocation"><i class="fa fa-pen-to-square"></i> Edit Location</a>
			</div>
			<div class="card-tools" style="display:inline;">
				<a class="btn btn-danger after-check delete-location" id="deleteLocation"><i class="fa fa-trash-can"></i> Delete Location</a>
			</div>
			<?php endif;?>
		</div>
		<div class="card-body">
			<table class="table table-bordered table-hover text-center" id="list" style="table-layout:fixed;font-size:1.1vw;">
				<colgroup>
					<?= ($_SESSION['login_type'] <= 2) ? "<col style='width: 7%;' />" : ''?>
					<col style="width: 11%;" />
					<col style="width: 9%;" />
					<col style="width: 25%;" />
					<col style="width: 7%;" />
					<col style="width: 8%;" />
					<col style="width: 9%;" />
					<col style="width: 11%;" />
				</colgroup>
				<thead>
					<tr>
						<?= ($_SESSION['login_type'] <= 2) ? "<th>Select to Edit</th>" : ''?>
						<th>Location Code</th>
						<th>Area Name</th>
						<th>Estate/Court/Road</th>
						<th>Active</th>
						<th>Expired</th>
						<th>Connected</th>
						<th>Penetration</th>
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
		$('.before-check').attr('style', 'display:block;');
		$('.after-check').attr('style', 'display:none;');
		$('#list').DataTable({
			"data": <?= json_encode($data)?>,
			"columns": [
				<?php if($_SESSION['login_type'] <= 2): ?>
				{ "data": "AreaCode",
					"render": function(data, type, row) {
						return "<input type='checkbox' name='selectLocation'><div style='display:none;'>"+row['AreaID']+" "+row['LocationID']+" "+row['AreaCode']+"</div>";
					}
				},
				<?php endif; ?>
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
				// ,{
				// 	"data": "AreaCode",
				// 	"render": function(data, type, row, meta) {
				// 		// Create a dropdown button element
				// 		var dropdownButton = $('<button>')
				// 			.attr('class', 'btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle')
				// 			.attr('type', 'button')
				// 			.attr('data-toggle', 'dropdown')
				// 			.text('Action');

				// 		// Create a dropdown menu
				// 		var dropdownMenu = $('<div>').attr('class', 'dropdown-menu');

				// 		// Add options to the dropdown menu
				// 		<?php if (!$_GET['id']) { ?>
				// 		var locationsOption = $('<a>').attr('class', 'dropdown-item text-center').attr('href', './index.php?page=area_list&id='+row['AreaID']).text('Edit Estates');
				// 		var editOption = $('<a>').attr('class', 'dropdown-item text-center').attr('href', './index.php?page=edit_area&id='+row['AreaID']).text('Edit Area');
				// 		var deleteOption = $('<a>').attr('class', 'dropdown-item text-center delete_area').attr('data-id', row['AreaID']).attr('href', 'javascript:void(0)').text('Delete Area');
				// 		<?php } else { ?>
				// 		var locationsOption = '';
				// 		var editOption = $('<a>').attr('class', 'dropdown-item text-center').attr('href', './index.php?page=edit_location&id='+row['LocationID']).text('Edit');
				// 		var deleteOption = $('<a>').attr('class', 'dropdown-item text-center delete_location').attr('data-id', row['LocationID']).attr('href', 'javascript:void(0)').text('Delete');
				// 		<?php } ?>

				// 		// Append options to the dropdown menu
				// 		dropdownMenu.append(locationsOption, editOption, deleteOption);

				// 		// Wrap dropdown menu with the dropdown button
				// 		dropdownButton.wrap('<div class="dropdown"></div>').parent().append(dropdownMenu);

				// 		// Return the HTML content of the dropdown button
				// 		return dropdownButton.parent().html();
				// 	}

				// }
				<?php endif; ?>
			],
			<?php if ($_SESSION['login_type'] <= 2): ?>
			"initComplete": function() {
				// Handle radio button selection
				$('#list tbody').on('change', 'input[type="checkbox"]', function() {
					var $row = $(this).closest('tr');
					// var locationCode = $row.find('td:eq(1)').text(); // Get LocationCode value from the second column
					var areaLocation = $row.find('td:eq(0)').text().split(/[ ,]+/);
					var areaID = areaLocation[0];
					var locationID = areaLocation[1];
					var areaCode = areaLocation[2];
					// console.log(areaCode);
					$('#list tbody tr').not($row).find('input[type="checkbox"]').prop('checked', false);
					$('.before-check').attr('style', 'display:none;');
					$('.after-check').attr('style', 'display:block;');
					$('#newLocation').attr('href', './index.php?page=new_location&AreaCode='+areaCode);
					$('#editLocation').attr('href', './index.php?page=edit_location&id='+locationID);
					$('#editArea').attr('href', './index.php?page=edit_area&id='+areaID);
					$('#deleteArea').attr('data-id', areaID).attr('href', 'javascript:void(0)');
					$('#deleteLocation').attr('data-id', locationID).attr('href', 'javascript:void(0)');
				});
			},
			<?php endif; ?>
			<?php if ($_SESSION['login_type'] != 4): ?>
			"createdRow": function(row, data, dataIndex) {
				var locationCode = data.LocationCode; // Get the value of LocationCode for this row
				$(row).css("cursor", "pointer"); // Change cursor to pointer to indicate clickability
				$(row).on("click", function(e) {
					if ($(e.target).index() > 0) {//exclude first column
						// Redirect or perform action when the row is clicked
						window.location.href = './index.php?page=customer_list&LocationCode='+locationCode;
					}
				});
			},
			<?php endif; ?>
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
		$('#deleteArea').click(function(){
			_conf("Are you sure to delete this area?","delete_area",[$(this).attr('data-id')])
		})
		$('#deleteLocation').click(function(){
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