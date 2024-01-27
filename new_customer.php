<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
        <?php
            if (isset($_SESSION['message']))
            {
                echo "<h4>".$_SESSION['message']."</h4>";
                unset ($_SESSION['message']);
            }
            ?>
			<form action="excel.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="import_file" class="form_control"/>
                <button type="submit" name="save_excel_data" class="btn btn-primary mt-3">Import</button>				
			</form>
		</div>
	</div>
</div>