<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_faq" enctype="multipart/form-data">
				<input type="hidden" name="questionID" value="<?php echo isset($questionID) ? $questionID : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Question</label>
							<input type="text" name="question" class="form-control form-control-sm" required value="<?php echo isset($question) ? $question : '' ?>">
						</div>
					</div>
					<div class="col-md-6">
						
						<div class="form-group">
							<label class="control-label">Answer</label>
							<input type="text" class="form-control form-control-sm" name="answer" required value="<?php echo isset($answer) ? $answer : '' ?>">
							<small id="msg"></small>
						</div>

					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=faqs'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('#manage_faq').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')

		$.ajax({
			url:'ajax.php?action=save_faq',
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
						location.replace('./index.php?page=faqs')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Unexpected error.</div>");
					end_load();
				}
			}
		})
	})
</script>