<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
	include("../includes/utils.inc.php");
$pageNumber=$_REQUEST["page"];
	
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

<body>
 
				   


				<table width="100%" border="0" cellspacing="2" cellpadding="2">
                       <tr>
                        <td width="16%" height="30" align="center" bgcolor="#e6e6e6"><strong>Party Code</strong></td>
                        <td width="30%" align="center" bgcolor="#e6e6e6"><strong>Name</strong></td>
 						 <td width="37%" align="center" bgcolor="#e6e6e6"><strong>Address</strong></td>
						<td width="17%" align="center" bgcolor="#e6e6e6"><strong>Phone</strong></td>
                    </tr>
					   <?php
					 
					   if($_REQUEST['party_type']!="" && $_REQUEST['party_type']!="Both")
					   {
					   $cond="where party_type='".$_REQUEST['party_type']."'";
					   $qstring='party_type='.$_REQUEST['party_type'];
					   }
					    
					    
					  $sql_usr="select *  from party_master $cond order by party_code,party_name ";
					 // $sql_usr.= "  limit $cPage,$recsOnPage ";
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
					   }
					   ?>
					   
					  
                  </table>


				   
				  
     
   
<script type="text/javascript">
window.print();
</script>
</body>
</html>
