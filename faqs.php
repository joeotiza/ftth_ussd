<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<?php if ($_SESSION['login_type'] <= 2):?>
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_faq"><i class="fa fa-plus"></i> Add New FAQ</a>
			</div>
			<?php endif;?>
		</div>
		<div class="card-body">
			<table class="table table-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>FAQ</th>
						<th>Answer</th>
						<th <?= ($_SESSION['login_type'] > 2) ? "style='display:none;'" : "style=''"; ?>>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM `questions`");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><?php echo $row['question'] ?></td>
						<td><?php echo $row['answer'] ?></td>
						<td <?= ($_SESSION['login_type'] > 2) ? "style='display:none;'" : "style=''"; ?> class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item" href="./index.php?page=edit_faq&id=<?php echo $row['questionID'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_faq" href="javascript:void(0)" data-id="<?php echo $row['questionID'] ?>">Delete</a>
		                    </div>
						</td>
					</tr>	
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.delete_faq').click(function(){
	_conf("Are you sure you want to delete this FAQ?","delete_faq",[$(this).attr('data-id')])
	})
	})
	function delete_faq($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_faq',
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