<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	ob_start();
	session_start();

	if(!isset($_SESSION["login"])){
	header("location:./login.php");
	exit();

	}
	include("../includes/config.php");include("sessiontime.php");
	include("../includes/utils.inc.php");
$pageNumber=$_REQUEST["page"];
	
	//$sql_cust = "select party_id, party_name from party_master";
	$sql_cust = "select * from party_master order by party_name";
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
      <td width="17%" align="center" class="text07">&nbsp;</td>
      <td width="19%" align="center" class="text02"><?=$msg?>&nbsp;</td>
      <? unset($msg); ?>
      <td width="29%" align="center" class="text07">&nbsp;</td>
      <td width="35%" align="center" class="text07">&nbsp;</td>
    </tr>
	
 	<tr>
      <td width="17%" align="right" class="text07">Party Type:</td>
      <td align="left" class="text02">
		  <select name="party_type" id="party_type"  style="width:150px;" multiple="multiple" size="3" >
                        <option value="Both" <? if($_REQUEST["party_type"]=="Both" || $_REQUEST["party_type"]=="") echo "selected"; ?>>Both</option>
                        <option value="Customer" <? if($_REQUEST["party_type"]=="Customer") echo "selected"; ?>>Customer</option>
                        <option value="Vendor" <? if($_REQUEST["party_type"]=="Vendor") echo "selected"; ?>>Vendor</option>
 		  </select></td>
			
      <td width="29%" align="left" class="text07"><input name="add" type="submit" class="button01" id="add" value="Go" /></td>
      <td width="35%" align="center" class="text07">&nbsp;</td>
     </tr>
	<tr>
      <td width="17%" align="center" class="text07">&nbsp;</td>
      <td align="center" class="text02"><?=$msg?>&nbsp;</td><? unset($msg); ?>
      <td width="29%" align="center" class="text07">&nbsp;</td>
      <td width="35%" align="center" class="text07">&nbsp;</td>
     </tr>
 	 
  </table>
</form>


				<table width="100%" border="0" cellspacing="2" cellpadding="2">
                       <tr>
                        <td width="16%" height="30" align="center" bgcolor="#e6e6e6"><strong>Party Code</strong></td>
                        <td width="30%" align="center" bgcolor="#e6e6e6"><strong>Name</strong></td>
 						 <td width="37%" align="center" bgcolor="#e6e6e6"><strong>Address</strong></td>
						<td width="17%" align="center" bgcolor="#e6e6e6"><strong>Phone</strong></td>
                    </tr>
					   <?php
					  // print_r($_REQUEST['party_type']);
					   if($_REQUEST['party_type']!="" && $_REQUEST['party_type']!="Both")
					   {
					   $cond="where party_type='".$_REQUEST['party_type']."'";
					   $qstring='party_type='.$_REQUEST['party_type'];
					   }
					   $sql_usr="select count(*) from party_master  $cond order by party_name,party_type ";
						  //$qstring="party_type=".$_REQUEST["party_type"] ;
					
					  $rs_usr=mysql_query($sql_usr);
					  $row=mysql_fetch_row($rs_usr);
					  $nr=$row[0];
					  $recsOnPage=10;
					 // echo "Number of records".$nr;
					   if ($nr > 0){
							$pages = round($nr / $recsOnPage, 0);
 							if (round($nr / $recsOnPage, 2) > $pages) $pages++;
							if ($pageNumber < 1) $pageNumber = 1;
							if ($pageNumber > $pages) $pageNumber = $pages;
							$cPage=($pageNumber-1) * $recsOnPage;
							
					  $sql_usr="select *  from party_master $cond order by party_name ";
					  $sql_usr.= "  limit $cPage,$recsOnPage ";
					 // echo  $sql_usr;
					  $rs_usr=mysql_query($sql_usr);
					  if(mysql_num_rows($rs_usr)>0){
					  while($row_usr=mysql_fetch_array($rs_usr)){
					  
					  ?>
                      <tr>
                        <td width="16%" align="center"><?php echo $row_usr['party_code']; ?></td>
                        <td width="30%" align="left"><?php echo $row_usr['party_name']; ?></td>
						<td width="37%" align="left"><?php echo nl2br($row_usr['party_address']); ?></td>
						<td width="17%" align="left" nowrap="nowrap"><?php echo $row_usr['party_phone']; ?></td>
                    </tr>
					<tr><td colspan="4"><hr size="1px" width="100%" /></td></tr>
                      <?php
					   }
					   ?>
					   
					   <tr><td colspan="6">
					   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="pagination">
                    <tr>
                      <?php
							if ($pageNumber > 1)  {
								?>
                      <td width="101" height="25" align="right" class="style1"><a href="party_search_list.php?page=1&max=1&<?=$qstring?>" class="lnk">&lt;&lt;</a></td>
                      <td width="66" align="right"><a href="party_search_list.php?page=<?php print($pageNumber - 1); ?>&max=1&<?=$qstring?>" class="lnk">&lt;</a></td>
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
                      <td width="80" height="25" align="left"><a href="party_search_list.php?page=<?php print($pageNumber + 1); ?>&max=1&<?=$qstring?>" class="lnk">&gt;</a></td>
                      <td width="46" align="left"><a href="party_search_list.php?page=<?php print($pages); ?>&max=1&<?=$qstring?>" class="lnk">&gt;&gt;</a></td>
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
					   ?>
					   
					   <tr>
                        <td colspan="6" align="right"><input type="button" value="Print" onclick="JavaScript:window.open('./party_list_print.php?party_type=<?=$_REQUEST["party_type"]?>&<?php echo $qstring?>');" /> </td>
                        
                      </tr>
					   
					   <?
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
                  </table>


				   
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
