<?php
ob_start();
session_start();
$fin_year =$_SESSION["fin_year"];
	//error_reporting(E_ALL);
	error_reporting(0);
	
	include("../includes/config.php");include("sessiontime.php");
		include("../includes/utils.inc.php");

	//Get item list
	//$sql_im = "select im.item_sale_rate as item_sale_rate, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_id as item_id, cm.capacity_name as capacity_name from item_master im JOIN capacity_master cm on im.capacity_id = cm.capacity_id";
	$sql_im = "select im.item_sale_rate as item_sale_rate, im.unit_id as unit_id, im.item_code as item_code, im.item_name as item_name, im.item_id as item_id, cm.capacity_name as capacity_name, c.category_name as category_name from item_master im LEFT JOIN capacity_master cm on im.capacity_id = cm.capacity_id LEFT JOIN category c ON c.category_id = im.category_id";
	$tok_im = mysql_query($sql_im);
	
	
	//The unit master
	$sql_um = "select * from unit_master";
	$tok_um = mysql_query($sql_um);
	
	//The tarnsporter
	$sql_tp = "select * from transporter_master";
	$tok_tp = mysql_query($sql_tp);
	
	//The customer
	//$sql_cust = "select party_id, party_name from party_master";
	$sql_cust = "select * from party_master order by party_name";
	$tok_cust = mysql_query($sql_cust);
	//Chk for save case
	if($_POST["mode"]=="sv")
	{
		
	 
	if(isset($_POST['receipt_payment_no']))
	{
		//Check if this bill no already exist in the system, if so redirect to existing bill
		$sql_chk_old = "select * from receipt_payment where receipt_payment_no = '" . trim($_POST['receipt_payment_no']) . "'";
		//echo $sql_chk_old;
		$tok_chk_old = mysql_query($sql_chk_old);
		if(mysql_num_rows($tok_chk_old) == 0)
		{
		$_POST['value_date']=($_POST['value_date']!="")?$_POST['value_date']:"0000-00-00";
		$_POST['cheque_date']=($_POST['cheque_date']!="")?$_POST['cheque_date']:"0000-00-00";
			//echo "In here cos : " . mysql_num_rows($tok_chk_old);
			//The main SQL
			$sql_main = "insert into receipt_payment set receipt_payment_id= '" . trim($_POST['receipt_payment_id']) . "', receipt_payment_no = '" . trim($_POST['receipt_payment_no']) . "', receipt_payment_date = '" . InSlashDate($_POST['receipt_payment_date']) . "', receipt_payment_amount = '" . trim($_POST['receipt_payment_amount']) . "', rp_status = '" .  $_POST['rp_status']  . "', party_id = '" . trim($_POST['party_id']) . "', cheque_date = '" . InSlashDate($_POST['cheque_date']) . "', transaction_mode = '" . $_POST['transaction_mode'] . "', cheque_no = '" . $_POST['cheque_no'] . "', bank_name = '" . $_POST['bank_name']  . "', value_date = '" . InSlashDate($_POST['value_date']) . "', remarks = '" . $_POST['remarks'] ."',fin_year='".$fin_year."'";
			//echo $sql_main . '<br />';
			$tok_main = mysql_query($sql_main);
			
			$receipt_payment_id = $_POST['receipt_payment_id'];
			$receipt_payment_no = $_POST['receipt_payment_no'];
			//echo '<pre>';
			//print_r($_POST); 
			//Once the main SQL is saved, the details part
			foreach($_POST['bill_no'] as $item_key => $item_val)
			{
				if(trim($item_val) != '')
				{
					//Set NULL for expected NULL's
					if(trim($_POST['bill_type'][$item_key]) == '')
						$_POST['bill_type'][$item_key] = NULL;
						
					if(trim($_POST['amt'][$item_key]) == '')
						$_POST['amt'][$item_key] = NULL;
						
					 
					
					$sql_sub = "insert into receipt_payment_details set receipt_payment_id = '" . $receipt_payment_no . "',  bill_no = '" . $_POST['bill_no'][$item_key] . "', bill_type = '" . $_POST['bill_type'][$item_key] . "', amount_paid = '" . $_POST['amt'][$item_key] . "'";
					//echo $sql_sub . '<br />';
					
					$tok_sub = mysql_query($sql_sub);
					//echo $sql_sub . '<br />';
				}
			}
			if ($_POST['rp_status']=='P'){
		$dr_cr='D';
		$nara="Being Payment # :".trim($_POST['receipt_payment_no']);
		post_ledger(trim($_POST['receipt_payment_no']),$_POST['receipt_payment_amount'],$dr_cr,$_POST['party_id'],InSlashDate($_POST['receipt_payment_date']),$nara);
		}
	else { 
		$dr_cr='C';
		$nara="Being Receipt #  :".trim($_POST['receipt_payment_no']);
		post_ledger(trim($_POST['receipt_payment_no']),$_POST['receipt_payment_amount'],$dr_cr,$_POST['party_id'],InSlashDate($_POST['receipt_payment_date']),$nara);
		}
			//Once the save is done, return to the listing page
			 
		  header("location: ./receipt_payment_list.php");
		}
		else
		{
			//Bill exists
			$msg = "<h3>Payment Receipt  with payment receipt number  exists.</h3>  ";
		}
	}
	
}
	function genBillNo($fin_year)
	{
		/**************************************/
		//FORMAT
		//ASMI/(Dynamic number)/(financial-year)
		/**************************************/
			$fy=$fin_year;
			$formated_fin_year=$fy."-".($fy+1);
		//Calculate fin-year
		//$sql_id = "select MAX(SUBSTRING_INDEX(SUBSTRING_INDEX( `bill_no` , '/', 2 ),'/',-1)) AS the_num from sales_order where bill_no LIKE '%2010-2011'";
		$sql_id = "select MAX(receipt_payment_id) AS the_num from receipt_payment where fin_year ='$fy'";
		$tok_id = mysql_query($sql_id);
		if(mysql_num_rows($tok_id) == 0)
		{
			//First ID
			$the_id = 'ASMI/VOU/1/'.$formated_fin_year;
		}
		else
		{
			//Successive ones
			$data_id = mysql_fetch_array($tok_id);
			$next_id = $data_id['the_num'] + 1;
			
			//NExt final id
			$the_id = 'ASMI/VOU/' . $next_id .'/'. $formated_fin_year;
		}
		
		return $the_id;
	}
	
	function genBillNoId($fin_year)
	{
		/**************************************/
		//FORMAT
		//ASMI/(Dynamic number)/(financial-year)
		/**************************************/
			$fy=$fin_year;
			$formated_fin_year=$fy."-".($fy+1);
		//Calculate fin-year
		//$sql_id = "select MAX(SUBSTRING_INDEX(SUBSTRING_INDEX( `bill_no` , '/', 2 ),'/',-1)) AS the_num from sales_order where bill_no LIKE '%2010-2011'";
		$sql_id = "select MAX(receipt_payment_id) AS the_num from receipt_payment where fin_year ='$fy'";
		$tok_id = mysql_query($sql_id);
		if(mysql_num_rows($tok_id) == 0)
		{
			//First ID
			$the_id = 1;
		}
		else
		{
			//Successive ones
			$data_id = mysql_fetch_assoc($tok_id);
			$next_id = $data_id['the_num'] + 1;
			
			//NExt final id
			$the_id =  $next_id ;
		}
		
		return $the_id;
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

	<script type="text/javascript"> 
	function Toggle(thediv) { 
	if(thediv=="Cash")
	document.getElementById("div1").style.display = "none";
	else 
	document.getElementById("div1").style.display = "block"; 
	} 
	</script>
	
	<script language="JavaScript" type="text/javascript">
	
	<?php
		//Lets generate the Item -> price -> unit array
		?>
		var item_arr = new Array();
		<?php
		while($res_im = mysql_fetch_assoc($tok_im))
		{
			?>
			item_arr[<?php echo $res_im['item_id'] ?>] = new Array(7);
			item_arr[<?php echo $res_im['item_id'] ?>][0] = '<?php echo ($res_im['item_sale_rate'] == "") ? 0 :  $res_im['item_sale_rate'] ?>';	//Sale rate
			item_arr[<?php echo $res_im['item_id'] ?>][1] = '<?php echo ($res_im['unit_id'] == "") ? 0 :  $res_im['unit_id'] ?>';	//Unit Id
			item_arr[<?php echo $res_im['item_id'] ?>][2] = '<?php echo $res_im['capacity_name'] ?>';	//Capacity
			item_arr[<?php echo $res_im['item_id'] ?>][3] = '<?php echo $res_im['item_code'] ?>';	//Item Code
			item_arr[<?php echo $res_im['item_id'] ?>][4] = '<?php echo str_replace("'","",$res_im['item_name']) ?>';	//Item Name
			item_arr[<?php echo $res_im['item_id'] ?>][5] = '<?php echo $res_im['item_id'] ?>';	//Item Id, itself
			item_arr[<?php echo $res_im['item_id'] ?>][6] = '<?php echo str_replace("'","",$res_im['category_name']) ?>';	//Item Id, itself
			<?php
		}
		//Reset the position for the item mysql resource
		mysql_data_seek($tok_im, 0);
		
		//Nxt the cust/party array
		?>
		var cust_arr = new Array();
		<?php
		while($res_cust = mysql_fetch_assoc($tok_cust))
		{
			?>
			cust_arr[<?php echo $res_cust['party_id'] ?>] = new Array(3);
			cust_arr[<?php echo $res_cust['party_id'] ?>][0] = <?php echo ($res_cust['party_vat_pcent'] == "") ? 0 :  $res_cust['party_vat_pcent'] ?>;	//VAT Percentage
			cust_arr[<?php echo $res_cust['party_id'] ?>][1] = <?php echo ($res_cust['party_cst_pcent'] == "") ? 0 :  $res_cust['party_cst_pcent'] ?>;	//CST Percentage
			cust_arr[<?php echo $res_cust['party_id'] ?>][2] = <?php echo ($res_cust['party_tax_deposit'] == "") ? 0 :  $res_cust['party_tax_deposit'] ?>;	//Tax Deposit Percentage
			<?php
		}
		//Reset the position for the item mysql resource
		mysql_data_seek($tok_cust, 0);
	?>
	
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		return true;
	}
	
	function setItemName(item_code_obj, row_ctr)
	{
		if(item_code_obj.value != "")
		{
			document.getElementById("item_description_" + row_ctr).value = "";
			for(var i in item_arr)
			{
				if(item_code_obj.value == item_arr[i][3])
				{
					//Matched
					document.getElementById("item_name_" + row_ctr).value = item_arr[i][4];
					document.getElementById("item_id_" + row_ctr).value = item_arr[i][5];
					document.getElementById("item_rate" + row_ctr).value = item_arr[i][0];
					document.getElementById("category" + row_ctr).value = item_arr[i][6];
					setRateAndUnit(row_ctr)
					break;
				}
			}
		}
	}
	
	function setRateAndUnit(row_ctr)
	{
		//Rate feild id
		var rate_fld_id = "item_rate" + row_ctr;
		var unit_fld_id = "item_unit" + row_ctr;
		var capacity_fld_id = "capacity" + row_ctr;
		//This can only be called for a row, when the item id is already set
		var item_id = document.getElementById("item_id_" + row_ctr).value
		//alert("Here");
		if(item_id != "")
		{
			//alert("But not here!");
			//document.getElementById(rate_fld_id).value = item_arr[item_id][0];
			
			document.getElementById(unit_fld_id).value = item_arr[item_id][1];
			document.getElementById(capacity_fld_id).value = item_arr[item_id][2];
			
			if(document.getElementById("item_qty" + row_ctr).value == '')
			{
				document.getElementById("item_qty" + row_ctr).value = "1";
				
			}
			//and thus set the total amount
			//document.getElementById("item_amount" + row_ctr).value = document.getElementById("item_qty" + row_ctr).value * item_arr[item_id][0];
			document.getElementById("item_amount" + row_ctr).value = document.getElementById("item_qty" + row_ctr).value * document.getElementById(rate_fld_id).value;
			//and the value field
			//document.getElementById("value" + row_ctr).value = document.getElementById("item_amount" + row_ctr).value;
		}
		else
		{
			document.getElementById(rate_fld_id).value = '';
			document.getElementById(unit_fld_id).value = '';
			document.getElementById(capacity_fld_id).value = '';
			document.getElementById("item_qty" + row_ctr).value = '';
			document.getElementById("item_amount" + row_ctr).value = '';
		}
		
		//Call the totals calculator
		calculateSaleTotals();
	}
	
	function calculateSaleTotals()
	{
		var gross_amnt;
		var other_additions, net_amount;
		gross_amnt = 0.0;
		for(var row_ctr = 1; row_ctr <= 60; row_ctr++)
		{
			gross_amnt = gross_amnt + Number(document.getElementById("item_amount" + row_ctr).value);
		}
		document.getElementById("sales_order_amount").value = gross_amnt;
		
		//Customer specific tasks
		var cust_id, vat_amnt, cst_amnt, tax_deposit, freight;
		cust_id = document.getElementById("party_id").value;
		//Party VAT
		vat_amnt = gross_amnt * cust_arr[cust_id][0] / 100;
		cst_amnt = gross_amnt * cust_arr[cust_id][1] / 100;
		tax_deposit = gross_amnt * cust_arr[cust_id][2] / 100;
		freight = Number(document.getElementById("freight").value);
		
		//Setvalue to respective fileds
		document.getElementById("vat").value = vat_amnt;
		document.getElementById("cst").value = cst_amnt;
		document.getElementById("tax_deposit").value = tax_deposit;
		
		//Other additions
		//other_additions = Number(document.getElementById("vat").value) + Number(document.getElementById("freight").value) + Number(document.getElementById("cst").value) + Number(document.getElementById("freight_to_pay").value) + Number(document.getElementById("freight_to_pay").value);
		other_additions = vat_amnt + cst_amnt + tax_deposit + freight;
		
		net_amount = Number(gross_amnt) + Number(other_additions) - Number(document.getElementById("discount").value);
		document.getElementById("net_amount").value = net_amount;
	}
	
	function IsNill(frm)
	{
	
		   if(frm.bill_no.value.length=="")
		   {
				   alert("Please enter Bill No");
				   frm.bill_no.focus();
				   return false;        
		   }
		   
		   if(frm.bill_date.value.length=="")
		   {
				   alert("Please enter Bill date");
				   frm.bill_date.focus();
				   return false;        
		   }
		   
		   if(frm.sales_order_id.value.length=="")
		   {
				   alert("Please enter order no");
				   frm.sales_order_id.focus();
				   return false;        
		   }
		   
		   if(frm.sales_order_date.value.length=="")
		   {
				   alert("Please enter sales order date");
				   frm.sales_order_date.focus();
				   return false;        
		   }
	
		   if(frm.challan_no.value.length=="")
		   {
				   alert("Please enter challan no");
				   frm.challan_no.focus();
				   return false;        
		   }
		   
		   if(frm.challan_date.value.length=="")
		   {
				   alert("Please enter challan date");
				   frm.challan_date.focus();
				   return false;        
		   }
		   
		   /*if(frm.r_r_cn_no.value.length=="")
		   {
				   alert("Please enter R.R/CN no");
				   frm.r_r_cn_no.focus();
				   return false;        
		   }
		   
		   if(frm.r_r_cn_date.value.length=="")
		   {
				   alert("Please enter R.R/CN date");
				   frm.r_r_cn_date.focus();
				   return false;        
		   }*/
		   
		   if(frm.transporter_id.value=="")
		   {
				   alert("Please select transporter");
				   frm.transporter_id.focus();
				   return false;        
		   }
		   
		   if(frm.party_id.value=="")
		   {
				   alert("Please select Party");
				   frm.party_id.focus();
				   return false;        
		   }
		   
		   
			if(frm.net_amount.value == "0")
			{
				alert("Please enter net Amount");
				frm.net_amount.focus();
				return false;        
			}

			if(confirm("Save and Print Bill?"))
			{
		   		return true;
			}
			else
			{
				return false;
			}
		}
		
		
		function addRemoveRow(row_id, cmd)
		{
			if(cmd == 'add')
			{
				//Disable the button layer
				document.getElementById("button_span_" + row_id).style.display = "none";
				row_id++;
				//alert(document.getElementById("div_" + row_id));
				document.getElementById("div_" + row_id).style.display = "block";
			}
			else
			{
				//alert(document.getElementById("div_" + row_id));
				document.getElementById("div_" + row_id).style.display = "none";
				//Enable the previous button layer
				row_id--;
				document.getElementById("button_span_" + row_id).style.display = "";
			}
		}
		
		function getBillDetails(md)
		{
		if(md==0)
		{
			document.frmregistration.mode.value="ref";
		}
		document.frmregistration.submit();
		}
		
		function save_bill()
		{
		document.frmregistration.mode.value="sv";
		document.frmregistration.submit();
		
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
 
if($item_id !=""){
 $sql_edit=mysql_query("SELECT * FROM item_master WHERE item_id ='$item_id '") or die(mysql_error());
	$r_edit= mysql_fetch_array($sql_edit);
	 
}	
?>
              <table width="90%" border="0" cellpadding="0" cellspacing="1" bgcolor="#999999">
                <tr>
                  <td bgcolor="#FFFFFF">
				  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="frmregistration" id="frmregistration" onsubmit="return IsNill(this)">
				   <input name="mode" type="hidden" value="<?php echo $mode ?>" />
				  <input name="item_id" type="hidden" value="<?php echo $_REQUEST["item_id"] ?>" />
				  

                   <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td height="30" colspan="3" background="images/login_bg.gif"><strong> Add/Edit Voucher </strong></td>
                      </tr>
                      <tr>
                        <td width="20%" height="30">&nbsp;</td>
                        <td width="193" height="30" align="center"><?php echo $_SESSION['err_msg'] ?>&nbsp;</td><?php echo $_SESSION['err_msg']=""; ?>						
                      </tr>
                    <tr>
                      <td>
                        <label>Voucher No.  : </label></td>
                      <td><input name="receipt_payment_no" class="input" id="receipt_payment_no" value="<?php echo genBillNo($fin_year); ?>" tabindex="1" size="30" />
					  
					  <input name="fin_year" type="hidden" id="fin_year" value="<?php echo $fin_year; ?>"  />
					  <input name="receipt_payment_id" type="hidden" id="receipt_payment_id" value="<?php echo genBillNoId($fin_year); ?>"  />
					  </td>
                    </tr>
					<tr>
                      <td>Type. :</td>
                      <td height="20">
 						<input name="rp_status" type="radio" value="P" <? if(trim($_REQUEST['rp_status'])=='P' || trim($_REQUEST['rp_status'])=='' ) echo 'checked'; ?> />Payment
						<input name="rp_status" type="radio" value="R" <? if(trim($_REQUEST['rp_status'])=='R') echo 'checked'; ?> />Receipt
					  </td>
                    </tr>
					<tr>
                      <td>Customer Name. (*):</td>
                      <td height="20">
					  	<select name="party_id" id="party_id" onchange="getBillDetails(0)">
							<option value="">---Select Customer---</option>
							<?php
							while($res_cust = mysql_fetch_assoc($tok_cust))
							{
								?>
								<option value="<?php echo $res_cust['party_id'] ?>" <?php if($_REQUEST["party_id"]==$res_cust['party_id']) echo "selected";?>><?php echo $res_cust['party_name'] ?></option>
								<?php
							}
							?>
						</select>
					  </td>
                    </tr>
					<tr>
                      <td>Voucher Date (*):</td>
                      <td valign="middle"><input name="receipt_payment_date" class="input" id="receipt_payment_date" tabindex="2" value="<?php echo date("d/m/Y");?>" size="20" readonly />&nbsp;
					  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
						var cal1x = new CalendarPopup("testdiv1");
						</SCRIPT>
						<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
						<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
					  <img src="calender/btn_dropdown.gif" alt="Select Date" width="18" height="18" onClick="displayCalendar(document.getElementById('receipt_payment_date'),'mm/dd/yyyy',this); return false;" align="absbottom" /></td>
                    </tr>
					
				<!-- Style related problem started from here -->				
					
					<tr>
                      <td valign="top">Bill Details. :</td>
                      <td height="20">
					  	<table width="100%" align="left">
							
							<?php $party_id=$_REQUEST["party_id"];
							if($party_id!="")
							{
							
							?>
							<tr>
								<td><strong>Bill No</strong></td>
								<td><strong>Date</strong></td>
 								<td><strong>Total Amount</strong></td>
								<td><strong> Amount Paid</strong></td>
								<td><strong> Amount Due</strong></td>
							</tr>
							<?php
							$tot_amt=0;
							$cond=" and (fin_year='".$fin_year."' or fin_year='".($fin_year-1)."')";
							if($_REQUEST["rp_status"]=="P"){
						$sql_ord="(select sales_return_no,sales_return_date,sales_return_amount,'SR' from sales_return where party_id='$party_id' order by sales_return_date) union (select purchase_bill_no,purchase_order_date ,net_amount,'PO'  from purchase_order where party_id='$party_id' $cond order by purchase_order_date)";
							}else if($_REQUEST["rp_status"]=="R"){
							$sql_ord="(select bill_no,bill_date,net_amount,'SO' from sales_order where party_id='$party_id' $cond order by bill_date )union (select purchase_return_no,purchase_return_date ,purchase_return_amount,'PR'  from purchase_return where party_id='$party_id'  order by purchase_return_date)";
							
							}
							//echo $sql_ord;
							$rs_ord=mysql_query($sql_ord);
							while($row_ord=mysql_fetch_array($rs_ord))
							{
							$bill_no=$row_ord[0];
							$bill_type=$row_ord[3];
							$sql_pay="select sum(amount_paid) from receipt_payment_details where bill_no='$bill_no' and bill_type='$bill_type'";
							//echo $sql_pay;
							$rs_pay=mysql_query($sql_pay);
							$paid_amt=0;
							if($row_pay=mysql_fetch_row($rs_pay)){
							$paid_amt=$row_pay[0];
							}
							$amt_due=$row_ord[2]-$paid_amt;
							$tot_amt+=$amt_due;
							if($amt_due>0){
							?>
							<tr>
								<td><input type="hidden" name="bill_no[]"   value="<?php echo $row_ord[0];?>" readonly="true" /><?php echo $row_ord[0];?></td>
								<td><input type="hidden" name="bill_type[]"  value="<?php echo $row_ord[3];?>" readonly="true" /><?php echo date('d-m-Y', strtotime($row_ord[1])) ?>
								
                                
                                </td>
								<td><?php echo $row_ord[2];?></td>
 								<td><?php echo $paid_amt;?></td>
								<td><input type="text" name="amt[]" value="<?php echo $amt_due; ?>" />
							</tr>
						
					 
					<?php
					}
							} // end of while
					} // end of party id checking
					
					?>
					
				<!-- Style related problem ended here -->	
				</table>				
					 </td>
                    </tr>
					
					
 					
					<tr>
                      <td>Mode of Operation. :</td>
                      <td height="20" nowrap="nowrap">
			<input name="transaction_mode" type="radio" value="Cash" <? if(trim($res_query['transaction_mode'])=='Cash') echo 'checked'; ?> onclick="Toggle('Cash');"  />Cash
			<input name="transaction_mode" type="radio" value="Cheque" <? if(trim($res_query['transaction_mode'])=='Cheque') echo 'checked'; ?> onclick="Toggle('Cheque');" />Cheque
  					  </td>
                    </tr>
				<tr><td colspan="2">	
					
					<div id="div1" style="display:none;">
						<table width="100%" border="0" cellspacing="0" cellpadding="5">
						<tr>
						  <td width="20%">Cheque No. : </td>
						  <td width="80%" height="20"><input name="cheque_no" class="input" id="cheque_no" value="<?php echo $r_edit["cheque_no"] ?>" tabindex="5" size="20" /></td>
						</tr>
						<tr>
						  <td>Cheque Date :</td>
						  <td height="20"><input name="cheque_date" class="input" id="cheque_date" tabindex="6" value="<?php echo $r_edit["cheque_date"]?>" size="20"  readonly />
						  &nbsp;
						  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
							var cal1x = new CalendarPopup("testdiv1");
							</SCRIPT>
							<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
							<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
						  <A HREF="#" onClick="cal1x.select(document.forms[0].cheque_date,'anchor3x','MM/dd/yyyy'); return false;" TITLE="cal1x.select(document.forms[0].cheque_date,'anchor3x','MM/dd/yyyy'); return false;" NAME="anchor3x" ID="anchor3x"><img src="images/show-calendar.gif" border="0" align="absmiddle" /></A>
						  </td>
						</tr>
						<tr>
						  <td>Bank Name. :</td>
						  <td height="20">
							<input name="bank_name" class="input" id="bank_name" tabindex="9" value="<?php echo $r_edit["bank_name"]?>" size="20" />
						  </td>
						</tr>
						<tr>
						  <td>Clearing Date :</td>
						  <td height="20"><input name="value_date" class="input" id="value_date" tabindex="6" value="<?php echo $r_edit["value_date"]?>" size="20"  readonly />
						  &nbsp;
						  <SCRIPT LANGUAGE="JavaScript" ID="jscal1x">
							var cal1x = new CalendarPopup("testdiv1");
							</SCRIPT>
							<!-- The next line prints out the source in this example page. It should not be included when you actually use the calendar popup code -->
							<SCRIPT LANGUAGE="JavaScript">writeSource("jscal1x");</SCRIPT>
						  <img src="calender/btn_dropdown.gif" alt="Select Date" width="18" height="18" onClick="displayCalendar(document.getElementById('value_date'),'mm/dd/yyyy',this); return false;" align="absbottom" />
						  </td>
						</tr>
						</table>
					</div>
					</td>
					</tr>
				 
 					<tr>
                      <td width="20%">Amount. :</td>
                      <td width="80%" height="20">
					  <?php
					  
					  if($r_edit["receipt_payment_amount"]!="")
					  $tot_amt=$r_edit["receipt_payment_amount"];
					  
					  
					  ?>
					  	<input name="receipt_payment_amount" class="input" id="receipt_payment_amount" tabindex="10" value="<?php echo $tot_amt;?>"  onkeypress="return isNumberKey(event)" size="20" />
					  </td>
                    </tr>
					
  					<tr>
                      <td>Remarks. :</td>
                      <td height="20">
					  	<textarea name="remarks" tabindex="17" cols="20" rows="3" style="width:250px;"><?=$r_edit["remarks"]?></textarea>
					  </td>
                    </tr>
					
                   	<tr>
                      <td>&nbsp;</td>
                      <td><input type="button" value="Save" onclick="JavaScript:save_bill();" /> &nbsp;<input type="button" value="Cancel" onclick="window.location.href='./receipt_payment_list.php'"></td>
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
