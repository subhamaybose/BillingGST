<?php
ob_start();
session_start();
$fin_year =$_SESSION["fin_year"];

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

<body>
<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="111" align="center" valign="bottom" background="images/header.gif"><table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="26%"><img src="images/logo.jpg" width="100" height="107" /></td>
        <td width="70%" align="center" class="header">Associated Scientific Manufacturing Industries (<?php echo $_SESSION['fin_year']."-".($_SESSION['fin_year']+1)?>)</td>
        <td width="4%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="420" align="center" bgcolor="#FFFFFF"><table width="100%" height="420" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="200" align="center" valign="top" bgcolor="#e6e6e6"><br />
          <?php include("left_menu.php");?></td>
        <td align="center" valign="top"><br />
            <br />
              <br />
				 
              <table width="90%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				   
				   <form action="" method="post" name="frmAdd" id="frmAdd" >
  <table width="80%" border="0" align="center">
    <tr>
      <td width="15%" align="center" class="text07">&nbsp;</td>
      <td align="center" class="text02"><?=$msg?>&nbsp;</td><? unset($msg); ?>
      <td width="13%" align="center" class="text07">&nbsp;</td>
      <td width="33%" align="center" class="text07">&nbsp;</td>
	  <td width="7%" align="center" class="text07">&nbsp;</td>
    </tr>
	
	 
		
 
	<tr>
		  <td width="15%" align="right">Start Date : </td>
		  <td width="32%">
		  <? 
		  $qstring="";
		  $stday=$_REQUEST["stday"];
		  $stmonth=$_REQUEST["stmonth"];
		  $styear=$_REQUEST["styear"];
		  $enday=$_REQUEST["enday"];
		  $enmonth=$_REQUEST["enmonth"];
		  $enyear=$_REQUEST["enyear"];
		 if($stmonth=="") 
		 	 $stmonth=4;
  		  if($stday=="")
		  $stday=1;
		  if($styear=="")
		  $styear=$fin_year;
		  if($enmonth=="")
		  $enmonth=3;
		  if($enday=="")
		  $enday=31;
		  if($enyear=="")
		  $enyear=$fin_year+1;
		   $currenYear=date("Y");

		  $start_date=date("Y-m-d",mktime(0,0,0,$stmonth,$stday,$styear));
		  $end_date=date("Y-m-d",mktime(0,0,0,$enmonth,$enday,$enyear));
		  $qstring.="stday=".$stday."&stmonth=".$stmonth."&styear=".$styear."&enday=".$enday."&enmonth=".$enmonth."&enyear=".$enyear;
		  //$end_date=$enyear."-".$enmonth."-".$enday;
		  ?>
		  <select name="stday" id="stday">
		  	<? for($i=1;$i<=31;$i++){ ?>
		  	<option value="<?=$i?>"<? if($stday==$i) echo "selected"?>><?=str_pad($i,2,"0",STR_PAD_LEFT)?></option>
			<? } ?>
		  </select>
		  <select name="stmonth" id="stmonth">
			<? for($i=1;$i<=12;$i++){ ?>
			<option value="<?=$i?>"<? if($stmonth==$i) echo "selected"?>><?=str_pad($i,2,"0",STR_PAD_LEFT)?></option>
			<? } ?>
		  </select>
		  <select name="styear" id="styear">
			<? for($i=2010;$i<=$currenYear;$i++){ ?>
			<option value="<?=$i?>"<? if($styear==$i) echo "selected"?>><?=str_pad($i,2,"0",STR_PAD_LEFT)?></option>
			<? } ?>
		  </select>(dd/mm/yyyy)</td>

		 <td width="13%" align="right">End Date : </td>
		 <td width="33%">
		 <select name="enday" id="enday">
			<? for($i=1;$i<=31;$i++){ ?>
			<option value="<?=$i?>"<? if($enday==$i) echo "selected"?>><?=str_pad($i,2,"0",STR_PAD_LEFT)?></option>
			<? } ?>
		 </select>
		 <select name="enmonth" id="enmonth">
			<? for($i=1;$i<=12;$i++){ ?>
			<option value="<?=$i?>"<? if($enmonth==$i) echo "selected"?>><?=str_pad($i,2,"0",STR_PAD_LEFT)?></option>
			<? } ?>
		 </select>
		 <select name="enyear" id="enyear">
			<? for($i=2010;$i<=($enyear);$i++){ ?>
			<option value="<?=$i?>"<? if($enyear==$i) echo "selected"?>><?=str_pad($i,2,"0",STR_PAD_LEFT)?></option>
			<? } ?>
		 </select>(dd/mm/yyyy)
		 </td>
		 <td width="7%"><input name="add" type="submit" class="button01" id="add" value="Go" /></td>
   </tr>
  </table>
