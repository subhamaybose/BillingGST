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
		  <td align="right">Party Name:</td>
		  <td height="20" colspan="4">
			<select name="party_id" id="party_id" onchange="getBillDetails(0)">
				<option value="">---Select Party---</option>
				<?php
				while($res_cust = mysql_fetch_assoc($tok_cust))
				{
					?>
					<option value="<?php echo $res_cust['party_id'] ?>" <?php if($_REQUEST["party_id"]==$res_cust['party_id']) echo "selected";?>><?php echo $res_cust['party_name'] ?></option>
					<?php
				}
				?>
			</select>
		  </td>
 		</tr>
		
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
		  $stday=$_POST["stday"];
		  $stmonth=$_POST["stmonth"];
		  $styear=$_POST["styear"];
		  $enday=$_POST["enday"];
		  $enmonth=$_POST["enmonth"];
		  $enyear=$_POST["enyear"];

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
		   $qstring.="stday=".$stday."&stmonth=".$stmonth."&styear=".$styear."&enday=".$enday."&enmonth=".$enmonth."&enyear=".$enyear."&party_id=".$_POST["party_id"];
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
			<? for($i=date("Y")-2;$i<=($enyear);$i++){ ?>
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
		if($_POST["party_id"]!="") {
			$cond=" and b.party_id='".$_POST["party_id"]."'";
			$qstring.='&party_id='.$_REQUEST['party_id'];
			 
?>

<form id="frmpartyList" name="frmpartyList" method="post" action="">
  <table width="100%" border="1" align="center">
  
	<td colspan="1" width="50%" valign="top">
			<table width="100%" border="0">
			<tr bgcolor="#CC99CC">
			<td width="20%" align="left" class="text07">&nbsp;Date</td>
			<td width="25%" align="left" class="text07">Bill No </td>
			<td width="15%" align="center" class="text07">Amount</td>
			<td width="20%" align="center" class="text07"><!--Mode--></td>
			</tr>
			 
		    <?php
 			$total_item_Sale=0;
			$sel_cr="select *,DATE_FORMAT(bill_date,'%d/%m/%Y') as sod from sales_order  b where to_days(bill_date)-to_days('$start_date')>=0 and  to_days('$end_date')-to_days(bill_date)>=0 $cond order by bill_date";
			$sql_cr_list=mysql_query($sel_cr) or die(mysql_error());
			while($s_cr_list=mysql_fetch_array($sql_cr_list))
			{
			 
			$total_item_Sale+=$s_cr_list['net_amount'];
			 
			 
			?>
			<tr><td width="25%" align="left">  <?=$s_cr_list['sod']?> </td>
				<td width="20%" align="left"><a href="./sale_print.php?bill_no=<?=$s_cr_list['bill_no']?>" target="_blank"><?=$s_cr_list['bill_no']?></a> </td>
 				<td width="15%" align="right"> <?=$s_cr_list['net_amount']?></td>
				<td width="20%" align="right"><? // =$s_cr_list['sales_order_id']?>  </td>
			</tr>
			<?php
 			}
			 ?>
			 <tr><td colspan="4"><hr width="100%" ></td></tr>
			 <tr><td colspan="2" align="right"><strong>Total</strong></td>
			 <td colspan="1" align="right"><strong><?=number_format($total_item_Sale,2)?></strong></td>
			 <td>&nbsp;</td>
			 </tr>
             <tr>
                        <td colspan="4" align="right"><input type="button" value="Print" onclick="JavaScript:window.open('./party_wise_sale_print.php?party_type=1&<?php echo $qstring?>');" /> </td>
                        
                      </tr>
			</table>
	</td>
	 
  </tr>
	
	
 
</table>
</form>
<? }else{
echo "Please select Party from dropdown";

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
