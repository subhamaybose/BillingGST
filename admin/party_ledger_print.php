<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	ob_start();
session_start();
$fin_year =$_SESSION["fin_year"];

	include("../includes/config.php");include("sessiontime.php");
	include("../includes/utils.inc.php");

	
	//$sql_cust = "select party_id, party_name from party_master";
	$sql_cust = "select * from party_master";
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

<body >
 		   <? 
		  $stday=$_REQUEST["stday"];
		  $stmonth=$_REQUEST["stmonth"];
		  $styear=$_REQUEST["styear"];
		  $enday=$_REQUEST["enday"];
		  $enmonth=$_REQUEST["enmonth"];
		  $enyear=$_REQUEST["enyear"];

		  if($stmonth=="")
		  $stmonth=date("m");
 		  if($stday=="")
		  $stday=date("d");
		  if($styear=="")
		  $styear=date("Y");
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
		  
		   $qstring.="stday=".$stday."&stmonth=".$stmonth."&styear=".$styear."&enday=".$enday."&enmonth=".$enmonth."&enyear=".$enyear."&party_id=".$_POST["party_id"];
		  //$end_date=$enyear."-".$enmonth."-".$enday;
		  ?>
		   
<?
		if($_REQUEST["party_id"]!="") {
			$cond=" and b.party_id='".$_REQUEST["party_id"]."'";
			$sqlParty="select party_name from party_master where party_id='".$_REQUEST["party_id"]."'";
			$rsParty=mysql_query($sqlParty);
			if($rowParty=mysql_fetch_array($rsParty))
			{
				$party_name=$rowParty["party_name"];
			}
			 
?>

   <table width="100%" border="1" align="center">
  <tr><td colspan="6">
  <table border="0" width="100%">
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
		</tr>
  </table>
  </td>
  </tr>
    <tr><td class="header" colspan="6">Statement of Accounts of <?=$party_name?>  from <?=$start_date_formatted?> to <?=$end_date_formatted?> (dd/mm/yyyy) .</td>
  </tr>

  <?
		if($_POST["party_id"]!="") {
			$cond=" and b.party_id='".$_POST["party_id"]."'";
			}
			$total_cr=0;
			$total_dr=0;
			$prev_cr=0;
			$finPrevStartDate=($fin_year-1)."-04-01";
			$finPrevEndDate=$fin_year."-03-31";
			$sel_cr="select sum(amount) from ledger b where dr_cr='C' and to_days('$finPrevEndDate')-to_days(led_date)>0 and to_days(led_date)-to_days('2006-04-01') >=0  $cond";
			$sql_cr_list=mysql_query($sel_cr) or die(mysql_error());
			if($s_cr_list=mysql_fetch_array($sql_cr_list))
			{
			$prev_cr=$s_cr_list[0];
			
			}
			//$total_cr+=$prev_cr;
			
			$prev_dr=0;
			
			$sel_dr="select sum(amount) from ledger b where dr_cr='D' and to_days('$finPrevEndDate')-to_days(led_date)>=0 and to_days(led_date)-to_days('2006-04-01') >=0  $cond";
			$sql_dr_list=mysql_query($sel_dr) or die(mysql_error());
			if($s_dr_list=mysql_fetch_array($sql_dr_list))
			{
			$prev_dr=$s_dr_list[0];
			
			}
			//echo "Previous dr.".$prev_dr;
			//echo "<br>Previous cr.".$prev_cr;
			 
			 
?>

   <table width="100%" border="1" align="center">
  <tr><td colspan="3" class="text07" align="left">&nbsp;<strong>Debit</strong> </td>
  <td colspan="3" align="left" class="text07"><strong> Credit  </strong></td>
  </tr>

 <tr>
	
	<td colspan="3" valign="top">
		<table width="100%" border="0">
		<tr bgcolor="#CC99CC">
			<td width="21%" align="left" class="text07">&nbsp;Date</td>
			<td width="59%" align="left" class="text07">Description</td>
			<td width="20%" align="center" class="text07">Amount</td>
			</tr>
		 
		<?php
		
			if($prev_dr>$prev_cr)
			$total_dr+=($prev_dr-$prev_cr);
			else
			$total_cr+=($prev_cr-$prev_dr);
			if($prev_dr-$prev_cr>=0){
			?>
			<tr>
				<td width="21%" align="left">&nbsp; </td>
				<td width="59%" align="left"> Opening Balance</td>
				<td width="20%" align="right"><?=number_format(($prev_dr-$prev_cr),2,'.',',')?>&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<?php
 			}
		$sql_stmt="SELECT *  from ledger b WHERE to_days(led_date)-to_days('$start_date')>=0 and to_days('$end_date')-to_days(led_date)>=0 and b.dr_cr='D' $cond order by led_date,id";
		 //echo $sql_stmt;
 		$sql_inst_list=mysql_query($sql_stmt) or die(mysql_error());
		//echo $sql_stmt;
		 //echo mysql_num_rows($sql_inst_list);
		if(mysql_num_rows($sql_inst_list)>0){
		while($s_inst_list=mysql_fetch_array($sql_inst_list)){
		?>
		  <tr>
			<td width="26%" align="left"><?=OutDate($s_inst_list['led_date'])?>&nbsp;</td>
			<td width="51%" align="left"><?=$s_inst_list['nara']?>&nbsp;</td>
			<td width="23%" align="right"><?=number_format($s_inst_list['amount'],2,'.',',')?>&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<? 
		$total_dr=$total_dr+$s_inst_list['amount'];
		}
		} else { ?>
		 <tr>
			 <td align="center"  colspan="3" class="text02">No Records</td>
		   </tr>
		<? } ?>
 		</table>
</td>
<td colspan="3" width="50%" valign="top">
			<table width="100%" border="0">
			<tr bgcolor="#CC99CC">
			<td width="21%" align="left" class="text07">&nbsp;Date</td>
			<td width="59%" align="left" class="text07">Description</td>
			<td width="20%" align="center" class="text07">Amount</td>
			</tr>
			 
		    <?php
			 
			
			
			$sel_cr="select sum(amount) from ledger b where dr_cr='C' and to_days('$start_date')-to_days(led_date)>0  and to_days(led_date)-to_days('2006-04-01') >=0 $cond";
			$sql_cr_list=mysql_query($sel_cr) or die(mysql_error());
			if($s_cr_list=mysql_fetch_array($sql_cr_list))
			{
			$prev_cr=$s_cr_list[0];
			
			}
			//$total_cr+=$prev_cr;
			
			//$prev_dr=0;
			
			$sel_dr="select sum(amount) from ledger b where dr_cr='D' and to_days('$start_date')-to_days(led_date)>0  and to_days(led_date)-to_days('2006-04-01') >=0 $cond";
			$sql_dr_list=mysql_query($sel_dr) or die(mysql_error());
			if($s_dr_list=mysql_fetch_array($sql_dr_list))
			{
			$prev_dr=$s_dr_list[0];
			
			}
			if($prev_cr-$prev_dr>0){
			?>
			<tr>
				<td width="21%" align="left">&nbsp;</td>
				<td width="59%" align="left"> Opening Balance</td>
				<td width="20%" align="right"><?=number_format(($prev_cr-$prev_dr),2,'.',',')?>&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<?php
 			}
			$sql_stmt="SELECT *  from ledger b WHERE to_days(led_date)-to_days('$start_date')>=0 and to_days('$end_date')-to_days(led_date)>=0 and b.dr_cr='C' $cond order by led_date,id";
			// echo $sql_stmt;
			$sql_inst_list=mysql_query($sql_stmt) or die(mysql_error());
			 //echo mysql_num_rows($sql_inst_list);
			if(mysql_num_rows($sql_inst_list)>0){
			while($s_inst_list=mysql_fetch_array($sql_inst_list)){
			?>
			  <tr>
				<td width="21%" align="left"><?=OutDate($s_inst_list['led_date'])?>&nbsp;</td>
				<td width="59%" align="left"><?=$s_inst_list['nara']?>&nbsp;</td>
				<td width="20%" align="right"><?=number_format($s_inst_list['amount'],2,'.',',')?>&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<? 
			$total_cr=$total_cr+$s_inst_list['amount'];
			}
			} else { ?>
			<tr>
				<td align="center" colspan="3" class="text02">No Records</td>
		    </tr>
			<? } ?>
			 
			</table>
	</td>
  </tr>
	
	<tr>
	<td align="right" colspan="3" class="text07"><strong>Total</strong> 
	 <?=number_format($total_dr,2,'.',',')?> &nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td align="right" colspan="3" class="text07"><strong>Total</strong> 
	  <?=number_format($total_cr,2,'.',',')?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
	<? if(($total_dr-$total_cr)>0){ ?>
	<td colspan="3" align="right"><strong>Closing Balance</strong> <strong>=</strong>	  <?=number_format(($total_dr-$total_cr),2,'.',',')?></td>
	<td colspan="3" align="right"> &nbsp;&nbsp;&nbsp;&nbsp;</td>
	<? }else{ ?>
	<td colspan="3" align="right"> &nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td colspan="3" align="right"><strong>Closing Balance =</strong>	  <?=number_format(($total_cr-$total_dr),2,'.',',')?></td>
 	<? }?>
	
	</tr>
    
 
</table>
 <? } 
?>
  <script language="JavaScript">window.print();</script>

</body>
</html>
