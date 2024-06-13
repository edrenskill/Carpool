<?php 
IF (stripos($member_ID, "TEMP") !== false):
?>
	<div class="col-lg-4" style="width:275">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Member's ID number update
			</div>
			<div class="panel-body">
				<div class="panel-body" id="enterNewID">
					<fieldset>            
						<div class="form-group">
							<h5>Tap ID Card or enter Card number Manually</h5>
						</div>
						<div class="form-group">
							<h4><span class="NewNumberChecked" id="NewNumberChecked" name="NewNumberChecked"></span></h4>
						</div>
						<div class="form-group">
							<h5>Temporary ID No.:</h5><h4><?= $member_ID; ?></h4>
							<input class="form-control" placeholder="New Member's ID" id="newmemberID" name="newmemberID" autofocus onkeypress="return isNumberKey(event)">
						</div>
						<button type="button" class="btn btn-lg btn-success btn-block" id="assignnew" name="assignnew"/>Update ID Number</button>			
					</fieldset>
				</div>
				<div class="panel-body" id="AssignResult" style="display:none;">
					<fieldset>
						<div class="form-group">
							<span class="IDAssigned" id="IDAssigned" name="IDAssigned"></span>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="panel-footer">
			</div>
		</div>
	</div>
<?php 
ENDIF;
?>

<div class="col-lg-4" style="width:275">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Member's Photo Upload
        </div>
        <div class="panel-body">
			<form action="components/upload/member_photo_upload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
				<div class="panel-body" id="enterNewID">
					<fieldset>
						<div class="form-group">
							<h5>Select Photo</h5>
						</div>
						<div class="form-group">
							<input class="form-control" name="image_file" id="imageInput" type="file" />
						</div>
						<input class="btn btn-lg btn-success btn-block" type="submit" id="submit-btn" value="Upload" />
						<img src="../myaccount/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
					</fieldset>
				</div>
			</form>
        </div>
        <div class="panel-footer">
            <div id="output" align="center">
				<?php $myname = mysqli_fetch_array(mysqli_query($link, "SELECT photo FROM ".DB_PREFIX."users WHERE user_ID = '{$member_ID}'")); ?>
				<span class="image avatar">
					<?php IF($myname['photo'] == ""){ $avatar = "../../myaccount/images/avatar1.jpg"; }ELSE{ $avatar = "../../myaccount/members/".$member_ID."/".$myname['photo']; } ?>
					<img src="<?= $avatar; ?>" alt="" width="200"/>
				</span>
			
			</div>
        </div>
    </div>
</div>

<div class="col-lg-4" style="width:275">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Member's Signature Upload
        </div>
        <div class="panel-body">
			<form action="components/upload/member_signature_upload.php" method="post" enctype="multipart/form-data" id="MySignatureUploadForm">
				<div class="panel-body" id="enterNewID">
					<fieldset>
						<div class="form-group">
							<h5>Select Signature File</h5>
							<h5>Only allow .PNG file for signature</h5>
						</div>
						<div class="form-group">
							<input class="form-control" name="image_file" id="SignatureInput" type="file" />
						</div>
						<input class="btn btn-lg btn-success btn-block" type="submit" id="submit-sig-btn" value="Upload" />
						<img src="../myaccount/images/ajax-loader.gif" id="loading-sig-img" style="display:none;" alt="Please Wait"/>
					</fieldset>
				</div>
			</form>
        </div>
        <div class="panel-footer">
            <div id="signature_output" align="center">
				<?php $myname = mysqli_fetch_array(mysqli_query($link, "SELECT signature FROM ".DB_PREFIX."users WHERE user_ID = '{$member_ID}'")); ?>
				<span class="image avatar">
					<?php IF($myname['signature'] == ""){ $signature = "../../myaccount/images/signature.png"; }ELSE{ $signature = "../../myaccount/members/".$member_ID."/signature/".$myname['signature']; } ?>
					<img src="<?= $signature; ?>" alt="" width="200"/>
				</span>
			
			</div>
        </div>
    </div>
</div>
