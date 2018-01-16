<?php
ob_start();
session_start();

if(!isset($_SESSION["login"])){
header("location:./login.php");
exit();

}
 //$fin_year='';
$mode=$_REQUEST['mode'];

	$pageNumber=$_REQUEST["page"];
	$recsOnPage=25;
	if($pageNumber=="")
	$pageNumber = 1;
  		$fin_year =$_SESSION["fin_year"];
?>

<?php 
 include("../includes/config.php");include("sessiontime.php");
	include("../includes/utils.inc.php");
if($mode=="del"){
	 $sql_delete="DELETE FROM sales_order WHERE bill_no='".trim($_REQUEST['bill_no'])."'";
	//echo $sql_delete;
	mysql_query($sql_delete);
	
	$sqlPreDelete="select item_id,item_qty from sales_order_details where sales_order_id='".trim($_REQUEST['bill_no'])."'";
	$rsPre=mysql_query($sqlPreDelete);
	while($rowPre=mysql_fetch_array($rsPre))
	{
	$item_id=$rowPre["item_id"];
	$item_qty=$rowPre["item_qty"];
 	$sql_stk = 'update item_master set item_stock = item_stock + ' . $item_qty . ' where item_id = \'' . $item_id . '\'';
	//echo $sql_stk."<br>" ;
	$tok_stk = mysql_query($sql_stk);
	}			
	//and the relations
	$sql_delete="DELETE FROM sales_order_details WHERE sales_order_id='".trim($_REQUEST['bill_no'])."'";
	mysql_query($sql_delete);
	adjust_ledger(trim($_REQUEST['bill_no']));
	$_SESSION['err_msg']='Record Deleted';
	 
 
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A.S.M.I.</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function delChk(bill_no){
	if(confirm("Do you want to delete the Sale Bill ? ")){
	 window.location.href="./sales_list.php?bill_no="+bill_no+"&mode=del&fin_year=<?=$fin_year?>";
	}
}
function roundNumber(rnum, rlength) { // Arguments: number to round, number of decimal places
	  var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
	  return newnumber; // Output the result to the form field (change for your purposes)
	}

</script>
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
        <td width="200" align="left" valign="top" bgcolor="#e6e6e6"><? include("left_menu.php");?></td>
        <td align="center" valign="top"><br />
            <br />
              <br />
             <table width="99%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td height="30" colspan="5" background="images/login_bg.gif"><img src="images/login_bg.gif" width="15" height="30" align="absmiddle" /><strong>Sales Bill List</strong></td>
						<td align="right"  background="images/login_bg.gif"><input name="adbutton" type="button" class="btn" value="Add" onclick="JavaScript:window.location.href='sale_master.php?mode=add&<?=$qstring?>'" /></td>
                      </tr>
					 
                      <tr>
                        <td width="15%" height="30" align="center" bgcolor="#e6e6e6"><strong>Bill No </strong></td>
                        <td width="5%" align="center" bgcolor="#e6e6e6"><strong>Job Heading </strong></td>
 						 <td width="5%" align="center" bgcolor="#e6e6e6"><strong>Date</strong></td>
						 <td width="50%" align="center" bgcolor="#e6e6e6"><strong>Customer</strong></td>
                         <td width="25%" height="30" align="center" bgcolor="#e6e6e6"><strong>Bill Amount </strong></td>
                         <td width="10%" align="center" bgcolor="#e6e6e6"><strong>Action</strong></td>
                      </tr>
					   <?php
					  /*bill_int_id need to be change*/
					   if ($fin_year=='2017'){
						   $cond =" where fin_year='".$fin_year."' and bill_int_id > '61'";
					   }else if($fin_year > '2017'){
						   $cond =" where fin_year='".$fin_year."'";
					   }else{
						   $cond =" where 0";
					   }
					  $sql_sales="select count(*) from sales_order $cond";
					  $qstring="&fin_year=".$fin_year;

 					  $rs_sales=mysql_query($sql_sales);
					  $row=mysql_fetch_row($rs_sales);
					  $nr=$row[0];
					   if ($nr > 0){
							$pages = round($nr / $recsOnPage, 0);
 							if (round($nr / $recsOnPage, 2) > $pages) $pages++;
							if ($pageNumber < 1) $pageNumber = 1;
							if ($pageNumber > $pages) $pageNumber = $pages;
							$cPage=($pageNumber-1) * $recsOnPage;
							
					  $sql_sales="select *  from sales_order $cond order by bill_int_id";
					  $sql_sales.= "  limit $cPage,$recsOnPage ";
					  $rs_sales=mysql_query($sql_sales);
					  if(mysql_num_rows($rs_sales)>0){
					  while($row_sales=mysql_fetch_array($rs_sales)){
					  	$sql_cust = "select party_name from party_master where party_id = '" . $row_sales['party_id'] . "'";
						$tok_cust = mysql_query($sql_cust);
						$data_cust = mysql_fetch_assoc($tok_cust);
					  	?>
						  <tr>
							<td width="10%" align="center"><?php echo $row_sales['bill_no']; ?></td>
							<td width="10%" align="center"><?php echo $row_sales['job_heading']; ?></td>
							<td width="10%" align="center"><?php echo date('d-m-Y', strtotime($row_sales['bill_date'])) ?>
							</td>
							<td width="50%" align="center" nowrap="nowrap"><?php echo $data_cust['party_name']; ?></td>
							<td width="20%" height="30" align="center" nowrap="nowrap"><?php echo number_format(round($row_sales['net_amount'],0),2,'.',''); ?>
 							</td>
							<td align="center" nowrap="nowrap"> 
							   <input name="edbutton" type="button" class="btn" value="Re-Print" onclick="JavaScript:window.location.href='./sale_print.php?bill_no=<?php echo $row_sales['bill_no']; ?>'" /> &nbsp;
							 <input name="delbutton" type="button" class="btn" value="Delete" onclick="delChk('<?php echo $row_sales['bill_no']; ?>')" />
							
							  </td>
						  </tr>
                      <?php
					   }
					   ?>
					   
					   <tr><td colspan="6">
					   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="pagination">
                    <tr>
                      <?php
							if ($pageNumber > 1)  {
								?>
                      <td width="101" height="25" align="right" class="style1"><a href="sales_list.php?page=1&max=1&<?=$qstring?>" class="lnk">&lt;&lt;</a></td>
                      <td width="66" align="right"><a href="sales_list.php?page=<?php print($pageNumber - 1); ?>&max=1&<?=$qstring?>" class="lnk">&lt;</a></td>
                      <?php
							}
							else {
								?>
                      <td width="72" height="25" align="right">&lt;&lt;</td>
                      <td width="109" align="right">&lt;</td>
                      <?php
							}
							?>
                      <td width="97" align="center">Page <?php print($pageNumber . "/" . $pages); ?></td>
                      <?php
							if ($pageNumber < $pages) {
								?>
                      <td width="80" height="25" align="left"><a href="sales_list.php?page=<?php print($pageNumber + 1); ?>&max=1&<?=$qstring?>" class="lnk">&gt;</a></td>
                      <td width="46" align="left"><a href="sales_list.php?page=<?php print($pages); ?>&max=1&<?=$qstring?>" class="lnk">&gt;&gt;</a></td>
                      <?php
							}
							else {
								?>
                      <td width="41" height="25" align="left">&gt;</td>
                      <td width="74" align="left">&gt;&gt;</td>
					  </tr>
					  </table>
					  </td>
					  </tr>
					   
					   
					   <?
					   }
					   }
					  }else{
					  ?>
					   <tr>
                        <td colspan="6" align="center">No Record Present</td>
                        
                      </tr>
					  <?php
					  }
					   
					   ?>
                      <tr>
                         <td colspan="6" align="center">&nbsp;</td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
      </tr>
    </table>    </td>
  </tr>
  <!--<tr>
    <td height="37" background="images/footer.gif">&nbsp;</td>
  </tr>-->
  <?php include("footer.inc.php");?>
</table>
</body>
</html>
