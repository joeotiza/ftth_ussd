<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
            <?php
            if (isset($_SESSION['message']))
            {
                echo "<h4 style='color:green; font-weight:bold;'>".$_SESSION['message']."</h4>";
                unset ($_SESSION['message']);
            }
            ?>
			<form action="excel.php" method="POST" style="display:inline;">
                <select name="export_file_type" class="form_control">
                	<option value="xlsx">.xlsx</option>
                    <option value="xls">.xls</option>
                    <option value="csv">.csv</option>
                </select>
                <button type="submit" name="export_customers_btn" class="btn btn-primary">Export</button>
            </form>
			<div class="card-tools" style="display:inline;">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_customer"><i class="fa fa-plus"></i> Upload Customers File</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table">
				<tr>
					<td class="text-center"><div class="btn btn-warning" style="color:#fff;">Connected: <b id="ConnectedCount"></b></div></td>
					<td class="text-center"><div class="btn btn-success">Active: <b id="ActiveCount"></b></div></td>
					<td class="text-center"><div class="btn btn-danger">Expired: <b id="ExpiredCount"></b></div></td>
				</tr>
			</table>
			<table class="table table-hover table-bordered" id="list" style="table-layout: fixed;">
				<colgroup>
					<col style="width: 11%;" />
					<col style="width: 10%;" />
					<col style="width: 10%;" />
					<col style="width: 10%;" />
					<col style="width: 19%;" />
					<col style="width: 15%;" />
					<col style="width: 11%;" />
					<col style="width: 14%;" />
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Account ID</th>
						<th>Status</th>
						<th>Current Package</th>
						<th>Area Name</th>
						<th>Estate/Court/Road</th>
						<th>Name</th>
						<th>Mobile Number</th>
						<th>E-Mail</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$qry = $conn->query($customersquery);
					while($row= $qry->fetch_assoc()):
					?>
					<tr class="dataRow <?=$row['Status']?>">
						<th class="text-center"><?php echo $row['Account_ID'] ?></th>
						<td><b style="color:<?= $row['Status'] == 'Active' ? "green" : "red"?>;"><?php echo $row['Status'] ?></b></td>
						<td><?php echo $row['Current_Package'] ?></td>
						<td><?php echo $row['AreaName'] ?></td>
						<td><?php echo $row['EstateName'] ?></td>
						<td><?php echo ucwords($row['FirstName']. " ".$row['LastName']) ?></td>
						<td><?php echo $row['MobileNumber'] ?></td>
						<td><?php echo $row['Email'] ?></td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
				<tfoot>
					<tr>
						<th class="text-center">Account ID</th>
						<th><input type="radio" name="Status"  value="All" checked="checked" />All<br/>
							<input type="radio" name="Status"  value="Active"/>Active<br/>
							<input type="radio" name="Status"  value="Expired"/>Expired<br/></th>
						<th>Current Package</th>
						<th>Area Name</th>
						<th>Estate/Court/Road</th>
						<th>Name</th>
						<th>Mobile Number</th>
						<th>E-Mail</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	var primaryColIdx;
	var secondaryColIdx;

	$(document).ready(function(){
		var mycustomers = $('#list').DataTable({
			initComplete: function () {
				populateDropdowns(this);
			}
		});
		document.getElementById('ActiveCount').innerHTML = $('#list').DataTable().rows('.Active').count();
		document.getElementById('ExpiredCount').innerHTML = $('#list').DataTable().rows('.Expired').count();
		document.getElementById('ConnectedCount').innerHTML = $('#list').DataTable().rows().count();
		$("input[type=radio]").change(function(){
			var filter = this.value;
			if (filter == "Active" || filter == "Expired")
				mycustomers.column(1).search(filter).draw();
			else mycustomers.column(1).search('').draw();
		});
	});

	$('#list').on('change', function () {
		//console.log($('#list').DataTable().rows({search:'applied'}).nodes().to$().filter('.Active').length);
    	document.getElementById('ActiveCount').innerHTML = $('#list').DataTable().rows({search:'applied'}).nodes().to$().filter('.Active').length;
		document.getElementById('ExpiredCount').innerHTML = $('#list').DataTable().rows({search:'applied'}).nodes().to$().filter('.Expired').length;
		document.getElementById('ConnectedCount').innerHTML = $('#list').DataTable().rows({search:'applied'}).count();
	})
	
	function populateDropdowns(table) {
		table.api().columns([3,4]).every( function () {
			var column = this;
			//console.log("processing col idx " + column.index());
			var select = $('<select style="width: 100%;"><option value=""></option></select>')
				.appendTo( $(column.footer()).empty() )
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
			secondaryColIdx = (primaryColIdx == 3) ? 4 : 3;
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

		var filteredSelect = $('<select style="width: 100%;"><option value=""></option></select>')
			.appendTo( $(secondaryCol.footer()).empty() )
			.on( 'change', function () {
				var dropdown = this;
				doFilter(table, dropdown, secondaryCol);
				//rebuildSecondaryDropdown(table, column.index());
			} );

		uniques.forEach(function (item, index) {
			filteredSelect.append( '<option value="' + item + '">' + item + '</option>' )
		} );
	}
</script>