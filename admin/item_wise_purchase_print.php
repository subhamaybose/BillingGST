<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
	include("../includes/utils.inc.php");

	
	//$sql_cust = "select party_id, party_name from party_master";
	$sql_cust = "select item_id,item_name,category_name from item_master a,category b where a.category_id =b.category_id  order by item_name";
	//echo $sql_cust;
	$tok_cust = mysql_query($sql_cust);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>A.S.M.I.</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="../js/CalendarPopup.js"></script>
	<script type="text/javascript" src="../js/AnchorPosition.js"></script>
	<script type="text/javascript" src="../js/date.js"></script>
	<script type="text/javascript" src="../js/PopupWindow.js"></script>
<script language="javascript" src="calender/dhtmlgoodies_calendar.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="calender/dhtmlgoodies_calendar.css" />
	   <script type="text/javascript" src="../js/jquery.min.1.4.4.js"></script>
    <script type="text/javascript" src="../js/jquery.autocomplete.js"></script>
    <link rel="stylesheet" href="./css/jquery.autocomplete.css" type="text/css" />
</head>

<body>
 
<?
$item_id=$_REQUEST["item_id"];
$item_name=$_REQUEST["item_name"];
$item_code=$_REQUEST["item_code"];
if($item_id!='' || $item_name!='' || $item_code!=''){
		if($_REQUEST["item_id"]!="") {
			$cond.=" and a.item_id='".$item_id."'";
			$_REQUEST["item_name"]='';
			$_REQUEST["item_code"]='';
		}
		if($_REQUEST["item_name"]!="") {
			$cond.=" and (a.item_name='".$item_name."' or a.item_name like '%".$item_name."%')";
			$_REQUEST["item_id"]='';
			$_REQUEST["item_code"]='';
		}
		if($_REQUEST["item_code"]!="") {
			$cond.=" and a.item_code='".$item_code."'";
			$_REQUEST["item_name"]='';
			$_REQUEST["item_id"]='';
		}
			$sqlItem="select item_name from item_master a where 1 $cond";
			$rsItem=mysql_query($sqlItem);
			if($rowItem=mysql_fetch_array($rsItem))
			{
				$item_name=$rowItem["item_name"];
			}		 
?>
 			<table width="100%" border="0">
             <tr>
 						<td align="center" colspan="4">
							<span style="font-size:20px; font-weight:bold;">Item wise Purchase Report  of <?=$item_name?>  from <?=$start_date?> to <?=$end_date?></span>						</td>
			  </tr>
			<tr >
			<td width="20%" align="left" class="text07">&nbsp;<strong>Date</strong></td>
			<td width="25%" align="left" class="text07"><strong>Bill No </strong></td>
            <td width="20%" align="center" class="text07"><strong>Doc No</strong></td>
			<td width="15%" align="right" class="text07"> <strong>Bill Amount</strong> </td>
			<td width="15%" align="right" class="text07"> <strong>Item Amount</strong> </td>
			</tr>
			 
		    <?php
						 $stday=$_GET["stday"];
		  $stmonth=$_GET["stmonth"];
		  $styear=$_GET["styear"];
		  $enday=$_GET["enday"];
		  $enmonth=$_GET["enmonth"];
		  $enyear=$_GET["enyear"];

		 

		  $start_date=date("Y-m-d",mktime(0,0,0,$stmonth,$stday,$styear));
		  $end_date=date("Y-m-d",mktime(0,0,0,$enmonth,$enday,$enyear));
 			$total_item_amt=0;
			$total_item_purchase_amt=0;
			$sel_cr="select *,DATE_FORMAT(purchase_order_date,'%d/%m/%Y') as sod from purchase_order  b ,purchase_order_details c,item_master a 
			where a.item_id=c.item_id and b.purchase_order_id=c.purchase_order_id and to_days(purchase_order_date)-to_days('$start_date')>=0 and  to_days('$end_date')-to_days(purchase_order_date)>=0 $cond order by purchase_order_date";
			$sql_cr_list=mysql_query($sel_cr) or die(mysql_error());
			while($s_cr_list=mysql_fetch_array($sql_cr_list))
			{
 			$total_item_purchase_amt+=$s_cr_list['net_amount'];
			$total_item_amt+=$s_cr_list['item_amount'];
 			?>
			<tr>
                <td width="25%" align="left">  <?=$s_cr_list['sod']?> </td>
                <td width="20%" align="left"> <?=$s_cr_list['purchase_bill_no']?>  </td>
                <td width="20%" align="center"> <?=$s_cr_list['doc_no']?></td>
                 <td width="15%" align="right"> <?=$s_cr_list['net_amount']?></td>
				  <td width="15%" align="right"> <?=$s_cr_list['item_amount']?></td>
			</tr>
			<?php
 			}
			 ?>
			 <tr><td colspan="5"><hr width="100%" ></td></tr>
			 <tr><td colspan="3" align="right"><strong>Total</strong></td>
			 <td colspan="1" align="right"><strong><?=number_format($total_item_purchase_amt,2)?></strong></td>
			 <td colspan="1" align="right"><strong><?=number_format($total_item_amt,2)?></strong></td>
			 </tr>
			</table>
	</td>
	 
  </tr>
	
	
 
</table>
 <? }else{
echo "Please select Item";

}
?>
<script language="JavaScript">window.print();</script>

</body>
</html>
