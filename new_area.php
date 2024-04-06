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
			<form action="" id="manage_area" enctype="multipart/form-data">
				<input type="hidden" name="AreaID" value="<?php echo isset($AreaID) ? $AreaID : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Area Code</label>
							<input type="text" name="AreaCode" class="form-control form-control-sm" required value="<?php echo isset($AreaCode) ? $AreaCode : 'lhkac-' ?>">
							<small id="msg"></small>
						</div>
					</div>
					<div class="col-md-6">
						
						<div class="form-group">
							<label class="control-label">Name</label>
							<input type="text" class="form-control form-control-sm" name="AreaName" required value="<?php echo isset($AreaName) ? $AreaName : '' ?>">
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
	$('#manage_area').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')

		$.ajax({
			url:'ajax.php?action=save_area',
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
					$('#msg').html("<div class='alert alert-danger'>An Area with this code already exists.</div>");
					end_load();
				}
			}
		})
	})
</script>