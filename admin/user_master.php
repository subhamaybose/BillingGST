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
//include("../includes/utils.inc.php");

$mode=$_REQUEST['mode'];
$user_id=$_REQUEST['user_id'];
function chkDuplicateUser($username,$user_id=""){

	if($username==""){
	return false;
	
	}
	if($user_id!="")
	$sql_user="select * from user_master where user_name='$username' and user_id!='$user_id'";
	else
	$sql_user="select * from user_master where user_name='$username' ";
	$rs_usr=mysql_query($sql_user);
	if(mysql_num_rows($rs_usr)>0) 
	return false;
	else
	return true;

}
if(isset($_POST['bsave'])){
	if( $user_id==""){	
		if(chkDuplicateUser($_POST['user_name'])){
			$sql_insert="INSERT INTO user_master (user_name, user_password, user_type, status)";
			$sql_insert.="VALUES('".trim(addslashes($_POST['user_name']))."', '".trim(addslashes($_POST['user_password']))."', ";
			$sql_insert.="'".trim($_POST['user_type'])."','".trim($_POST['status'])."')";	
			//echo $sql_insert;
				if(mysql_query($sql_insert))	{
					$_SESSION['err_msg']='Message : Record Added';
					$mode="add";
					header("location: user_list.php");
					exit();	
				}	else	{
				$_SESSION['err_msg']="Error : <p>Wrong procedure: Oparation Failed.</p>";	
				//header("location: user_list.php");
				//exit();		
				}
			}else{
				$_SESSION['err_msg']='Error :  User name already exists';
			
			}	
		}else{ 
			if(chkDuplicateUser($_POST['user_name'],$_POST['user_id'])){
				$sql_edit="UPDATE user_master SET user_name='".trim(addslashes($_POST['user_name']))."', ";
				$sql_edit.="user_password='".trim(addslashes($_POST['user_password']))."', user_type='".trim($_POST['user_type'])."'";
				$sql_edit.=",status='".trim($_POST['status'])."'";
				$sql_edit.=" WHERE user_id='".trim($_POST['user_id'])."'";
			//echo $sql_edit;
	
				if(mysql_query($sql_edit))	{
					$_SESSION['err_msg']='Record Updated';
					$mode='edit';
					header("location: user_list.php");
					exit();	
				}	else	{
					$_SESSION['err_msg']="Error :<p>Wrong procedure: Oparation Failed.</p>";	
				
				}
			}else{
				$_SESSION['err_msg']='Error :  User name already exists';
			
			}	
	
	}
 }
if($mode=="del"){
	$sql_delete="DELETE FROM user_master WHERE user_id='".trim($_REQUEST['user_id'])."'";
	//echo $sql_delete;
	mysql_query($sql_delete);
	$_SESSION['err_msg']='Record Deleted';
	header("location: user_list.php");
	exit();

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A.S.M.I.</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />

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
          <? include("left_menu.php");?></td>

		<td align="center" valign="top"><br />
            <br />
              <br />
 <?php
 
if($user_id!=""){
 $sql_edit=mysql_query("SELECT * FROM user_master WHERE user_id='$user_id'") or die(mysql_error());
	$r_edit= mysql_fetch_array($sql_edit);
}	
?>
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmEdit" id="frmEdit">
		<input name="mode" type="hidden" value="<?=$mode?>" />
		<input name="user_id" type="hidden" value="<?=$user_id?>" />
              <table width="400" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF"><table width="400" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong>Add/Edit User </strong></td>
                      </tr>
                      <tr>
                        <td width="123" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?=$_SESSION['err_msg']?>&nbsp;</td><? $_SESSION['err_msg']=""; ?>
						<td width="84">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" align="right">Name:</td>
                        <td height="30" align="left"><label>
                        <input name="user_name" type="text" class="input" id="user_name" value="<?=$r_edit['user_name']?>"/>
                        </label></td>
                        <td>&nbsp;</td>
                      </tr>
					  
                       <tr>
                        <td height="30" align="right">Password:</td>
                        <td height="30" align="left">
						<input name="user_password" type="password" class="input" id="user_password" value="<?=$r_edit['user_password']?>"/></td>
                        <td>&nbsp;</td>
                      </tr>
					  <tr>
                        <td height="30" align="right">Confirm Password:</td>
                        <td height="30" align="left">
						<input name="varConfPassword" type="password" class="input" id="varConfPassword" value="<?=$r_edit['user_password']?>"/></td>
                        <td>&nbsp;</td>
                      </tr>

					<tr>
					<td height="30" align="right">Type:</td>
					<td height="30" align="left"><select name="user_type" id="user_type">
					<option value="0" selected>--- Select ---</option>
					<option value="Operator" <? if($r_edit["user_type"]=="Operator") echo "selected"; ?>>Operator</option>
					<option value="Admin" <? if($r_edit["user_type"]=="Admin") echo "selected"; ?>>Admin</option>
					<option value="Super Admin" <? if($r_edit["user_type"]=="Super Admin") echo "selected"; ?>>Super Admin</option>
					</select></td>
					<td>&nbsp;</td>
				  </tr>
					  
                      <tr>
                        <td height="30" align="right">Status:</td>
                        <td height="30" align="left"><select name="status" id="status">
          				<option value="0" selected>--- Select ---</option>
          				<option value="Active" <? if($r_edit["status"]=="Active") echo "selected"; ?>>Active </option>
                        <option value="Pending" <? if($r_edit["status"]=="Pending") echo "selected"; ?>>Pending </option>
				        </select></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30">&nbsp;</td>
                        <td height="30" align="left"><label>&nbsp;
                            <input type="submit" name="bsave" value="Save" />
    						<input name="cancelbutton" type="button" value="Cancel" onclick="JavaScript:window.location.href='user_list.php'" />
                        </label></td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30">&nbsp;</td>
                        <td height="30">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                  </table></td>
                </tr>
              </table></form>
 			  </td>
      </tr>
    </table></td>
  </tr>
  
  <!--<tr>
    <td height="37" background="images/footer.gif">&nbsp;</td>
  </tr>-->
  <?php include("footer.inc.php");?>
</table>
		
</body>
</html>
