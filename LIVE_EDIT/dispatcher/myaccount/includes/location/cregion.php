<option value="" selected="true" disabled="disabled">--Select Region--</option>
<?php
	include('../../../settings/connect.php');
	if($_POST['id']){
		$id=$_POST['id'];	
		$rsql = mysqli_query($link, "SELECT name, r_code FROM ".DB_PREFIX."regions WHERE c_code = '$id'");
		while ($region = mysqli_fetch_array($rsql)){
	
			$id=$region['r_code'];
			$data=$region['name'];
			echo '<option value="'.$id.'">'.$data.'</option>';
	
		}
	}
?>
<script type="text/javascript">$('#cregion').prop('disabled', false);</script>