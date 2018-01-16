<?php
ob_start();
session_start();

if(!isset($_SESSION["login"])){
header("location:./login.php");
exit();
}

?><?php 
include("../includes/config.php");include("sessiontime.php");

$mode=$_REQUEST['mode'];
$unit_id=$_REQUEST['unit_id'];
function chkDuplicateUser($unit_name,$unit_id=""){

	if($unit_name==""){
	return false;
	
	}
	if($unit_id!="")
	$sql_user="select * from unit_master where unit_name='$unit_name' and unit_id!='$unit_id'";
	else
	$sql_user="select * from unit_master where unit_name='$unit_name'";
	$rs_usr=mysql_query($sql_user);
	if(mysql_num_rows($rs_usr)>0) 
	return false;
	else
	return true;

}
if(isset($_POST['bsave'])){
	if( $unit_id==""){	
		if(chkDuplicateUser(ucwords($_POST['unit_name']))){
			$sql_insert="INSERT INTO unit_master (unit_name )";
			$sql_insert.="VALUES('".trim(addslashes(ucwords($_POST['unit_name'])))."')";

			 //echo $sql_insert;
			 
				if(mysql_query($sql_insert))	{
					$_SESSION['err_msg']='Message : Record Added';
					$mode="add";
					header("location:./unit_list.php");
					exit();	
				}	else	{
				$_SESSION['err_msg']="Error : <p>Wrong procedure: Oparation Failed.</p>";	
				//header("location: register.php");
				//exit();		
				}
			}else{
				$_SESSION['err_msg']='Error : In Unit name already exists';
			
			}	
		}else{ 
			if(chkDuplicateUser(ucwords($_POST['unit_name']),$_POST['unit_id'])){
				$sql_edit="UPDATE unit_master SET unit_name='".trim(addslashes(ucwords($_POST['unit_name'])))."'";
				$sql_edit.=" WHERE unit_id='".trim($_POST['unit_id'])."'";
		 		//echo $sql_edit;
				 
				if(mysql_query($sql_edit))	{
					$_SESSION['err_msg']='Record Updated';
					$mode='edit';
					header("location:./unit_list.php");
					exit();	
				}	else	{
					$_SESSION['err_msg']="Error :<p>Wrong procedure: Oparation Failed.</p>";	
				
				}
			}else{
				$_SESSION['err_msg']='Error : Ed Unit name already exists';
			}	
	}
 }
if($mode=="del"){
	$sql_delete="DELETE FROM unit_master WHERE unit_id='".trim($_REQUEST['unit_id'])."'";
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
	   if(frm.unit_name.value.length=="")
               {
                       alert("Please enter name");
                       frm.unit_name.focus();
                       return false;        
               }
	
       return true;
}
function chng(showid){
  if(showid=="r"){
 	document.getElementById("rec").style.display='block';
	document.getElementById("advt").style.display='none';
 }else if(showid=="a"){
 	document.getElementById("advt").style.display='block';
	document.getElementById("rec").style.display='none';
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
			   <?php
 
if($unit_id !=""){
 $sql_edit=mysql_query("SELECT * FROM unit_master WHERE unit_id ='$unit_id '") or die(mysql_error());
 $r_edit= mysql_fetch_array($sql_edit);
}	
?>
              <table width="99%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				  <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmregistration" id="frmregistration" onsubmit="return IsNill(this)">
				   <input name="mode" type="hidden" value="<?=$mode?>" />
				  <input name="unit_id" type="hidden" value="<?=$_REQUEST["unit_id"]?>" />

                   <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Add/Edit Unit </strong></td>
                      </tr>
                      <tr>
                        <td width="123" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?=$_SESSION['err_msg']?>&nbsp;</td><? $_SESSION['err_msg']=""; ?>
						<td width="84">&nbsp;</td>
                      </tr>
                      <td>Unit Name (*):</td>
                      <td height="20"><input name="unit_name" class="input" id="unit_name" tabindex="3" value="<?=$r_edit["unit_name"]?>" size="50" /></td>
                    </tr>
                     
                    <tr>
                      <td>&nbsp;</td>
                      <td> 
                        <input type="submit" name="bsave" value="Submit " />
                        <input type="reset" name="cancelbutton" value="Cancel" onclick="JavaScript:window.location.href='unit_list.php'" />
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