</form>
				   
<br />
<?
		 
			 
?>

<form id="frmpartyList" name="frmpartyList" method="post" action="">
  <table width="100%" border="1" align="center">
  <tr>
	<td  width="50%" valign="top">
			<table width="100%" border="0" cellspacing="2">
			<tr bgcolor="#CC99CC"> 
			<td width="5%" align="left" class="text07">&nbsp;Date</td>
			<td width="20%" align="left" class="text07">Particulars </td>
			<td width="5%" align="center" class="text07">Challan No</td>
			<td width="20%" align="center" class="text07">Invoice No</td>
            <td width="10%" align="center" class="text07">Total</td>
            <td width="5%" align="center" class="text07">CST</td>
            <td width="5%" align="center" class="text07">VAT</td>
            <td width="5%" align="center" class="text07">To Pay</td>
            <td width="5%" align="center" class="text07">Paid</td>
            <td width="5%" align="center" class="text07">Total</td>
            <td width="15%" align="center" class="text07">Remarks</td>
             
            
			</tr>
			 
		    <?php
 			$total=array();
			$total['vat']=0;
			$total['cst']=0;
			$total['amt']=0;
			$total['frtopay']=0;
			$total['frpaid']=0;
			$total['netamt']=0;
			 
			$sel_cr="select *,DATE_FORMAT(bill_date,'%d/%m/%Y') as sod from sales_order  b,party_master a where a.party_id=b.party_id and to_days(bill_date)-to_days('$start_date')>=0 and  to_days('$end_date')-to_days(bill_date)>=0  order by bill_date,bill_no";
			//echo $sel_cr;	
			$sql_cr_list=mysql_query($sel_cr) or die(mysql_error());
			if(mysql_num_rows($sql_cr_list)>0) {
			while($s_cr_list=mysql_fetch_array($sql_cr_list))
			{
			$total['netamt']=$total['netamt']+$s_cr_list['net_amount'];
			$total['vat']=$total['vat']+$s_cr_list['vat'];
			$total['cst']=$total['cst']+$s_cr_list['cst'];
			$total['amt']=$total['amt']+$s_cr_list['sales_order_amount'];
			$total['frtopay']=$total['frtopay']+$s_cr_list['freight_to_pay'];
			$total['frpaid']=$total['frpaid']+$s_cr_list['freight'];
 			 
			 
			?>
			<tr><td width="5%" align="left" valign="top">  <?=$s_cr_list['sod']?> </td>
				<td width="20%" align="left" valign="top">  <?=$s_cr_list['party_name']?> </td>
 				<td width="5%" align="right" valign="top"> <?=$s_cr_list['challan_no']?></td>
				<td width="20%" align="right" valign="top"><?=$s_cr_list['bill_no']?>  </td>
                <td width="10%" align="right" valign="top"><?=$s_cr_list['sales_order_amount']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['cst']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['vat']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['freight_to_pay']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['freight']?>  </td>
                <td width="5%" align="right" valign="top"><?=$s_cr_list['net_amount']?>  </td>
                <td width="15%" align="left" valign="top"><?=$s_cr_list['remarks']?>  </td>
 			</tr>
			<?php
 			}
			 ?>
			 <tr><td colspan="11"><hr width="100%" ></td></tr>
			 <tr ><td colspan="4"  align="right"><strong>Total</strong></td>
             <td style="border:1px solid black;" align="right"><strong><?=number_format($total['amt'],2)?></strong></td>
             <td style="border:1px solid black;" align="right"><strong><?=number_format($total['cst'],2)?></strong></td>
             <td  style="border:1px solid black;" align="right"><strong><?=number_format($total['vat'],2)?></strong></td>
             <td  style="border:1px solid black;" align="right"><strong><?=number_format($total['frtopay'],2)?></strong></td>
             <td style="border:1px solid black;" align="right"><strong><?=number_format($total['frpaid'],2)?></strong></td>
			 <td  style="border:1px solid black;" align="right"><strong><?=number_format($total['netamt'],2)?></strong></td>
			 <td>&nbsp;</td>
			 </tr>
			</table>
	</td>
	 
  </tr>
  <tr>	
  
                        <td colspan="11" align="right"><input type="button" value="Print" onclick="JavaScript:window.open('./consolidated_sale_print.php?<?php echo $qstring?>');" />  </td>	
</tr>
 
</table>
</form>
<? }else{
echo "No records found.";

}
?>
<br />

	  <DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
				  </td>
                </tr>
              </table></td>
      </tr>
    </table>    
	</td>
  </tr>
  <!--<tr>
    <td height="37" background="images/footer.gif">&nbsp;</td>
  </tr>-->
  <?php include("footer.inc.php");?>
</table>
</body>
</html>
