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
$item_id=$_REQUEST['item_id'];
function chkDuplicateUser($item_code,$item_id=""){

	if($item_code==""){
	return false;
	
	}
	if($item_id!="")
	$sql_user="select * from item_master where item_code='$item_code' and item_id!='$item_id'";
	else
	$sql_user="select * from item_master where item_code='$item_code' ";
	$rs_usr=mysql_query($sql_user);
	if(mysql_num_rows($rs_usr)>0) 
	return false;
	else
	return true;

}
if(isset($_POST['bsave'])){
	if( $item_id==""){	
		if(chkDuplicateUser(strtoupper($_POST['item_code']))){
			
			$sql_insert="INSERT INTO item_master (item_code,item_name,item_description,item_stock,unit_id,category_id,capacity_id,item_type,item_purchase_rate,item_sale_rate)";
			
			$sql_insert.="VALUES('".trim(strtoupper($_POST['item_code']))."', '".trim(addslashes(ucwords($_POST['item_name'])))."',
			'".trim(addslashes(ucwords($_POST['item_description'])))."','".trim(strtoupper($_POST['item_stock']))."',";
			$sql_insert.="'".trim($_POST['unit_id'])."','".trim($_POST['category_id'])."','".trim($_POST['capacity_id'])."','".trim($_POST['item_type'])."',";
			$sql_insert.="'".trim($_POST['item_purchase_rate'])."','".trim($_POST['item_sale_rate'])."')";
			
			 //echo $sql_insert;
			 
				if(mysql_query($sql_insert))	{
					$_SESSION['err_msg']='Message : Record Added';
					$mode="add";
					header("location:./item_list.php?".$qstring);
					exit();	
				}	else	{
				$_SESSION['err_msg']="Error : <p>Wrong procedure: Oparation Failed.</p>";	
				//header("location: register.php");
				//exit();		
				}
			}else{
				$_SESSION['err_msg']='Error : Item Code already exists';
			}
				
		}else{ 
			if(chkDuplicateUser($_POST['item_code'],$_POST['item_id'])){
				$sql_edit="UPDATE item_master SET item_code='".trim(strtoupper($_POST['item_code']))."', ";
				$sql_edit.="item_name='".trim(addslashes(ucwords($_POST['item_name'])))."',item_description='".trim(addslashes(ucwords($_POST['item_description'])))."',item_stock='".trim(strtoupper($_POST['item_stock']))."',";
				$sql_edit.="unit_id='".trim($_POST['unit_id'])."',category_id='".trim($_POST['category_id'])."',capacity_id='".trim($_POST['capacity_id'])."',";
				$sql_edit.="item_type='".trim($_POST['item_type'])."',item_purchase_rate='".trim($_POST['item_purchase_rate'])."',";
				$sql_edit.="item_sale_rate='".trim($_POST['item_sale_rate'])."'";
 				$sql_edit.=" WHERE item_id='".trim($_POST['item_id'])."'";
		 		//echo $sql_edit;
				 
				if(mysql_query($sql_edit))	{
					$_SESSION['err_msg']='Record Updated';
					$mode='edit';
					header("location:./item_list.php?".$qstring);
					exit();	
				}	else	{
					$_SESSION['err_msg']="Error :<p>Wrong procedure: Oparation Failed.</p>";	
				
				}
			}else{
				$_SESSION['err_msg']='Error : Duplicate Item Code';
			
			}	
	
	}
 }
