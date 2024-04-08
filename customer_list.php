<?php include'db_connect.php';
if($_SESSION['login_type'] == 4): 
{
	// header('Location: index.php?page=home');
	echo "<script type='text/javascript'>location.href = 'index.php';</script>";
	exit(0);
}
endif; ?>
<div class="col-lg-12">
	<table class="table table-borderless">
		<tr>
			<td class="text-center"><div class="btn btn-warning btn-sm btn-flat" style="color:#fff;">Connected: <b id="ConnectedCount"></b></div></td>
			<td class="text-center"><div class="btn btn-success btn-sm btn-flat">Active: <b id="ActiveCount"></b></div></td>
			<td class="text-center"><div class="btn btn-danger btn-sm btn-flat">Expired: <b id="ExpiredCount"></b></div></td>
		</tr>
	</table>
	<div class="card card-outline card-success">
		<div class="card-header">
            <?php
            if (isset($_SESSION['message']))
            {
                echo "<h4 style='color:green; font-weight:bold;'>".$_SESSION['message']."</h4>";
                unset ($_SESSION['message']);
            }
            ?>
			<table class="table table-borderless" style="table-layout: fixed;">
				<colgroup>
					<col style="width: 30%;" />
					<col style="width: 20%;" />
					<col style="width: 30%;" />
					<col style="width: 20%;" />
				</colgroup>
				<td>
					<input type="radio" name="Status"  value="All" checked="checked" />All
					<input type="radio" name="Status"  value="Active"/>Active
					<input type="radio" name="Status"  value="Expired"/>Expired
				</td>
				<td>Area: <p id="selectArea"></p></td>
				<td><div id="LocationSelect" style="display:none;">Estate/Court/Road: <p id="selectLocation"></p></div></td>
				<?php if($_SESSION['login_type'] != 3): ?>
				<td><div class="card-tools"><a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_customer"><i class="fa fa-plus"></i> Upload Customers File</a></div></td>
				<?php endif; ?>
			</table>
		</div>
		<div class="card-body">
			<table class="table table-hover table-bordered" id="list" style="table-layout: fixed;font-size:0.9vw;">
				<colgroup>
					<col style="width: 9%;" />
					<col style="width: 14%;" />
					<col style="width: 11%;" />
					<col style="width: 27%;" />
					<col style="width: 10%;" />
					<col style="width: 15%;" />
					<col style="width: 7%;" />
					<col style="width: 6%;" />
					<col class="hidethis" style="width: 10%;" />
					<col class="hidethis" style="width: 20%;" />
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Account ID</th>
						<th>Name</th>
						<th>Mobile No</th>
						<th>Address</th>
						<th>Service ID</th>
						<th>Current Package</th>
						<th>Status</th>
						<th>TED</th>
						<th class="hidethis">Area</th>
						<th class="hidethis">Estate/Court/Road</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($_GET['LocationCode'])
						$where = " AND `myCustomers`.`LocationCode` LIKE '".$_GET['LocationCode']."'";
					else $where = "";
					$qry = $conn->query($customersquery.$where);
					while($row= $qry->fetch_assoc()):
						$pageTitle = $row['EstateName'].", ".$row['AreaName'];
					?>
					<tr class="dataRow <?=$row['Status']?>">
						<td class="text-center"><b><?php echo $row['Account_ID'] ?></b></td>
						<td><?php echo ucwords($row['FirstName']. " ".$row['LastName']) ?></td>
						<td><?php echo $row['MobileNumber'] ?></td>
						<td><?= $row['Address']?></td>
						<td><?= $row['Service ID'] ?></td>
						<td><?= $row['Current_Package'] ?></td>
						<td><b style="color:<?= $row['Status'] == 'Active' ? "green" : "red"?>;"><?php echo $row['Status'] ?></b></td>
						<td><?= $row['TED'] ?></td>
						<td class="hidethis"><?= $row['AreaName'] ? $row['AreaName'] : "_" ?></td>
						<td class="hidethis"><?= $row['EstateName'] ? $row['EstateName'] : "_" ?></td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
				<!-- <tfoot>
					<tr>
						<th class="text-center">Account ID</th>
						<th>Status</th>
						<th>TED</th>
						<th>Current Package</th>
						<th>Area Name</th>
						<th>Estate/Court/Road</th>
						<th>Name</th>
						<th>Mobile Number</th>
						<th>E-Mail</th>
					</tr>
				</tfoot> -->
			</table>
			<form action="excel.php" method="POST" style="display:inline;">
                <select name="export_file_type" class="form_control">
                	<option value="xlsx">.xlsx</option>
                    <option value="xls">.xls</option>
                    <option value="csv">.csv</option>
                </select>
                <button type="submit" name="export_customers_btn" class="btn btn-primary btn-sm">Export</button>
            </form>
		</div>
	</div>
