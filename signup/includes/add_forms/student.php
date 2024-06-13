<label><span style="color:#0000FF">Student</span></label>

<div class="form-group">
	<label>School Name<span style="color:#FF0000">*</span></label>
	<input class="form-control" type="text" name="school-name" id="school-name" value="<?php IF(isset($_SESSION['schoolname'])): echo $_SESSION['schoolname']; ENDIF; ?>" PLACEHOLDER="School Name" style="width:300px" />
</div>

<div class="form-group">
	<label>ID Number<span style="color:#FF0000">*</span></label>
	<input class="form-control" type="text" name="school-id" id="school-id" value="" PLACEHOLDER="School ID No." style="width:300px" />
</div>

<div class="form-group">
	<label>Expiry Date<span style="color:#FF0000">*</span></label>
	<input class="form-control" type="date" name="schoo-expiry" id="school-expiry" value="" PLACEHOLDER="Expiration Date" style="width:300px" />
</div>

<!--
<div class="form-group">
	<label>Upload picture of your student ID.<span style="color:#FF0000">*</span></label>
	<label>File input</label><input type="file" name="id_pic" id="id_pic">
</div>
-->