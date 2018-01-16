<?php
	//error_reporting(E_ALL);
	error_reporting(0);
	ob_start();
	session_start();

	if(!isset($_SESSION["login"])){
	header("location:./login.php");
	exit();

	}
	 $recsOnPage=20;
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
    <script type="text/javascript" src="../js/jquery.min.1.4.4.js"></script>
    <script type="text/javascript" src="../js/jquery.autocomplete.js"></script>
    <link rel="stylesheet" href="./css/jquery.autocomplete.css" type="text/css" />
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
      <td width="15%" align="center" class="text07">&nbsp;</td>
      <td width="20%" align="center" class="text02"><?=$msg?>&nbsp;</td>
      <? unset($msg); ?>
      <td width="25%" align="center" class="text07">&nbsp;</td>
      <td width="33%" align="center" class="text07">&nbsp;</td>
	   
    </tr>
	
 	<tr>
      <td width="15%" align="right" class="text07">Item Type:</td>
      <td align="left" class="text02">
		  <select name="item_type" id="item_type" style="width:150px;" multiple="multiple" size="5">
				<option value="" selected>--- All ---</option>
				<option value="Raw Material" <?php if($_REQUEST['item_type']=="Raw Material"){ echo 'Selected'; } ?>>Raw Material</option>
				<option value="Work-In-Progress" <?php if($_REQUEST['item_type']=="Work-In-Progress"){ echo 'Selected'; } ?>>Work-In-Progress</option>
				<option value="Finished Goods"<?php if($_REQUEST['item_type']=="Finished Goods"){ echo 'Selected'; } ?>>Finished Goods</option>
		   </select></td>
		  
      <td width="15%" align="right" class="text07">Item Capacity:</td>
	<td><select name="capacity_id" tabindex="6">
					  <option value="">Any/All</option>
					  <?php
					  $sql_cap="select * from capacity_master order by capacity_name";
					  $rs_cap=mysql_query($sql_cap);
 					  while($row_cap=mysql_fetch_array($rs_cap)){
					  ?>
					  <option value="<?=$row_cap["capacity_id"]?>" <? if( $_REQUEST["capacity_id"]==$row_cap["capacity_id"]) echo "selected";?>><? echo $row_cap["capacity_name"];?></option>
					  <? } ?>
					  </select></td>
	   
    </tr>
	<tr>
	<td width="15%" align="right" class="text07">Item Name:</td>
	<td><input type="text" name="item_name"  id="srchAjax" style="width:300px;" class="ac_input" value="<?=$_REQUEST['item_name']?>" />
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
	<td width="15%" align="right" class="text07">Item Code:</td>
	<td><input type="text" name="item_code"  id="srchAjaxId" value="<?=$_REQUEST['item_code']?>" />
    
    
     
                       <script type="text/javascript">
  function findValueId(li) {
	 // alert(li);
  	if( li == null ) return alert("No match!");

  	// if coming from an AJAX call, let's use the CityId as the value
  	if( !!li.extra ) var sValue = li.extra[0];

  	// otherwise, let's just display the value in the text box
  	else var sValue = li.selectValue;

  	//alert("The value you selected was: " + sValue);
  }

  function selectItem(li) {
    	findValueId(li);
  }

  function formatItem(row) {
    	return row[0] + " (value: " + row[1] + ")";
  }

  function lookupAjax(){
  	var oSuggest = $("#srchAjaxId")[0].autocompleter;
    oSuggest.findValueId();
  	document.frm1.submit();
	//return false;
  }

  function lookupLocal(){
    	var oSuggest = $("#srchAjaxId")[0].autocompleter;
     	oSuggest.findValueId();

    	return false;
  }
  
  
    $("#srchAjaxId").autocomplete(
      "autocomplete_item_id.php",
      {
  			delay:10,
  			minChars:2,
  			matchSubset:1,
  			matchContains:1,
  			cacheLength:10,
  			onItemSelect:selectItem,
  			onFindValue:findValueId,
  			formatItem:formatItem,
  			autoFill:true
  		}
    );
  
