<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<form action="excel.php" method="POST">
                <select name="export_file_type" class="form_control">
                	<option value="xlsx">.xlsx</option>
                    <option value="xls">.xls</option>
                    <option value="csv">.csv</option>
                </select>
                <button type="submit" name="export_areas_btn" class="btn btn-primary">Export</button>
            </form>
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href=<?= (!$_GET['id']) ? "./index.php?page=new_area" : "./index.php?page=new_location"?>><i class="fa fa-plus"></i> Add New <?= (!$_GET['id']) ? "Area" : "Location"?></a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Area Code</th>
						<th>Name</th>
						<th>Location Code</th>
                        <th>Estate/Court/Road</th>
						<th>Penetration</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
                    $where = ($_GET['id']) ? "WHERE `AreaID`=".$_GET['id'] : '';
					$i = 1;
					$qry = $conn->query("SELECT * FROM `AreaDetails` ".$where);
					while($row= $qry->fetch_assoc()):
                        $countestate = $conn->query("SELECT * FROM `LocationDetails` WHERE `LocationDetails`.`AreaCode`='".$row['AreaCode']."';")->num_rows;
                        $first = true;
					?>
					<tr>
						<th class="text-center" rowspan=<?=($countestate!=0) ? $countestate : 1?>><?php echo $i++ ?></th>
						<td rowspan=<?=($countestate!=0) ? $countestate : 1?>><b><?php echo $row['AreaCode'] ?></b></td>
                        <td rowspan=<?=($countestate!=0) ? $countestate : 1?>><b><?php echo ucwords($row['AreaName']) ?></b></td>
                        <?php
                        if ($countestate != 0){
                        	$qry2 = $conn->query("SELECT * FROM `LocationDetails` WHERE `AreaCode`='".$row['AreaCode']."';");
                        	while($row2=$qry2->fetch_assoc()):
                            	if (!$first) echo "<tr>";
                        ?>
						<td><?php echo $row2['LocationCode']; ?></td>
						<td><?php echo $row2['EstateName']; ?></td>
						<td><b id="<?=$row2['LocationCode']?>" style="color:#273c88;">...</b></td>
                        <?php if ($first || $_GET['id']){ ?>
						<td rowspan=<?=(!$_GET['id']) ? $countestate : 1?> class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
                            <?php if (!$_GET['id']){?>
		                      <a class="dropdown-item" href="./index.php?page=area_list&id=<?php echo $row['AreaID'] ?>">Locations</a>
		                      <div class="dropdown-divider"></div>
                              <?php } else $_SESSION['AreaCode'] = $row['AreaCode']; ?>
		                      <a class="dropdown-item" href=<?=(!$_GET['id'])? "./index.php?page=edit_area&id=".$row['AreaID'] : "./index.php?page=edit_location&id=".$row2['LocationID']?>>Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item <?=(!$_GET['id'])? "delete_area" : "delete_location"?>" href="javascript:void(0)" data-id="<?=(!$_GET['id']) ?  $row['AreaID'] : $row2['LocationID'] ?>">Delete</a>
		                    </div>
						</td>
                        <?php } $first=false; ?>
						
				<?php endwhile;}
                else
                {
                    ?>
                    <td></td><td></td>
                    <td rowspan=<?=(!$_GET['id'] || $countestate!=0) ? $countestate : 1?> class="text-center">
						<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                    Action
		                </button>
		                <div class="dropdown-menu" style="">
                        <?php if (!$_GET['id']){?>
		                    <a class="dropdown-item" href="./index.php?page=area_list&id=<?php echo $row['AreaID'] ?>">Locations</a>
		                    <div class="dropdown-divider"></div>
                            <?php } else $_SESSION['AreaCode'] = $row['AreaCode']; ?>
		                    <a class="dropdown-item" href=<?=(!$_GET['id'])? "./index.php?page=edit_area&id=".$row['AreaID'] : "./index.php?page=edit_location&id=".$row2['LocationID']?>>Edit</a>
		                    <div class="dropdown-divider"></div>
		                    <a class="dropdown-item <?=(!$_GET['id'])? "delete_area" : "delete_location"?>" href="javascript:void(0)" data-id="<?=(!$_GET['id']) ?  $row['AreaID'] : $row2['LocationID'] ?>">Delete</a>
		                </div>
					</td>
                    <?php
                }
                // echo "</tr>";
             endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
	<?php
	$qry = $conn->query($penetrationquery.$where);
	while($row= $qry->fetch_assoc()):
	?>
	document.getElementById('<?= $row['LocationCode'] ?>').innerHTML="<?= $row['Penetration'] ? round((float)$row['Penetration']*100).'%' : 'No Homes Passed';  ?>";
	<?php endwhile;?>
	$('.delete_area').click(function(){
	_conf("Are you sure to delete this area?","delete_area",[$(this).attr('data-id')])
	})
    $('.delete_location').click(function(){
	_conf("Are you sure to delete this?","delete_location",[$(this).attr('data-id')])
	})
    $('#list').dataTable()
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