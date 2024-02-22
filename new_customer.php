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
            <h3>Supported formats: .xlsx/.xls/.csv</h3>
			<form action="excel.php" method="POST" enctype="multipart/form-data">
                <label for="" class="control-label">Prism Active file</label><br>
                Delete the empty column "J" but Leave the first row containing "LTK Active GPON Accounts" text<br>
                <input type="file" name="import_file" class="form_control"/>
                <button type="submit" name="save_excel_data" class="btn btn-primary mt-3">Import</button><br>
                <label for="" class="control-label">Pyramite Active file</label><br>
                <input type="file" name="import_pyramite_active" class="form_control"/>
                <button type="submit" name="save_pyramite_active" class="btn btn-primary mt-3">Import</button><br>
                <label for="" class="control-label">Pyramite Expired file</label><br>
                <input type="file" name="import_pyramite_expired" class="form_control"/>
                <button type="submit" name="save_pyramite_expired" class="btn btn-primary mt-3">Import</button><br>
                <label for="" class="control-label">Customer Location Codes file</label><br>
                Columns "ONT_Username" and "ONT_Location_Code"<br>
                <input type="file" name="import_location_codes" class="form_control"/>
                <button type="submit" name="save_location_codes" class="btn btn-primary mt-3">Import</button>	
			</form>
		</div>
	</div>
</div>