</script></td>
	
	</tr>
	<tr><td colspan="4" align="center"><input name="add" type="submit" class="button01" id="add" value="Search" /></td></tr>
	<tr>
      <td width="15%" align="center" class="text07">&nbsp;</td>
      <td align="center" class="text02"><?=$msg?>&nbsp;</td><? unset($msg); ?>
      <td width="25%" align="center" class="text07">&nbsp;</td>
      <td width="33%" align="center" class="text07">&nbsp;</td>
	   
    </tr>
 	 
  </table>
</form>


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
					   //print_r($_REQUEST);
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
					   $sql_usr="select count(*) from item_master $cond order by item_type,item_name ";
						 
						 
					  $rs_usr=mysql_query($sql_usr);
					  $row=mysql_fetch_row($rs_usr);
					  $nr=$row[0];
					   if ($nr > 0){
							$pages = round($nr / $recsOnPage, 0);
 							if (round($nr / $recsOnPage, 2) > $pages) $pages++;
							if ($pageNumber < 1) $pageNumber = 1;
							if ($pageNumber > $pages) $pageNumber = $pages;
							$cPage=($pageNumber-1) * $recsOnPage;
							
					  $sql_usr="select *  from item_master $cond order by item_code,item_name ";
					  $sql_usr.= "  limit $cPage,$recsOnPage ";
					  $rs_usr=mysql_query($sql_usr);
					  if(mysql_num_rows($rs_usr)>0){
					  while($row_usr=mysql_fetch_array($rs_usr)){
					  
					  ?>
                      <tr>
                        <td width="5%" align="center"><a href="./item_master.php?item_id=<?php echo $row_usr['item_id']; ?>&mode=edit&"><?php echo $row_usr['item_code']; ?></a></td>
                        <td width="35%" align="left"><?php echo $row_usr['item_name']; ?></td>
						 <td width="20%" align="left"><?php echo $row_usr['item_type']; ?></td>
						<td width="10%" align="center" nowrap="nowrap"><?php echo shw_capacity($row_usr['capacity_id'])?></td>
						<td width="10%" align="center" nowrap="nowrap"><?php echo shw_category($row_usr['category_id'])?></td>
						<td width="10%" align="center"><?php echo $row_usr['item_stock']; ?></td>
						<td width="10%" align="center" nowrap="nowrap"><?php echo shw_unit($row_usr['unit_id'])?></td>
                    </tr>
                      <?php
					   }
					   ?>
					   
					   <tr><td colspan="9">
					   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="pagination">
                    <tr>
                      <?php
							if ($pageNumber > 1)  {
								?>
                      <td width="101" height="25" align="right" class="style1"><a href="item_search_list.php?page=1&max=1<?php echo $qstring?>" class="lnk">&lt;&lt;</a></td>
                      <td width="66" align="right"><a href="item_search_list.php?page=<?php print($pageNumber - 1); ?>&max=1<?php echo $qstring?>" class="lnk">&lt;</a></td>
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
                      <td width="80" height="25" align="left"><a href="item_search_list.php?page=<?php print($pageNumber + 1); ?>&max=1<?php echo $qstring ?>" class="lnk">&gt;</a></td>
                      <td width="46" align="left"><a href="item_search_list.php?page=<?php print($pages); ?>&max=1<?php echo $qstring?>" class="lnk">&gt;&gt;</a></td>
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
					   ?>
					   
					   <tr>
                        <td colspan="9" align="right"><input type="button" value="Print" onclick="JavaScript:window.open('./item_list_print.php?item_type=<?=$_REQUEST["item_type"]?><?php echo $qstring?>');" /> </td>
                        
                      </tr>
					   
					   <?
					   }
					  }else{
					  ?>
					   <tr>
                        <td colspan="9" align="center">No Record Present</td>
                        
                      </tr>
					  <?php
					  }
					   
					   ?>
                      <tr>
                         <td colspan="9" align="center">&nbsp;</td>
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
