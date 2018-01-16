<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
	include("../includes/utils.inc.php");
 	
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
                        <td width="5%" height="30" align="center" bgcolor="#e6e6e6"><strong>Item Code</strong></td>
                        <td width="35%" align="left" bgcolor="#e6e6e6"><strong>Name</strong></td>
						<td width="20%" align="left" bgcolor="#e6e6e6"><strong>Type</strong></td>
						<td width="10%" align="center" bgcolor="#e6e6e6"><strong>Capacity</strong></td>
 						<td width="10%" align="center" bgcolor="#e6e6e6"><strong>Grade</strong></td>
						<td width="10%" align="center" bgcolor="#e6e6e6"><strong>Quantity</strong></td>
						<td width="10%" align="center" bgcolor="#e6e6e6"><strong>Unit</strong></td>
                         
                      </tr>
					   <?php
					 
					   $cond="";
					   if($_REQUEST['item_type']!="")
					   {
					   $cond="where item_type='".$_REQUEST['item_type']."'";
					   $qstring.="&item_type=".$_REQUEST['item_type'];
					   }
					   if($_REQUEST['item_name']!="")
					   {
					   if($cond!="")
					     $cond.=" and (item_name='".$_REQUEST['item_name']."' or item_name like '%".$_REQUEST['item_name']."%' or item_name like '".$_REQUEST['item_name']."%' or item_name like '%".$_REQUEST['item_name']."')";
					   else
					  $cond.=" where (item_name='".$_REQUEST['item_name']."' or item_name like '%".$_REQUEST['item_name']."%' or item_name like '".$_REQUEST['item_name']."%' or item_name like '%".$_REQUEST['item_name']."')";
					   $qstring.="&item_name=".$_REQUEST['item_name'];
					   }
					   if($_REQUEST['item_code']!="")
					   {
					   if($cond!="")
					   $cond.=" and item_code='".$_REQUEST['item_code']."'";
					   else
					   $cond="where item_code='".$_REQUEST['item_code']."'";
					   
					   $qstring.="&item_code=".$_REQUEST['item_code'];
					   }
					   if($_REQUEST['capacity_id']!="")
					   {
					   if($cond!="")
					    $cond.=" and capacity_id='".$_REQUEST['capacity_id']."'";
					   else
					   $cond="where capacity_id='".$_REQUEST['capacity_id']."'";
					   $qstring.="&capacity_id=".$_REQUEST['capacity_id'];
					   }
					    
					    
					  $sql_usr="select *  from item_master $cond order by item_code,item_name ";
					 
					  $rs_usr=mysql_query($sql_usr);
					  if(mysql_num_rows($rs_usr)>0){
					  while($row_usr=mysql_fetch_array($rs_usr)){
					  
					  ?>
                     <tr>
                        <td width="5%" align="center"><?php echo $row_usr['item_code']; ?></td>
                        <td width="35%" align="left"><?php echo $row_usr['item_name']; ?></td>
						<td width="20%" align="left"><?php echo $row_usr['item_type']; ?></td>
						<td width="10%" align="center" nowrap="nowrap"><?php echo shw_capacity($row_usr['capacity_id'])?></td>
						<td width="10%" align="center" nowrap="nowrap"><?php echo shw_category($row_usr['category_id'])?></td>
						<td width="10%" align="center"><?php echo $row_usr['item_stock']; ?></td>
						<td width="10%" align="center" nowrap="nowrap"><?php echo shw_unit($row_usr['unit_id'])?></td>
                    </tr>
					 
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
