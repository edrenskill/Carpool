<option value="" selected="true" disabled="disabled">--Select City/Municipality--</option>
<?php
	include('../../../settings/connect.php');
	if($_POST['id']){
		$id=$_POST['id'];	
		$csql = mysqli_query($link, "SELECT name, cm_code FROM ".DB_PREFIX."city_municipality WHERE id = '$id'");
		while ($city = mysqli_fetch_array($csql)){
	
			$id=$city['cm_code'];
			$data=$city['name'];
			echo '<option value="'.$id.'">'.$data.'</option>';
	
		}
	}
?>
<script type="text/javascript">$('#city').prop('disabled', false);</script>