if($mode=="del"){
	$sql_delete="DELETE FROM item_master WHERE item_id='".trim($_REQUEST['item_id'])."'";
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

<link href="../includes/CalendarControl.css"
      rel="stylesheet" type="text/css">
<script src="../includes/CalendarControl.js"
        language="javascript"></script>
		<script language="JavaScript" type="text/javascript">
function IsNill(frm)
{

	   if(frm.item_code.value.length=="")
               {
                       alert("Please enter code");
                       frm.item_code.focus();
                       return false;        
               }
	   
	   if(frm.item_name.value.length=="")
               {
                       alert("Please enter name");
                       frm.item_name.focus();
                       return false;        
               }

	   if(frm.item_stock.value.length=="")
               {
                       alert("Please enter quantity");
                       frm.item_stock.focus();
                       return false;        
               }
		
       return true;
}

	function setItemCodePrefix(item_state)
	{
		var prefix = "";
		var item_code = "";
		if(item_state == "Raw Material")
		{
			prefix = "R";
		}
		else if(item_state == "Work-In-Progress")
		{
			prefix = "W";
		}
		else if(item_state == "Finished Goods")
		{
			prefix = "F";
		}
		
		item_code = document.getElementById("item_code").value;
		
		if(item_code.length == 0)
		{
			document.getElementById("item_code").value = prefix;
		}
		else
		{
			//We had something, replace the firts letter with the prefix
			document.getElementById("item_code").value = prefix + item_code.substr(1);
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
 
if($item_id !=""){
 $sql_edit=mysql_query("SELECT * FROM item_master WHERE item_id ='$item_id '") or die(mysql_error());
	$r_edit= mysql_fetch_array($sql_edit);
	 
}	
?>
              <table width="400" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="frmregistration" id="frmregistration" onsubmit="return IsNill(this)">
				   <input name="mode" type="hidden" value="<?php echo $mode ?>" />
				  <input name="item_id" type="hidden" value="<?php echo $_REQUEST["item_id"] ?>" />
				  

                   <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Add/Edit Item </strong></td>
                      </tr>
                      <tr>
                        <td width="123" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?php echo $_SESSION['err_msg'] ?>&nbsp;</td><?php echo $_SESSION['err_msg']=""; ?>
						<td width="84">&nbsp;</td>
                      </tr>
                    <tr>
                      <td>
                        <label>Code (*): </label></td>
                      <td height="20"><input name="item_code" class="input" id="item_code" value="<?php echo $r_edit["item_code"] ?>" tabindex="1" size="20" /></td>
                    </tr>
					<tr>
                      <td>Name (*):</td>
                      <td height="20"><input name="item_name" class="input" id="item_name" tabindex="2" value="<?php echo $r_edit["item_name"]?>" size="20" /></td>
                    </tr>
					
					<tr>
                      <td>Description :</td>
                      <td height="20"><textarea name="item_description" tabindex="3" cols="20" rows="3" style="width:250px;"><?=$r_edit["item_description"]?></textarea></td>
                    </tr>
                    <tr>
                      <td>Quantity (*):</td>
                      <td height="20"><input name="item_stock" class="input" id="item_stock" tabindex="4" value="<?php echo $r_edit["item_stock"]?>" size="20" /></td>
                    </tr> 
					 <tr>
                      <td>Unit:</td>
                      <td height="20">
					  <select name="unit_id" tabindex="5">
					  <option value="">< -- Select -- ></option>
					  <?php
					  $sql_unt="select * from unit_master order by unit_name";
					  $rs_unt=mysql_query($sql_unt);
 					  while($row_unt=mysql_fetch_array($rs_unt)){
					  ?>
					  <option value="<?=$row_unt["unit_id"]?>" <? if( $r_edit["unit_id"]==$row_unt["unit_id"]) echo "selected";?>><? echo $row_unt["unit_name"];?></option>
					  <? } ?>
					  </select>
					  </td>
                    </tr>
 					
					<tr>
                      <td>Capacity:</td>
                      <td height="20">
					  <select name="capacity_id" tabindex="6">
					  <option value="">< -- Select -- ></option>
					  <?php
					  $sql_cap="select * from capacity_master order by capacity_name";
					  $rs_cap=mysql_query($sql_cap);
 					  while($row_cap=mysql_fetch_array($rs_cap)){
					  ?>
					  <option value="<?=$row_cap["capacity_id"]?>" <? if( $r_edit["capacity_id"]==$row_cap["capacity_id"]) echo "selected";?>><? echo $row_cap["capacity_name"];?></option>
					  <? } ?>
					  </select>
					  </td>
                    </tr>
  					<tr>
                      <td>Grade:</td>
                      <td height="20">
					  <select name="category_id" tabindex="7">
 					  <option value="">< -- Select -- ></option>
					  <?php
						$sql_catg=mysql_query("SELECT a.category_id as catid,a.category_name as catname,b.category_name as parentname FROM category a left outer join category b on b.category_id=a.parentid order by a.parentid") or die(mysql_error());
						while($r_catg=mysql_fetch_array($sql_catg)){
						$catname="";
						if ($r_catg['parentname']!="")
						$catname=$r_catg['parentname']."::".$r_catg['catname'];
						else
						$catname=$r_catg['catname'];
						?>
 					  <option value="<? echo $r_catg['catid'];?>" <? if($r_edit['category_id']==$r_catg['catid']) { echo "selected"; }  ?> ><? echo $catname;?></option>
					  <?php } ?>
					  </select>
					  </td>
                    </tr>
 					<tr>
                      <td>Type :</td>
                      <td height="20">
					  <select name="item_type" id="item_type" tabindex="8" onchange="setItemCodePrefix(this.value);">
                        <option value="" selected="selected">< -- Select -- ></option>
                        <option value="Raw Material" <?php if($r_edit["item_type"]=="Raw Material") echo "selected"; ?>>Raw Material</option>
                        <option value="Work-In-Progress" <?php if($r_edit["item_type"]=="Work-In-Progress") echo "selected"; ?>>Work-In-Progress</option>
						<option value="Finished Goods" <?php if($r_edit["item_type"]=="Finished Goods") echo "selected"; ?>>Finished Goods</option>
                       </select>
					  </td>
                    </tr>
                    <tr>
                      <td>Purchase Rate:</td>
                      <td height="20"><input name="item_purchase_rate" class="input" id="item_purchase_rate" tabindex="9" value="<?php echo $r_edit["item_purchase_rate"] ?>" size="20" /></td>
                    </tr>
					<tr>
                      <td>Sale Rate:</td>
                      <td height="20"><input name="item_sale_rate" class="input" id="item_sale_rate" tabindex="10" value="<?php echo $r_edit["item_sale_rate"] ?>" size="20" /></td>
                    </tr>
					<?php /*?><tr>
                      <td>Capacity:</td>
                      <td height="20"><input name="capacity" class="input" id="capacity" tabindex="11" value="<?php echo $r_edit["capacity"] ?>" size="20" /></td>
                    </tr><?php */?>
					
                    <tr>
                      <td>&nbsp;</td>
                      <td> 
                        <input type="submit" name="bsave" value="Submit " tabindex="12" />
                        <input type="reset" name="cancelbutton" value="Cancel" onclick="JavaScript:window.location.href='item_list.php'" />
                       </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
				  </form></td>
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
