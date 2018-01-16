<?php
 
###########################################
# function to show Item Grade/Category    #
# date : 16.02.2009         			  #
###########################################
function shw_category($key){
$sql_category= mysql_query("SELECT category_name FROM category WHERE category_id ='$key'")or die(mysql_error());
$r_category= mysql_fetch_array($sql_category);
return stripslashes($r_category['category_name']);
}

##############################
# function to show Unit Name #
# date : 16.02.2009          #
##############################
function shw_unit($key){
$sql_unit= mysql_query("SELECT unit_name FROM unit_master WHERE unit_id ='$key'")or die(mysql_error());
$r_unit= mysql_fetch_array($sql_unit);
return stripslashes($r_unit['unit_name']);
}

##################################
# function to show Capacity Name #
# date : 04.03.2010              #
##################################
function shw_capacity($key){
$sql_capacity= mysql_query("SELECT capacity_name FROM capacity_master WHERE capacity_id ='$key'")or die(mysql_error());
$r_capacity= mysql_fetch_array($sql_capacity);
return stripslashes($r_capacity['capacity_name']);
}

##########################
# Date inserting format  #
# date : 13.02.2008      #
##########################
function InDate($dt){
list($d,$m,$Y)=explode('-',$dt);
return $Y.'-'.$m.'-'.$d;
}
function InSlashDate($dt){
if($dt==""||$dt=="0000-00-00")
return "0000-00-00";
list($m,$d,$Y)=explode('/',$dt);
return $Y.'-'.$m.'-'.$d;
}
##########################
# Date extract format    #
# date : 13.02.2008      #
##########################
function OutDate($dt){
list($Y,$m,$d)=explode('-',$dt);
return $d.'-'.$m.'-'.$Y;
}
function post_ledger($ref_id,$amount,$dr_cr,$party_id,$led_date,$nara){
	
	$sql_led="insert into ledger set ref_id = '" . trim($ref_id) . "', amount = '" . trim($amount) . "', dr_cr = '" . trim($dr_cr) . "', party_id = '" . trim($party_id) . "',  led_date = '" . trim($led_date) . "', nara = '" . trim($nara) . "'";
	//echo $sql_led;
	mysql_query($sql_led);
	}
	
	function adjust_ledger($ref_id) {
	$sql_del="delete from ledger where ref_id = '" . trim($ref_id) . "'";
	mysql_query($sql_del);
	
	}
	
	function post_Update_ledger($ref_id,$net_amount ,$dr_cr)
	{
	$sql_upd="update ledger set amount = '" . trim($net_amount) . "'  where ref_id = '" . trim($ref_id) . "' and dr_cr='".$dr_cr."'";
	mysql_query($sql_upd);
	
	}
?>
