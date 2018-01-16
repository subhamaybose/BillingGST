<?php
ob_start();
session_start();

if(!isset($_SESSION["login"])){
	header("location:./login.php");
	exit();
}
?>

<?php 
include("../includes/config.php");include("sessiontime.php");

$mode=$_REQUEST['mode'];
$wastage_id=$_REQUEST['wastage_id'];
function chkDuplicateUser($wastage_no,$wastage_id=""){

	if($wastage_no==""){
	return false;
	
	}
	if($wastage_id!="")
	$sql_user="select * from wastage_master where wastage_no='$wastage_no' and wastage_id!='$wastage_id'";
	else
	$sql_user="select * from wastage_master where wastage_no='$wastage_no' ";
	$rs_usr=mysql_query($sql_user);
	if(mysql_num_rows($rs_usr)>0) 
	return false;
	else
	return true;

}
if(isset($_POST['bsave'])){
	if( $wastage_id==""){	
		if(chkDuplicateUser(strtoupper($_POST['wastage_no']))){
			
			$sql_insert="INSERT INTO wastage_master (wastage_no,wastage_date,item_type,item_id,wastage_qty)";
			
			$sql_insert.="VALUES('".trim(strtoupper($_POST['wastage_no']))."', '" . date('Y-m-d', strtotime($_POST['wastage_date'])) . "',
			'".trim($_POST['item_type'])."',";
			$sql_insert.="'".trim($_POST['item_id'])."','".trim($_POST['wastage_qty'])."')";
			
			 //echo $sql_insert;
			 
				if(mysql_query($sql_insert))	{
					$_SESSION['err_msg']='Message : Record Added';
					$mode="add";
					header("location:./wastage_list.php?".$qstring);
					exit();	
				}	else	{
				$_SESSION['err_msg']="Error : <p>Wrong procedure: Oparation Failed.</p>";	
				//header("location: register.php");
				//exit();		
				}
			}else{
				$_SESSION['err_msg']='Error : Wastage Code already exists';
			}
				
		}else{ 
			if(chkDuplicateUser($_POST['wastage_no'],$_POST['wastage_id'])){
				
				$sql_edit="UPDATE wastage_master SET wastage_no='".trim(strtoupper($_POST['wastage_no']))."', ";
				$sql_edit.="'" . date('Y-m-d', strtotime($_POST['wastage_date'])) . "',item_type='".trim($_POST['item_type'])."',unit_id='".trim($_POST['item_id'])."'";
				$sql_edit.="wastage_qty='".trim($_POST['wastage_qty'])."'";
 				$sql_edit.=" WHERE wastage_id='".trim($_POST['wastage_id'])."'";
		 		//echo $sql_edit;
				 
				if(mysql_query($sql_edit))	{
					$_SESSION['err_msg']='Record Updated';
					$mode='edit';
					header("location:./wastage_list.php?".$qstring);
					exit();	
				}	else	{
					$_SESSION['err_msg']="Error :<p>Wrong procedure: Oparation Failed.</p>";	
				
				}
			}else{
				$_SESSION['err_msg']='Error : Duplicate Wastage Code';
			
			}	
	
	}
 }
