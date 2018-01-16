<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
	include("../includes/utils.inc.php");

	
	//$sql_cust = "select party_id, party_name from party_master";
	$sql_cust = "select * from party_master where party_type='Both' or party_type='Customer' order by party_name";
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

<body class="body_print">
 
				 
              <table width="90%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				   
 		  <? 
		  $stday=$_REQUEST["stday"];
		  $stmonth=$_REQUEST["stmonth"];
		  $styear=$_REQUEST["styear"];
		  $enday=$_REQUEST["enday"];
		  $enmonth=$_REQUEST["enmonth"];
		  $enyear=$_REQUEST["enyear"];
		 if($stday=="")
		  $stday=date("d");
		  if($styear=="")
			  $styear=date("Y");	
		  if($stmonth=="")
		  {
			$stmonth=date("m")-1;
			  if($stmonth<=0)
			  {
				$stmonth=12;
				$styear=$styear-1;
			  }
		  }
		  if($enmonth=="")
		  $enmonth=date("m");
		  if($enday=="")
		  $enday=date("d");
		  if($enyear=="")
		  $enyear=date("Y");
 
		  $start_date=date("Y-m-d",mktime(0,0,0,$stmonth,$stday,$styear));
		  $end_date=date("Y-m-d",mktime(0,0,0,$enmonth,$enday,$enyear));
		  
		  		  $start_date_formatted=date("d/m/Y",mktime(0,0,0,$stmonth,$stday,$styear));
		  $end_date_formatted=date("d/m/Y",mktime(0,0,0,$enmonth,$enday,$enyear));

		  //$end_date=$enyear."-".$enmonth."-".$enday;
		  ?>
		 
