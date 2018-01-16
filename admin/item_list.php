<?php
ob_start();
session_start();

if(!isset($_SESSION["login"])){
header("location:./login.php");
exit();

}
if (isset($_SESSION["LAST_ACTIVITY"]) && (time() - $_SESSION["LAST_ACTIVITY"] > 3600)) {
    session_destroy();  
    session_unset();  
}
$_SESSION['LAST_ACTIVITY'] = time();
$mode=$_REQUEST['mode'];
$srchString=$_REQUEST['srchString'];
$pageNumber=$_REQUEST["page"];
$recsOnPage=25;
if($pageNumber=="")
	$pageNumber = 1;
?>

<?php 
include("../includes/config.php");include("sessiontime.php");
include("../includes/utils.inc.php");

if($mode=="del"){
	$sql_delete="DELETE FROM item_master WHERE item_id='".trim($_REQUEST['item_id'])."'";
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

<script type="text/javascript" src="../js/jquery.min.1.4.4.js"></script>
  <script type="text/javascript" src="../js/jquery.autocomplete.js"></script>
	<link rel="stylesheet" href="./css/jquery.autocomplete.css" type="text/css" />
  <script language="javascript">
function delChk(item_id,item_code){
if(confirm("Do you want to delete the item ? ")){
 window.location.href="./item_list.php?item_id="+item_id+"&mode=del&"+item_code;
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
        <td width="200" align="left" valign="top" bgcolor="#e6e6e6"><?php include("left_menu.php");?></td>
        <td align="center" valign="top"><br />
            <br />
              <br />
             <table width="99%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td height="30" colspan="8" background="images/login_bg.gif"><img src="images/login_bg.gif" width="15" height="30" align="absmiddle" /><strong>Item List</strong></td>
						<td align="right"  background="images/login_bg.gif"><input name="adbutton" type="button" class="btn" value="Add" onclick="JavaScript:window.location.href='item_master.php?mode=add&<?php echo $qstring ?>'" /></td>
                      </tr>
					  <form name="frm1" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
					  
					  <tr>
					  <td colspan="1">Type</td>
					  
					  <?php /*?><td colspan="1"> 
					  <select name="fitem_id" style="width:150px">
					  <option value="">All</option>
					  <?php
					   $sql_usr="select *  from item_master order by item_type";
					  $rs_usr=mysql_query($sql_usr);
 					  while($row_usr1=mysql_fetch_array($rs_usr)){
					  ?>
					  <option value="<?php echo $row_usr1["item_id"]?>" <?php if( $_REQUEST["fitem_id"]==$row_usr1["item_id"]) echo "selected";?>><?php echo $row_usr1["item_type"];?></option>
					  <?php } ?>
					  </select>
					  </td><?php */?>
					  
					  <td colspan="1">
					  		<select name="item_type" id="item_type" style="width:150px;">
								<option value="0" selected>--- Select ---</option>
								<option value="Raw Material" <?php if($_REQUEST['item_type']=="Raw Material"){ echo 'Selected'; } ?>>Raw Material</option>
								<option value="Work-In-Progress" <?php if($_REQUEST['item_type']=="Work-In-Progress"){ echo 'Selected'; } ?>>Work-In-Progress</option>
								<option value="Finished Goods"<?php if($_REQUEST['item_type']=="Finished Goods"){ echo 'Selected'; } ?>>Finished Goods</option>
 						   </select>
  					  </td>
                      <td>Item Name</td>
 					  <td colspan="5" nowrap="nowrap"><input type="text" name="srchString"  id="srchAjax" style="width:300px;" class="ac_input" value="<?=$srchString?>" />
                       <script type="text/javascript">
  function findValue(li) {
	 // alert(li);
  	if( li == null ) return alert("No match!");

  	// if coming from an AJAX call, let's use the CityId as the value
  	if( !!li.extra ) var sValue = li.extra[0];

  	// otherwise, let's just display the value in the text box
  	else var sValue = li.selectValue;

  	//alert("The value you selected was: " + sValue);
  }

  function selectItem(li) {
    	findValue(li);
  }

  function formatItem(row) {
    	return row[0] + " (id: " + row[1] + ")";
  }

  function lookupAjax(){
  	var oSuggest = $("#srchAjax")[0].autocompleter;
    oSuggest.findValue();
  	document.frm1.submit();
	//return false;
  }

  function lookupLocal(){
    	var oSuggest = $("#srchAjax")[0].autocompleter;
     	oSuggest.findValue();

    	return false;
  }
  
  
    $("#srchAjax").autocomplete(
      "autocomplete_item.php",
      {
  			delay:10,
  			minChars:2,
  			matchSubset:1,
  			matchContains:1,
  			cacheLength:10,
  			onItemSelect:selectItem,
  			onFindValue:findValue,
  			formatItem:formatItem,
  			autoFill:true
  		}
    );
  
</script>
                      </td>
					 <td colspan="1">
					  <input type="button" value="Filter" name="but1" onclick="lookupAjax();" />
					  </td>
					  </tr>
					  </form>
                      <tr>
                        <td width="7%" height="30" align="center" bgcolor="#e6e6e6"><strong>Item Code</strong></td>
                        <td width="15%" align="center" bgcolor="#e6e6e6"><strong>Name</strong></td>
						<td width="10%" align="center" bgcolor="#e6e6e6"><strong>HSN Code</strong></td>
						<td width="10%" align="center" bgcolor="#e6e6e6"><strong>Capacity</strong></td>
 						<td width="10%" align="center" bgcolor="#e6e6e6"><strong>Porosity</strong></td>
						<td width="10%" align="center" bgcolor="#e6e6e6"><strong>Quantity</strong></td>
						<td width="11%" align="center" bgcolor="#e6e6e6"><strong>Unit</strong></td>
                        <td width="16%" height="30" align="center" bgcolor="#e6e6e6"><strong>Type</strong></td>
                        <td width="11%" align="center" bgcolor="#e6e6e6"><strong>Action</strong></td>
                      </tr>
					   <?php
					   if($_REQUEST['item_type']!="")
					   {
					   $cond=" and item_type='".$_REQUEST['item_type']."'";
					   }
					    if($srchString!="")
					   {
					   $cond=" and (item_name='".$srchString."' or item_name like '%".$srchString."%')";
					   }
					   
					   $sql_usr="select count(*) from item_master where 1 $cond order by item_type,item_name ";
					  
						 $qstring="item_type=".$_REQUEST['item_type']."&srchString=".$srchString;
						 
					  $rs_usr=mysql_query($sql_usr);
					  $row=mysql_fetch_row($rs_usr);
					  $nr=$row[0];
					   if ($nr > 0){
							$pages = round($nr / $recsOnPage, 0);
 							if (round($nr / $recsOnPage, 2) > $pages) $pages++;
							if ($pageNumber < 1) $pageNumber = 1;
							if ($pageNumber > $pages) $pageNumber = $pages;
							$cPage=($pageNumber-1) * $recsOnPage;
							
					  $sql_usr="select *  from item_master where 1 $cond order by item_code,item_name ";
					  $sql_usr.= "  limit $cPage,$recsOnPage ";
					  $rs_usr=mysql_query($sql_usr);
					  if(mysql_num_rows($rs_usr)>0){
					  while($row_usr=mysql_fetch_array($rs_usr)){
					  
					  ?>
                      <tr>
                        <td width="7%" align="center"><?php echo $row_usr['item_code']; ?></td>
                        <td width="15%" align="center"><?php echo $row_usr['item_name']; ?></td>
						<td width="10%" align="center"><?php echo $row_usr['item_hsn']; ?></td>
						<td width="10%" align="center" nowrap="nowrap"><?php echo shw_capacity($row_usr['capacity_id'])?></td>
						<td width="10%" align="center" nowrap="nowrap"><?php echo shw_category($row_usr['category_id'])?></td>
						<td width="10%" align="center"><?php echo $row_usr['item_stock']; ?></td>
						<td width="11%" align="center" nowrap="nowrap"><?php echo shw_unit($row_usr['unit_id'])?></td>
                        <td width="16%" height="30" align="center" nowrap="nowrap"><?php echo $row_usr['item_type']; ?></td>
                        <td align="center" nowrap="nowrap"> 
                           <input name="edbutton" type="button" class="btn" value="Edit" onclick="JavaScript:window.location.href='./item_master.php?item_id=<?php echo $row_usr['item_id']; ?>&mode=edit&<?php $qstring ?>'" /> &nbsp;
 						 <input name="delbutton" type="button" class="btn" value="Delete" onclick="JavaScript:delChk('<?php echo $row_usr['item_id']; ?>','<?php echo $qstring ?>')" />
						
                        </td>
                      </tr>
                      <?php
					   }
					   ?>
					   
					   <tr><td colspan="8">
					   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="pagination">
                    <tr>
                      <?php
							if ($pageNumber > 1)  {
								?>
                      <td width="101" height="25" align="right" class="style1"><a href="item_list.php?page=1&max=1&<?php echo $qstring?>" class="lnk">&lt;&lt;</a></td>
                      <td width="66" align="right"><a href="item_list.php?page=<?php print($pageNumber - 1); ?>&max=1&<?php echo $qstring?>" class="lnk">&lt;</a></td>
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
                      <td width="80" height="25" align="left"><a href="item_list.php?page=<?php print($pageNumber + 1); ?>&max=1&<?php echo $qstring ?>" class="lnk">&gt;</a></td>
                      <td width="46" align="left"><a href="item_list.php?page=<?php print($pages); ?>&max=1&<?php echo $qstring?>" class="lnk">&gt;&gt;</a></td>
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
					   
 					   <?php
					   }
					   }
					  }else{
					  ?>
					   <tr>
                        <td colspan="8" align="center">No Record Present</td>
                        
                      </tr>
					  <?php
					  }
					   
					   ?>
                      <tr>
                         <td colspan="8" align="center">&nbsp;</td>
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