if($mode=="del"){
	$sql_delete="DELETE FROM wastage_master WHERE wastage_id='".trim($_REQUEST['wastage_id'])."'";
	//echo $sql_delete;
	mysql_query($sql_delete);
	$_SESSION['err_msg']='Record Deleted';
	header("location: #");
	exit();

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>A.S.M.I.</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="../js/CalendarPopup.js"></script>
	<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>

	<script type="text/javascript" src="../js/AnchorPosition.js"></script>
	<script type="text/javascript" src="../js/date.js"></script>
	<script type="text/javascript" src="../js/PopupWindow.js"></script>
<script language="javascript" src="calender/dhtmlgoodies_calendar.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="calender/dhtmlgoodies_calendar.css" />
	
	
	<script language="JavaScript" type="text/javascript">
	 
	function IsNill(frm)
	{
	
		   if(frm.invoice_no.value.length=="")
		   {
				   alert("Please enter Order No");
				   frm.invoice_no.focus();
				   return false;        
		   }
		   
		   if(frm.purchase_order_date.value.length=="")
		   {
				   alert("Please enter Order date");
				   frm.purchase_order_date.focus();
				   return false;        
		   }
		   
		   if(frm.doc_no.value.length=="")
		   {
				   alert("Please enter DOC no");
				   frm.doc_no.focus();
				   return false;        
		   }
		   
		   if(frm.doc_date.value.length=="")
		   {
				   alert("Please enter DOC date");
				   frm.doc_date.focus();
				   return false;        
		   }
 		   if(frm.party_id.value=="")
		   {
				   alert("Please select Party");
				   frm.party_id.focus();
				   return false;        
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
        <td width="200" align="center" valign="top" bgcolor="#e6e6e6"><br />
          <?php include("left_menu.php");?></td>
        <td align="center" valign="top"><br />
            <br />
              <br />
				<?php
				if(isset($msg))
				{
					echo $msg . '<br /><br />';
				}
 
if($wastage_id !=""){
 $sql_edit=mysql_query("SELECT * FROM wastage_master WHERE wastage_id ='$wastage_id '") or die(mysql_error());
 $r_edit= mysql_fetch_array($sql_edit);
}	
?>
              <table width="90%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="frmregistration" id="frmregistration" onsubmit="return IsNill(this)">
				   <input name="mode" type="hidden" value="<?php echo $mode ?>" />
				  <input name="item_id" type="hidden" value="<?php echo $_REQUEST["wastage_id"] ?>" />

                   <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Add/Edit Wastage / Damage </strong></td>
                      </tr>
                      <tr>
                        <td width="20%" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?php echo $_SESSION['err_msg'] ?>&nbsp;</td><?php echo $_SESSION['err_msg']=""; ?>						
                      </tr>
					
 					<tr>
                      <td>
                        <label>Wastage No. (*): </label></td>
                      <td height="20"><input name="wastage_no" class="input" id="wastage_no" value="<?php echo $r_edit["wastage_no"] ?>" tabindex="1" size="20" /></td>
                    </tr>
					<tr>
                      <td>Date (*):</td>
                      <td height="20"><input name="wastage_date" class="input" id="wastage_date" tabindex="2" value="<?php echo $r_edit["wastage_date"]?>" size="20"  readonly />
					  &nbsp;
					  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
						var cal1x = new CalendarPopup("testdiv1");
						</SCRIPT>
						<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
						<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
					 <img src="calender/btn_dropdown.gif" alt="Select Date" width="18" height="18" onClick="displayCalendar(document.getElementById('wastage_date'),'mm/dd/yyyy',this); return false;" align="absbottom" />
					  </td>
                    </tr>
   					<tr>
                      <td>Item Type:</td>
                      <td height="20">
					  	<select name="item_type" id="item_type" tabindex="3" onchange="setItemCodePrefix(this.value);">
                        <option value="" selected="selected">< -- Select -- ></option>
                        <option value="Raw Material" <?php if($r_edit["item_type"]=="Raw Material") echo "selected"; ?>>Raw Material</option>
                        <option value="Work-In-Progress" <?php if($r_edit["item_type"]=="Work-In-Progress") echo "selected"; ?>>Work-In-Progress</option>
						<option value="Finished Goods" <?php if($r_edit["item_type"]=="Finished Goods") echo "selected"; ?>>Finished Goods</option>
                       </select>
					  </td>
                    </tr>
					
					<tr>
                      <td>Name (*) :</td>
                      <td height="20">
					  	<select name="item_id" tabindex="4" style="width:500px;"  >
					  <option value="">< -- Select -- ></option>
					  <?php
					  $sql_imt="select * from item_master order by item_name";
					  $rs_imt=mysql_query($sql_imt);
 					  while($row_imt=mysql_fetch_array($rs_imt)){
					  ?>
					  <option value="<?=$row_imt["item_id"]?>" <? if( $r_edit["item_id"]==$row_imt["item_id"]) echo "selected";?>><? echo $row_imt["item_name"];?></option>
					  <? } ?>
					  </select>
					  </td>
                    </tr>
 					<tr>
                      <td>Quantity (*):</td>
                      <td height="20"><input name="wastage_qty" class="input" id="wastage_qty" tabindex="5" value="<?php echo $r_edit["wastage_qty"]?>" size="20" /></td>
                    </tr> 
                   	<tr>
                      <td>&nbsp;</td>
                      <td><input type="submit" value="Save" /> &nbsp;<input type="button" value="Cancel" onclick="window.location.href='./wastage_list.php'"></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
				  </form>
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