<?
		 
			 
?>

   <table width="100%" border="0" align="center">
 		<tr>
			<td>
				<table width="100%">
					<tr>
						<td align="center">
							<span style="font-size:27px; font-weight:bold;">Associated Scientific Mfg. Industries</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table border="1px solid black" width="100%">
					<tr>
						<td width="27%">
							<table width="100%">
								<tr>
									<td>VAT NO.&nbsp;&nbsp; : 19301344017</td>
								</tr>
								<tr>
									<td>C.S.T.NO. : 19301344211</td>
								</tr>
								<tr>
									<td>PAN No.&nbsp;&nbsp;&nbsp; : AFMPB6687D</td>
								</tr>
								<tr>
									<td>E.M.NO.&nbsp;&nbsp;&nbsp; : 190171105238</td>
								</tr>
							</table>
						</td>
						<td width="73%">
							<table width="100%">
								<tr>
									<td>
									<!--	<h1 style="color:#336699">Associated Scientific Mfg. Industries</h1>	-->
										<span>Spl. In: Sintered Glass Ware &amp; Micro Filtration Assembly</span><br />
										10, Bhairab Mukherjee Lane, Kolkata - 700 004 (Opp. R.G. Kar Hospital Emergency Building)<br />
										Phone : 033-2554-8602, 2543-1010 &amp; Mobile: +91 9830606770<br />
										E-mail: info@sinteredglassware.com / associatedscientificmfgind@yahoo.co.in<br />
										Web Site : www.sinteredglassware.com
									</td>
								</tr>
							</table>
						</td>
					</tr>
                    
				</table>
			</td>
		</tr><tr>
			<td>
				<table width="100%">
					<tr>
 						<td align="center" colspan="3">
							<span style="font-size:20px; font-weight:bold;">Consolidate Sale Report from <?=$start_date_formatted?> to <?=$end_date_formatted?></span>
						</td>
 					</tr>
				</table>
			</td>
		</tr>
  	 <tr>
	<td colspan="1" width="50%" valign="top">
			<table width="100%" border="1" cellspacing="0">
			<tr bgcolor="#CC99CC">
			<td width="5%" align="left"  class="text07">&nbsp;Date</td>
			<td width="17%" align="left" class="text07">Particulars </td>
			<td width="5%" align="center" class="text07">Challan No</td>
			<td width="20%" align="center" class="text07">Invoice No</td>
            <td width="10%" align="center" class="text07">Total</td>
            <td width="5%" align="center" class="text07">CGST</td>
            <td width="5%" align="center" class="text07">SGST</td>
			<td width="5%" align="center" class="text07">IGST</td>
            <td width="5%" align="center" class="text07">To Pay</td>
            <td width="5%" align="center" class="text07">Paid</td>
            <td width="5%" align="center" class="text07">Total</td>
            <td width="13%" align="center" class="text07">Remarks</td>
             
            
			</tr>
			 
		    <?php
 			$total=array();
			$total['sgst']=0;
			$total['cgst']=0;
			$total['igst']=0;
			$total['amt']=0;
			$total['frtopay']=0;
			$total['frpaid']=0;
			$total['netamt']=0;
			
			$sel_cr="select *,DATE_FORMAT(bill_date,'%d/%m/%Y') as sod from sales_order  b,party_master a where a.party_id=b.party_id and to_days(bill_date)-to_days('$start_date')>=0 and  to_days('$end_date')-to_days(bill_date)>=0  order by bill_date";
			//echo $sel_cr;	
			$sql_cr_list=mysql_query($sel_cr) or die(mysql_error());
			if(mysql_num_rows($sql_cr_list)>0) {
			while($s_cr_list=mysql_fetch_array($sql_cr_list))
			{
			$total['netamt']=$total['netamt']+$s_cr_list['net_amount'];
			$total['cgst']=$total['cgst']+$s_cr_list['cgst'];
			$total['sgst']=$total['sgst']+$s_cr_list['sgst'];
			$total['igst']=$total['igst']+$s_cr_list['igst'];
			$total['amt']=$total['amt']+$s_cr_list['sales_order_amount'];
			$total['frtopay']=$total['frtopay']+$s_cr_list['freight_to_pay'];
			$total['frpaid']=$total['frpaid']+$s_cr_list['freight'];
 			 
			 
			?>
			<tr><td width="5%" align="left" valign="top">  <?=$s_cr_list['sod']?> </td>
				<td width="20%" align="left"  valign="top">  <?=$s_cr_list['party_name']?> </td>
 				<td width="5%" align="right" valign="top"> <?=$s_cr_list['challan_no']?></td>
				<td width="17%" align="right" valign="top"><?=$s_cr_list['bill_no']?>  </td>
                <td width="10%" align="right" valign="top"><?=$s_cr_list['sales_order_amount']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['cgst']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['sgst']?>  </td>
				<td width="5%" align="right" valign="top"><?=$s_cr_list['igst']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['freight_to_pay']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['freight']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['net_amount']?>  </td>
                <td width="13%" align="left" valign="top"><?=$s_cr_list['remarks']?></td>
 			</tr>
			<?php
 			}
			 ?>
			 <tr><td colspan="12"><hr width="100%" ></td></tr>
			 <tr ><td colspan="4"  align="right"><strong>Total</strong></td>
             <td style="border:1px solid black;" align="right"><strong><?=number_format($total['amt'],2)?></strong></td>
             <td style="border:1px solid black;" align="right"><strong><?=number_format($total['cgst'],2)?></strong></td>
             <td  style="border:1px solid black;" align="right"><strong><?=number_format($total['sgst'],2)?></strong></td>
			 <td  style="border:1px solid black;" align="right"><strong><?=number_format($total['igst'],2)?></strong></td>
             <td  style="border:1px solid black;" align="right"><strong><?=number_format($total['frtopay'],2)?></strong></td>
             <td style="border:1px solid black;" align="right"><strong><?=number_format($total['frpaid'],2)?></strong></td>
			 <td  style="border:1px solid black;" align="right"><strong><?=number_format($total['netamt'],2)?></strong></td>
			 <td>&nbsp;</td>
			 </tr>
			</table>
	</td>
	 
  </tr>
	
	
 
</table>
 <? }else{
echo "No records found.";

}
?>
 <script language="JavaScript">window.print();</script>
</body>
</html>