</div>
<script type="text/javascript">
	var primaryColIdx;
	var secondaryColIdx;

	const statusIndex = 6;
	const areaIndex = 8;
	const locationIndex = 9;

	$(".hidethis").hide()

	$(document).ready(function(){
		var mycustomers = $('#list').DataTable({
			initComplete: function () {
				populateDropdowns(this);
			},
			// "order": [[2, 'asc']],
		});
		<?php if ($_GET['LocationCode']): ?>
		document.getElementById('page-title').innerHTML = '<?= $pageTitle?>';
		<?php endif;?>
		document.getElementById('ActiveCount').innerHTML = $('#list').DataTable().rows('.Active').count();
		document.getElementById('ExpiredCount').innerHTML = $('#list').DataTable().rows('.Expired').count();
		document.getElementById('ConnectedCount').innerHTML = $('#list').DataTable().rows().count();
		$("input[type=radio]").change(function(){
			var filter = this.value;
			if (filter == "Active" || filter == "Expired")
				mycustomers.column(statusIndex).search(filter).draw();
			else mycustomers.column(statusIndex).search('').draw();
		});
	});

	$('#selectArea, #selectLocation').on('change', function () {
		// console.log($('#list').DataTable().rows({search:'applied'}).nodes().to$().filter('.Active').length);
    	document.getElementById('ActiveCount').innerHTML = $('#list').DataTable().rows({search:'applied'}).nodes().to$().filter('.Active').length;
		document.getElementById('ExpiredCount').innerHTML = $('#list').DataTable().rows({search:'applied'}).nodes().to$().filter('.Expired').length;
		document.getElementById('ConnectedCount').innerHTML = $('#list').DataTable().rows({search:'applied'}).count();
	})
	
	function populateDropdowns(table) {
		table.api().columns([locationIndex,areaIndex]).every( function () {
			var column = this;
			//console.log("processing col idx " + column.index());
			var select = $('<select><option value="">All</option></select>')
				.appendTo( $('#selectArea').empty() )
				.on( 'change', function () {
					var dropdown = this;
					doFilter(table, dropdown, column);
					rebuildSecondaryDropdown(table, column.index());
				} );

			column.data().unique().sort().each( function ( val, idx ) {
				select.append( '<option value="' + val + '">' + val + '</option>' )
			} );
			//console.log(select)
		} );
	}

	function doFilter(table, dropdown, column) {
		// first time a drop-down is used, it becomes the primary. This
		// remains the case until the page is refreshed:
		if (primaryColIdx == null) {
			primaryColIdx = column.index();
			secondaryColIdx = (primaryColIdx == areaIndex) ? locationIndex : areaIndex;
		}

		if (column.index() === primaryColIdx) {
			// reset all the filters because the primary is changing:
			table.api().search( '' ).columns().search( '' );
		}

		var filterVal = $.fn.dataTable.util.escapeRegex($(dropdown).val());
		console.log("firing dropdown for col idx " + column.index() + " with value " + filterVal);
		column
			.search( filterVal ? '^' + filterVal + '$' : '', true, false )
			.draw();
	}

	function rebuildSecondaryDropdown(table, primaryColIdx) {
		var secondaryCol;

		table.api().columns(secondaryColIdx).every( function () {
			secondaryCol = this;
		} );

		// get only the unfiltered (unhidden) values for the "other" column:
		var raw = table.api().columns( { search: 'applied' } ).data()[secondaryColIdx];
		// the following uses "spread syntax" (...) for sorting and de-duping:
		// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Spread_syntax
		var uniques = [...new Set(raw)].sort();

		var filteredSelect = $('<select><option value="">All</option></select>')
		.appendTo( $('#selectLocation').empty() )
		.on( 'change', function () {
			var dropdown = this;
			doFilter(table, dropdown, secondaryCol);
			//rebuildSecondaryDropdown(table, column.index());
		} );
		$('#LocationSelect').attr('style','display:block;');

		uniques.forEach(function (item, index) {
			filteredSelect.append( '<option value="' + item + '">' + item + '</option>' )
		} );
	}
</script>