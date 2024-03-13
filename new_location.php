<?php
if($_SESSION['login_type'] > 2): 
{
	// header('Location: index.php?page=home');
	echo "<script type='text/javascript'>location.href = 'index.php';</script>";
	exit(0);
}
endif;
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_location" enctype="multipart/form-data">
				<input type="hidden" name="LocationID" value="<?php echo isset($LocationID) ? $LocationID : '' ?>">
                <?php if(isset($_GET['AreaCode'])) $AreaCode=$_GET['AreaCode']; unset($_GET['AreaCode'])?>
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Location Code</label>
							<input type="text" name="LocationCode" class="form-control form-control-sm" required value="<?php echo isset($LocationCode) ? $LocationCode : 'lhkac-' ?>">
						</div>
                        <div class="form-group">
							<label for="" class="control-label">Area</label>
							<select name="AreaCode" id="AreaCode" class="custom-select custom-select-sm">
                                <?php
                                $qry = $conn->query("SELECT * FROM `AreaDetails`");
                                while($row= $qry->fetch_assoc()):
                                    ?>
                                    <option value=<?php echo $row['AreaCode']; echo isset($AreaCode) && $AreaCode==$row['AreaCode'] ? ' selected' : ''; echo ">".$row['AreaCode']." | ".$row['AreaName'];?></option>
                                    <?php
                                endwhile;
                                ?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Estate/Court/Road</label>
							<input type="text" class="form-control form-control-sm" name="EstateName" required value="<?php echo isset($EstateName) ? $EstateName : '' ?>">
							<small id="msg"></small>
						</div>
						<div class="form-group">
							<label class="control-label">Homes Passed</label>
							<input type="number" class="form-control form-control-sm" name="homes" required min="0" value="<?php echo isset($homes) ? $homes : '' ?>">
							<small id="msg"></small>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=area_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('#manage_location').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')

		$.ajax({
			url:'ajax.php?action=save_location',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully saved.",'success')
					setTimeout(function(){
						location.replace('./index.php?page=area_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Unexpected error.</div>");
					end_load();
				}
			}
		})
	})
</script>