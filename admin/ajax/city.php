<?
include("../../includes/config.php");
$qls=mysql_query("select * from `cities` where `state_id`='".$_REQUEST['state']."'");
		if(mysql_num_rows($qls)>0){
			echo '<option value="0" selected="" disabled="">Select City</option>';
		while($df=mysql_fetch_array($qls)){
			echo '<option value="'.$df["id"].'">'.$df["name"].'</option>';
		}}
?>