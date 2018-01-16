<?php
ob_start();
session_start();

include("../includes/config.php");include("sessiontime.php");
$msglog= "Welcome to Admin Panel !";

if($_SERVER['REQUEST_METHOD']=="POST"){

$user = $_POST['user_name'];
$password = $_POST['user_password'];
$utype = $_POST['user_type'];
$fin_year = $_POST['fin_year'];

 //$enmUniv = $_POST['enmUniv'];

$sql_user = mysql_query("SELECT COUNT(*) AS user_log FROM user_master WHERE user_name='$user' AND user_password='$password'") or die(mysql_error());
$r_user = mysql_fetch_array($sql_user);

if ($r_user['user_log']==1){
	$_SESSION["login"]=$user;
	$_SESSION["Type"]=$utype;
	$_SESSION["fin_year"]=$fin_year;
	//echo $_SESSION["login"];
	 header("location:index.php");
	exit();
	} else {
	$msglog ="<font color='#FF0000'>Invalid username or password !!</font>";
	}
	/**/
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A.S.M.I.</title>

<SCRIPT language=javascript type=text/javascript>
	function setFocus() {
		document.loginForm.usr.select();
		document.loginForm.usr.focus();
	}
	function IsNill(frm)
	{
		if(frm.user_name.value.length=="")
	   {
		   alert("Please enter username");
		   frm.user_name.focus();
		   return false;        
	   }
	   if(frm.user_password.value.length=="")
	   {
		   alert("Please enter password");
		   frm.user_password.focus();
		   return false;        
	   }
	   if(frm.user_type.value=="")
	   {
		   alert("Please select type ");
		   frm.user_type.focus();
		   return false;        
	   }
	   return true;
	}
		   
</SCRIPT>

<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="111" align="center" valign="bottom" background="images/header.gif"><table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="29%"><img src="images/logo.jpg" width="100" height="107" /></td>
        <td width="67%" class="header" align="center">Associated Scientific Manufacturing Industries</td>
        <td width="4%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="450" align="center" bgcolor="#FFFFFF"><form id=loginForm name=loginForm action="<?php echo $_SERVER['PHP_SELF'] ?>" method=post onsubmit="return IsNill(this)"> 

      <table width="400" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
        <tr>
          <td bgcolor="#FFFFFF"><table width="400" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="30" colspan="3" background="images/login_bg.gif"><img src="images/login_bg.gif" width="15" height="30" align="absmiddle" /><strong>Login</strong></td>
            </tr>
            <tr>
              <td width="123" height="30">&nbsp;</td>
              <td width="193" height="30" align="center"><?=$msglog?></td>
              <td width="84">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="right">Login:</td>
              <td height="30" align="left"><label>
                <input type="text" name="user_name" />
              </label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="right">Password:</td>
              <td height="30" align="left"><input type="password" name="user_password" /></td>
			  
              <td>&nbsp;</td>
            </tr>
			<tr>
				  <td align="right">Financial Year:</td>
				  <td height="20" align="left">
				  <select name="fin_year" id="fin_year" tabindex="3">
				   <?php
				   list($fm,$fy)=explode("-",date("m-Y"));
					if($fm<=3){
						$fy=$fy-1;
					}
					else{
						$fy=$fy;
						}
						for($i=2010;$i<=$fy;$i++) {
						$formated_fin_year = $i."-".($i+1); 
						?>
                        <option value="<?php echo $i;?>"<?php if($i==$fy) echo "selected";?>><?=$formated_fin_year?></option>
                        
                        <?php
						} ?>
 				  </select></td>
				  <td>&nbsp;</td>
                </tr>
                <tr>
				  <td align="right">Select Type:</td>
				  <td height="20" align="left">
				  <select name="user_type" id="user_type" tabindex="3">
				  <option value="Operator"  <?php #if($r_edit["user_type"]=="Operator") echo "selected"; ?>>Operator</option>
				  <option value="Admin" selected <?php #if($r_edit["user_type"]=="Admin") echo "selected"; ?>>Admin</option>
				  <option value="Super Admin" <?php #if($r_edit["user_type"]=="Super Admin") echo "selected"; ?>>Super Admin</option>
 				  </select></td>
				  <td>&nbsp;</td>
                </tr>
            <tr>
              <td height="30">&nbsp;</td>
              <td height="30" align="center"><label>
                <input type="submit" name="bsave" id ="Submit" value="Submit" />
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
      </table>
    </form>
    </td>
  </tr>
  <!--<tr>
    <td height="37" background="images/footer.gif">&nbsp;</td>
  </tr>-->
  <?php include("footer.inc.php");?>
</table>
</body>
</html>
