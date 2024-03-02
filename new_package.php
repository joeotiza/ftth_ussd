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
			<form action="" id="manage_package" enctype="multipart/form-data">
				<input type="hidden" name="PackageID" value="<?php echo isset($PackageID) ? $PackageID : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Capacity (Mbps)</label>
							<input type="text" name="Capacity" class="form-control form-control-sm" required value="<?php echo isset($Capacity) ? $Capacity : '' ?>">
						</div>
                        <div class="form-group">
							<label for="" class="control-label">Description</label>
							<input type="text" name="Description" class="form-control form-control-sm" required value="<?php echo isset($Description) ? $Description : '' ?>">
						</div>
					</div>
					<div class="col-md-6">
						
						<div class="form-group">
							<label class="control-label">Price (KES)</label>
							<input type="text" class="form-control form-control-sm" name="Price" required value="<?php echo isset($Price) ? $Price : '' ?>">
							<small id="msg"></small>
						</div>

					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=packages'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('#manage_package').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')

		$.ajax({
			url:'ajax.php?action=save_package',
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
						location.replace('./index.php?page=packages')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Unexpected error.</div>");
					end_load();
				}
			}
		})
	})
</script>