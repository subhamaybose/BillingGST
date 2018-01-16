<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
	
	function post_ledger($ref_id,$amount,$dr_cr,$party_id,$led_date,$nara){
	
	$sql_led="insert into ledger set ref_id = '" . trim($ref_id) . "', amount = '" . trim($amount) . "', dr_cr = '" . trim($dr_cr) . "', party_id = '" . trim($party_id) . "',  led_date = '" . trim($led_date) . "', nara = '" . trim($nara) . "'";
	//echo $sql_led;
	mysql_query($sql_led);
	}
	
	$sql_sale="SELECT bill_no,bill_date,net_amount,party_id from sales_order ";
	//echo $sql_sale;
	$res=mysql_query($sql_sale);
	while ($row=mysql_fetch_array($res)){
	$dr_cr='D';
	$nara="Being Sale Bill no :".$row["bill_no"];
	post_ledger($row["bill_no"],$row["net_amount"],$dr_cr,$row["party_id"],$row["bill_date"],$nara);
 	}
	
	$sql_sale_ret="SELECT sales_return_no,sales_return_date,sales_return_amount,party_id from sales_return ";
	//echo $sql_sale;
	$res=mysql_query($sql_sale_ret);
	while ($row=mysql_fetch_array($res)){
	$dr_cr='C';
	$nara="Being Credit Note No :".$row["sales_return_no"];
	post_ledger($row["sales_return_no"],$row["sales_return_amount"],$dr_cr,$row["party_id"],$row["sales_return_date"],$nara);
 	}
		
	$sql_pur="SELECT purchase_bill_no,purchase_order_date,net_amount,party_id from purchase_order ";
	//echo $sql_sale;
	$res=mysql_query($sql_pur);
	while ($row=mysql_fetch_array($res)){
	$dr_cr='C';
	$nara="Being Purchase Bill no :".$row["purchase_bill_no"];
	post_ledger($row["purchase_bill_no"],$row["net_amount"],$dr_cr,$row["party_id"],$row["purchase_order_date"],$nara);
  	}
	
	$sql_pur_ret="SELECT purchase_return_no,purchase_return_date,purchase_return_amount,party_id from purchase_return ";
	//echo $sql_sale;
	$res=mysql_query($sql_pur_ret);
	while ($row=mysql_fetch_array($res)){
	$dr_cr='D';
	$nara="Being Debit Note No :".$row["purchase_return_no"];
	post_ledger($row["purchase_return_no"],$row["purchase_return_amount"],$dr_cr,$row["party_id"],$row["purchase_return_date"],$nara);
 	}
	
	$sql_re_pt="SELECT receipt_payment_no,receipt_payment_date,receipt_payment_amount,party_id,rp_status from receipt_payment ";
	//echo $sql_re_pt;
	$res=mysql_query($sql_re_pt);
	while ($row=mysql_fetch_array($res)){
	if ($row["rp_status"]=='P'){
		$dr_cr='D';
		$nara="Being Payment # :".$row["receipt_payment_no"];
		post_ledger($row["receipt_payment_no"],$row["receipt_payment_amount"],$dr_cr,$row["party_id"],$row["receipt_payment_date"],$nara);
		}
	else { 
		$dr_cr='C';
		$nara="Being Receipt #  :".$row["receipt_payment_no"];
		post_ledger($row["receipt_payment_no"],$row["receipt_payment_amount"],$dr_cr,$row["party_id"],$row["receipt_payment_date"],$nara);
		}
 	}
		
?>

<!--date('Y-m-d', strtotime($_POST['led_date']))-->