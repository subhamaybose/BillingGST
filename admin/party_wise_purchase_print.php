<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
	include("../includes/utils.inc.php");

	
	//$sql_cust = "select party_id, party_name from party_master";
	$sql_cust = "select * from party_master where party_type='Both' or party_type='Vendor' order by party_name";
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
</head>

<body>
   
  
	 

	 
<?

 $stday=$_GET["stday"];
		  $stmonth=$_GET["stmonth"];
		  $styear=$_GET["styear"];
		  $enday=$_GET["enday"];
		  $enmonth=$_GET["enmonth"];
		  $enyear=$_GET["enyear"];
$start_date=date("Y-m-d",mktime(0,0,0,$stmonth,$stday,$styear));
		  $end_date=date("Y-m-d",mktime(0,0,0,$enmonth,$enday,$enyear));
		if($_REQUEST["party_id"]!="") {
			$cond=" and b.party_id='".$_REQUEST["party_id"]."'";
 			$sqlParty="select party_name from party_master where party_id='".$_REQUEST["party_id"]."'";
			$rsParty=mysql_query($sqlParty);
			if($rowParty=mysql_fetch_array($rsParty))
			{
				$party_name=$rowParty["party_name"];
			}
			
			 
			 
?>

 <table width="100%" border="0">
  <tr>
 						<td align="center" colspan="4">
							<span style="font-size:20px; font-weight:bold;">Party wise Purchase Report  of <?=$party_name?>  from <?=$start_date?> to <?=$end_date?></span>
						</td>
   </tr>
			<tr >
			<td width="20%" align="left" class="text07">&nbsp;<strong>Date</strong></td>
			<td width="25%" align="left" class="text07"><strong>Bill No </strong></td>
            <td width="20%" align="center" class="text07"><strong>Doc No</strong></td>
			<td width="15%" align="right" class="text07"><strong>Amount</strong></td>
			</tr>
			 
		    <?php
 			$total_item_Sale=0;
			$sel_cr="select *,DATE_FORMAT(purchase_order_date,'%d/%m/%Y') as sod from purchase_order  b where to_days(purchase_order_date)-to_days('$start_date')>=0 and  to_days('$end_date')-to_days(purchase_order_date)>=0 $cond order by purchase_order_date";
			$sql_cr_list=mysql_query($sel_cr) or die(mysql_error());
			while($s_cr_list=mysql_fetch_array($sql_cr_list))
			{
			 
			$total_item_Sale+=$s_cr_list['net_amount'];
			 
			 
			?>
			<tr>
                <td width="25%" align="left">  <?=$s_cr_list['sod']?> </td>
                <td width="20%" align="left"> <?=$s_cr_list['purchase_bill_no']?>  </td>
                <td width="20%" align="center"> <?=$s_cr_list['doc_no']?></td>
                <td width="15%" align="right"> <?=$s_cr_list['net_amount']?></td>
			</tr>
			<?php
 			}
			 ?>
			 <tr>
			   <td colspan="4"><hr width="100%" /></td>
			 </tr>
			 <tr><td colspan="3" align="right"><strong>Total</strong></td>
			 <td colspan="3" align="right"><strong><?=number_format($total_item_Sale,2)?></strong></td>
			 <!--<td>&nbsp;</td>-->
			 </tr>
              
</table>
	</td>
	 
  </tr>
	
	
 
</table>
 
<? }else{
echo "Please select Party from dropdown";

}
?>
 
 
</body>
</html>
