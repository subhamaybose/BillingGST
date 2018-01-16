<?php
ob_start();
session_start();

if(!isset($_SESSION["login"])){
header("location:./login.php");
exit();

}
$mode=$_REQUEST['mode'];

	$pageNumber=$_REQUEST["page"];
	$recsOnPage=25;
	if($pageNumber=="")
	$pageNumber = 1;
?>

<?php 
include("../includes/config.php");include("sessiontime.php");
if($mode=="del"){
	
	
	$sql_ledger_adjust="select * from sales_return  WHERE sales_return_id = '".trim($_REQUEST['sales_return_id'])."'";
	if($rs_ledger=mysql_query($sql_ledger_adjust))
	{
		while($row_ledger=mysql_fetch_array($rs_ledger))
		{
			$sales_return_no=$row_ledger["sales_return_no"];
			$party_id=$row_ledger["party_id"];
 			$sql_led = "delete from ledger  where ref_id = '" . $sales_return_no . "' and party_id = '".$party_id."'";
			//echo $sql_led;
 			mysql_query($sql_led);
 		}
	}
	$sql_item_adjust="select * from sales_return_details  WHERE sales_return_id='".trim($_REQUEST['sales_return_id'])."'";
	if($rs_item=mysql_query($sql_item_adjust))
	{
		while($row_item=mysql_fetch_array($rs_item))
		{
			
			$item_id=$row_item["item_id"];
			$item_id=$row_item["item_id"];
			$item_unit=$row_item["item_unit"];
			$item_qty=$row_item["item_qty"];
			
			$sql_stk = "update item_master set item_stock = item_stock - " . $item_qty . " where item_id = '" . $item_id . "' ";
			//echo $sql_stk;
			mysql_query($sql_stk);
		
		}
	}
	
	$sql_delete="DELETE FROM sales_return WHERE sales_return_id = '".trim($_REQUEST['sales_return_id'])."'";
	//echo $sql_delete;
	mysql_query($sql_delete);
	//and the relations
	$sql_delete="DELETE FROM sales_return_details WHERE sales_return_id='".trim($_REQUEST['sales_return_id'])."'";
	//echo $sql_delete;
	mysql_query($sql_delete);
	
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
function delChk(sales_return_id){
	if(confirm("Do you want to delete the Sale Return Bill ? ")){
	 window.location.href="./sales_return_list.php?sales_return_id="+sales_return_id+"&mode=del";
	}
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
			 	<?php
				if(isset($_GET['msg']))
				{
				?>
			 	<tr>
					<td bgcolor="#FFFFFF" align="center" style="color:#CC0000">
						<?php echo $_GET['msg'] ?>
					</td>
				</tr>
				<?php
				}
				?>
                <tr>
                  <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td height="30" colspan="4" background="images/login_bg.gif"><img src="images/login_bg.gif" width="15" height="30" align="absmiddle" /><strong>Sales Return List</strong></td>
						<td align="right"  background="images/login_bg.gif"><input name="adbutton" type="button" class="btn" value="Add" onclick="JavaScript:window.location.href='sales_return_master.php?mode=add&<?=$qstring?>'" /></td>
                      </tr>
					  <!--<form name="frm1" method="post" action="<?=$_SERVER['PHP_SELF']?>">
					  <tr>
						  <td colspan="1">Type</td>
						  <td colspan="1"> 
						  <select name="fparty_id" style="width:150px">
						  <option value="">All</option>
						  <?php
						   $sql_usr="select *  from party_master WHERE party_type='Vendor' order by party_name ";
						  $rs_usr=mysql_query($sql_usr);
						  while($row_usr1=mysql_fetch_array($rs_usr)){
						  ?>
						  <option value="<?=$row_usr1["party_id"]?>" <? if( $_REQUEST["fparty_id"]==$row_usr1["party_id"]) echo "selected";?>><? echo $row_usr1["party_type"];?></option>
						  <? } ?>
						  </select>
						  </td>
						  <td colspan="1">
						  <input type="submit" value="Filter" name="but1" />
						  </td>
						  <td colspan="1" nowrap="nowrap">&nbsp;</td>
						  <td colspan="1">&nbsp;</td>
						  <td colspan="1">&nbsp;</td>
					  </tr>
					  </form>-->
                      <tr>
                        <td width="15%" height="30" align="center" bgcolor="#e6e6e6"><strong>Sales Return No</strong></td>
						<td width="5%" align="center" bgcolor="#e6e6e6"><strong>Date</strong></td>
						<td width="25%" height="30" align="center" bgcolor="#e6e6e6"><strong>Amount </strong></td>
						<td width="50%" align="center" bgcolor="#e6e6e6"><strong>Party</strong></td>
                        <td width="10%" align="center" bgcolor="#e6e6e6"><strong>Action</strong></td>
                      </tr>
					   <?php
					   
					  $sql_purchases="select count(*) from sales_return";

 					  $rs_purchases=mysql_query($sql_purchases);
					  $row=mysql_fetch_row($rs_purchases);
					  $nr=$row[0];
					   if ($nr > 0){
							$pages = round($nr / $recsOnPage, 0);
 							if (round($nr / $recsOnPage, 2) > $pages) $pages++;
							if ($pageNumber < 1) $pageNumber = 1;
							if ($pageNumber > $pages) $pageNumber = $pages;
							$cPage=($pageNumber-1) * $recsOnPage;
							
					  $sql_purchases="select *  from sales_return order by sales_return_date";
					  $sql_purchases.= "  limit $cPage,$recsOnPage ";
					  $rs_purchases=mysql_query($sql_purchases);
					  if(mysql_num_rows($rs_purchases)>0){
					  while($row_purchases=mysql_fetch_array($rs_purchases)){
					  	$sql_cust = "select party_name from party_master where party_id = '" . $row_purchases['party_id'] . "'";
						$tok_cust = mysql_query($sql_cust);
						$data_cust = mysql_fetch_assoc($tok_cust);
					  	?>
						  <tr>
							<td width="10%" align="center"><?php echo $row_purchases['sales_return_no']; ?></td>
							<td width="10%" align="center"><?php echo date('d-m-Y', strtotime($row_purchases['sales_return_date'])); ?></td>
							<td width="20%" height="30" align="center" nowrap="nowrap"><?php echo $row_purchases['sales_return_amount']; ?></td>
							<td width="50%" align="center" nowrap="nowrap"><?php echo $data_cust['party_name']; ?></td>
 							<td align="center" nowrap="nowrap">
							 <input name="delbutton" type="button" class="btn" value="Delete" onclick="delChk('<?php echo $row_purchases['sales_return_id']; ?>')" />
							
							  </td>
						  </tr>
                      <?php
					   }
					   ?>
					   
					   <tr><td colspan="5">
					   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="pagination">
                    <tr>
                      <?php
							if ($pageNumber > 1)  {
								?>
                      <td width="101" height="25" align="right" class="style1"><a href="sales_return_list.php?page=1&max=1&<?=$qstring?>" class="lnk">&lt;&lt;</a></td>
                      <td width="66" align="right"><a href="sales_return_list.php?page=<?php print($pageNumber - 1); ?>&max=1&<?=$qstring?>" class="lnk">&lt;</a></td>
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
                      <td width="80" height="25" align="left"><a href="sales_return_list.php?page=<?php print($pageNumber + 1); ?>&max=1&<?=$qstring?>" class="lnk">&gt;</a></td>
                      <td width="46" align="left"><a href="sales_return_list.php?page=<?php print($pages); ?>&max=1&<?=$qstring?>" class="lnk">&gt;&gt;</a></td>
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
                        <td colspan="5" align="center">No Record Present</td>
                        
                      </tr>
					  <?php
					  }
					   
					   ?>
                      <tr>
                         <td colspan="5" align="center">&nbsp;</td>
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
