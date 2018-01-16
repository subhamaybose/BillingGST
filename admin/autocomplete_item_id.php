<?php
include("../includes/config.php");include("sessiontime.php");
include("../includes/utils.inc.php");
$items=array();
$q = strtolower($_GET["q"]);
if (!$q) return;
 $sqlItem="select item_name,item_code  from item_master $cond order by item_code ";
  $rs_usr=mysql_query($sqlItem);
  if(mysql_num_rows($rs_usr)>0){
	  while($row_usr=mysql_fetch_array($rs_usr)){
		  $key=$row_usr["item_code"];
 		  $items[$key]=str_replace("'","\'",$row_usr["item_name"]);
	  }
   }
  // print_r($items);
 foreach ($items as $key=>$value) {
	if (strpos(strtolower($key), $q) !== false) {
		 echo "$key|$value\n";
		//echo "$value|$key\n";
	}
}
 ?>