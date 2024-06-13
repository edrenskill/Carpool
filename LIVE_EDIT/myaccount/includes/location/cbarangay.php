<option value="" selected="true" disabled="disabled">--Select Barangay--</option>
<?php
	include('../../../settings/connect.php');
	if($_POST['id']){
		$id=$_POST['id'];	
		$bsql = mysqli_query($link, "SELECT name, brgy_code FROM ".DB_PREFIX."barangays WHERE id = '$id'");
		while ($barangay = mysqli_fetch_array($bsql)){
	
			$id=$barangay['brgy_code'];
			$data=$barangay['name'];
			echo '<option value="'.$id.'">'.$data.'</option>';
	
		}
	}
?>
<script type="text/javascript">$('#cbarangay').prop('disabled', false);</script>