<option value="" selected="true" disabled="disabled">--Select Province--</option>
<?php
	include('../../../settings/connect.php');
	if($_POST['id']){
		$id=$_POST['id'];	
		$psql = mysqli_query($link, "SELECT name, p_code FROM ".DB_PREFIX."provinces WHERE id = '$id'");
		while ($province = mysqli_fetch_array($psql)){
	
			$id=$province['p_code'];
			$data=$province['name'];
			echo '<option value="'.$id.'">'.$data.'</option>';
	
		}
	}
?>
<script type="text/javascript">$('#province').prop('disabled', false);</script>