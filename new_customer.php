<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
            <?php
            if (isset($_SESSION['message']))
            {
                echo "<h4 style='color:red; font-weight:bold;'>".$_SESSION['message']."</h4>";
                unset ($_SESSION['message']);
            }
            ?>
            <h3 style="color:#273c88;"><b>Supported formats: <a style="color:#f89e3c;">.xlsx/.xls/.csv</a></b></h3><br>
			<form action="excel.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-light shadow-sm border">
                            <div class="inner">
                                <h3>Pyramite Active file</h3>
                                <input type="file" name="import_pyramite_active" class="form_control"/>
                                <button type="submit" name="save_pyramite_active" class="btn btn-primary">Import</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-light shadow-sm border">
                            <div class="inner">
                                <h3>Pyramite Expired file</h3>
                                <input type="file" name="import_pyramite_expired" class="form_control"/>
                                <button type="submit" name="save_pyramite_expired" class="btn btn-primary">Import</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-light shadow-sm border">
                            <div class="inner">
                                <h3 style="display: inline-block;">Prism file</h3><h6 style="display: inline-block;">&nbsp(LTK-ActiveGPONAccounts)</h6>
                                <input type="file" name="import_file" class="form_control"/>
                                <button type="submit" name="save_excel_data" class="btn btn-primary">Import</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-light shadow-sm border">
                            <div class="inner">
                                <h6><b>Customer Location Codes file</b></h6>
                                Columns <b>ONT_Username</b> and <b>ONT_Location_Code</b>
                                <input type="file" name="import_location_codes" class="form_control"/>
                                <button type="submit" name="save_location_codes" class="btn btn-primary">Import</button>
                            </div>
                        </div>
                    </div>
                </div>	
			</form>
		</div>
	</div>
</div>