<?php

	include("../includes/config.php");include("sessiontime.php");
	include('convert.php');
	
	//$conversion_obj = new Convert();
	//get bill id from 
	$the_bill_no = $_REQUEST['bill_no'];
	$sql_sale = "select * from sales_order where bill_no = '" . $the_bill_no . "'";
	$tok_sale = mysql_query($sql_sale);
	$data_sale = mysql_fetch_assoc($tok_sale);
	
	$sql_cust = "select * from party_master where party_id = '" . $data_sale['party_id'] . "'";
	$tok_cust = mysql_query($sql_cust);
	$data_cust = mysql_fetch_assoc($tok_cust);
	//print_r ($data_cust);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>A.S.M.I.</title>
	<style type="text/css">
		body td
		{
			font-family:Verdana, Arial, Helvetica, sans-serif;
			font-size:11px;
		}
	</style>
	<script type="text/javascript" src="../js/prototype.js"></script>

<script type="text/javascript">
	function roundNumber(rnum, rlength) { // Arguments: number to round, number of decimal places
	  var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
	  return newnumber; // Output the result to the form field (change for your purposes)
	}
	function saveExtra()
	{
	var bill_no='<?=$_REQUEST['bill_no']?>';
	var vfreight=document.getElementById('freight').value;
	var vr_r_cn_no=document.getElementById('r_r_cn_no').value;
	var vpacking_qty=document.getElementById('packing_qty').value;
	var cgst = '<?=$data_sale['cgst']?>';
	if(cgst != '0.00'){
	var freight_cgst = roundNumber(parseFloat(vfreight)*(parseFloat(<?=$data_cust['party_cgst_pcent']?>)/100),2);
	var freight_sgst = roundNumber(parseFloat(vfreight)*(parseFloat(<?=$data_cust['party_sgst_pcent']?>)/100),2);
	var freight_igst = roundNumber(0.00,2);
	document.getElementById('freight_cgst').value=roundNumber(Math.round(freight_cgst),2);
	document.getElementById('freight_sgst').value=roundNumber(Math.round(freight_sgst),2);
	document.getElementById("net_amount").value=parseFloat(document.getElementById("net_amount").value)-parseFloat(document.getElementById("old_freight").value)-parseFloat(document.getElementById("old_freight_cgst").value)-parseFloat(document.getElementById("old_freight_sgst").value)+parseFloat(document.getElementById("freight").value)+parseFloat(freight_cgst)+parseFloat(freight_sgst)+parseFloat(freight_igst);
	document.getElementById("net_amount").value=roundNumber(Math.round(document.getElementById("net_amount").value),2);
	}else{
	var freight_cgst = roundNumber(0.00,2);
	var freight_sgst = roundNumber(0.00,2);
	var freight_igst = roundNumber(parseFloat(vfreight)*(parseFloat(<?=$data_cust['party_igst_pcent']?>)/100),2);
	//document.getElementById('freight_igst').value=roundNumber(freight_igst,2);
	document.getElementById('freight_igst').value=roundNumber(Math.round(freight_igst),2);
	document.getElementById("net_amount").value=parseFloat(document.getElementById("net_amount").value)-parseFloat(document.getElementById("old_freight").value)-parseFloat(document.getElementById("old_freight_igst").value)+parseFloat(document.getElementById("freight").value)+parseFloat(freight_cgst)+parseFloat(freight_sgst)+parseFloat(freight_igst);
	document.getElementById("net_amount").value=roundNumber(Math.round(document.getElementById("net_amount").value),2);
	}
	//document.getElementById("net_amount").value=document.getElementById("net_amount").value.toFixed(2);

	var parameterString ='r_r_cn_no='+vr_r_cn_no+'&packing_qty='+vpacking_qty+'&freight='+vfreight+'&bill_no='+bill_no+'&f_cgst='+freight_cgst+'&f_sgst='+freight_sgst+'&f_igst='+freight_igst+'&net_amount='+document.getElementById("net_amount").value; 
			//	+	'&comments='+document.getElementById("comments").value;
	var loc1='./saveextra.php';
	 var newAJAX = new Ajax.Request(loc1, { 
					method: 'get', 
					parameters: parameterString,
					onSuccess: function(transport) {
					//var notice = $('maturity_amt');
					 alert("Record Saved");
					  if(document.getElementById("amtinwords"))
						document.getElementById("amtinwords").innerHTML = transport.responseText;
						if(document.getElementById("freight").value=="0.00" || document.getElementById("freight").value=="")
						{
							if(cgst == '0.00'){
							document.getElementById("freight").style.display='none';
							/*document.getElementById("freight_sgst").style.visibility='hidden';
							document.getElementById("freight_cgst").style.visibility='hidden';*/
							document.getElementById("freight_igst").style.display='none';
							document.getElementById("fr_label").style.display='none';
							/*document.getElementById("fr_label_sgst").innerHTML='';
							document.getElementById("fr_label_cgst").innerHTML='';*/
							document.getElementById("fr_label_igst").style.display='none';
							//document.getElementById("fr_label").style.border='1px solid black';
							}
							if(cgst != '0.00'){
							document.getElementById("freight").style.display='none';
							document.getElementById("freight_sgst").style.display='none';
							document.getElementById("freight_cgst").style.display='none';
							//document.getElementById("freight_igst").style.display='none';
							document.getElementById("fr_label").style.display='none';
							document.getElementById("fr_label_sgst").style.display='none';
							document.getElementById("fr_label_cgst").style.display='none';
							//document.getElementById("fr_label_igst").style.display='none';
							//document.getElementById("fr_label").style.border='1px solid black';
							}
						}
						
						 
					 }
				}
		   );
	  }
  
</script>

<script type="text/javascript">
function roundNumber(number,decimals) {
	var newString;// The new rounded number
	decimals = Number(decimals);
	if (decimals < 1) {
		newString = (Math.round(number)).toString();
	} else {
		var numString = number.toString();
		if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
			numString += ".";// give it one at the end
		}
		var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
		var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
		var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
		if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
			if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
				while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
					if (d1 != ".") {
						cutoff -= 1;
						d1 = Number(numString.substring(cutoff,cutoff+1));
					} else {
						cutoff -= 1;
					}
				}
			}
			d1 += 1;
		} 
		if (d1 == 10) {
			numString = numString.substring(0, numString.lastIndexOf("."));
			var roundedNum = Number(numString) + 1;
			newString = roundedNum.toString() + '.';
		} else {
			newString = numString.substring(0,cutoff) + d1.toString();
		}
	}
	if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
		newString += ".";
	}
	var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;
	for(var i=0;i<decimals-decs;i++) newString += "0";
	//var newNumber = Number(newString);// make it a number if you like
	return newString; // Output the result to the form field (change for your purposes)
}

</script>

	<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body >
	<!--<table border="1" width="800" height="1080">
	<tr><td width="800" height="1080" bgcolor="#99CC99">H</td></tr>
	</table>-->
	
			<?php bill_header($data_sale,1); ?>
			
				
					<!-- Dynamic Row START-->
					<?php
					sub_head();
					//Let's get the item relationship
					$sql_item = "select * from sales_order_details where sales_order_id = '" . $data_sale['bill_no'] . "'  order by sales_order_detail_id";
					$tok_item = mysql_query($sql_item);
					$sql_count="SELECT count(sales_order_detail_id) from sales_order_details where sales_order_id = '" . $data_sale['bill_no'] . "'";
					$tot_row_rs=mysql_query($sql_count);
					$tot_row_row=mysql_fetch_array($tot_row_rs);
					$tot_row=$tot_row_row[0];
					$row_ctr = 0;
					$set_newPage=0;
					$page_count=1;
					// if(($tot_row/10) > 0) $rw_height="48";
					//else
					 if($tot_row>=7) $rw_height="52";
					else $rw_height="38";
					while($data_item = mysql_fetch_assoc($tok_item))
					{
						
						//The item name etc
						$sql_details = "select * from item_master where item_id = '" . $data_item['item_id'] . "'";
						$tok_details = mysql_query($sql_details);
						$data_details = mysql_fetch_assoc($tok_details);
						
						//The unit etc
						//$sql_unit = "select unit_name from unit_master where item_id = '" . $data_details['item_unit'] . "'";
						$sql_unit = "select unit_name from unit_master where unit_id = '" . $data_details['unit_id'] . "'";
						$tok_unit = mysql_query($sql_unit);
						$data_unit = mysql_fetch_assoc($tok_unit);
						if( $tot_row>10){
						if($page_count==1 && $tot_row>10)
						$rw_height="48";
						else
						$rw_height="55";
						}
						?>
						<tr height="<?php echo $rw_height;?>">
							<td><?php echo ($row_ctr+1) ?></td>
							<td><?php echo $data_details['item_name'] . "<br /><i>" . $data_item['item_decription'] . '</i>' ?></td>
							<td align="center"><?php echo $data_details['item_hsn'] ?>&nbsp;</td>
							<td align="center"><?php echo $data_item['category'] ?>&nbsp;</td>
							<td align="right"><?php echo $data_item['capacity'] ?>&nbsp;</td>
							<?php
								//Fecth unit
								$sql_unit = "select unit_name from unit_master where unit_id = '" . $data_item['item_unit'] . "'";
								$tok_unit = mysql_query($sql_unit);
								$data_unit = mysql_fetch_assoc($tok_unit);
							?>
							<td align="right"><?php echo $data_item['item_qty'] . ' ' . $data_unit['unit_name']; ?></td>
							<td align="right"><?php echo $data_item['item_rate'] ?></td>
							<td align="right"><?php echo $data_item['item_amount'] ?></td>
						</tr>
						<?php
						$row_ctr++;
						if($row_ctr%10==0){
						$page_count++;
					      echo "</table></td></tr>";
						echo "<tr><td>";
 						bill_footer(0);
						echo "</td></tr>";
						bill_header($data_sale,$page_count);
						sub_head();
						}
					}
					?>
					<!-- Fill Up vacat rows for the sake of bill symetry -->
					<?php
					
					//$sql_cust = "select * from party_master where party_id = '" . $data_sale['party_id'] . "'";
					//$tok_cust = mysql_query($sql_cust);
					//$data_cust = mysql_fetch_assoc($tok_cust);
					$rw=$row_ctr%10;
					//echo $rw;
					if($rw>6) {
					for($vac_row =$rw ; $vac_row <9; $vac_row++)
					{
						?>
						<tr height="35">
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<?php
					}
					$page_count++;
						echo "</table></td></tr>";
 						bill_footer(0);
						
						bill_header($data_sale,$page_count);
						sub_head();
						for($vac_row =1 ; $vac_row <9; $vac_row++)
					{
						?>
						<tr height="35">
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<?php
					}
					
					}else{
					for($vac_row =$rw ; $vac_row <= 6; $vac_row++)
					{
						?>
						<tr height="30">
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<?php
					}
					}
					echo "</table></td></tr>";
					?>
					<tr><td><table border="1px solid black;" width="100%">
					<!-- Dynamic Row END -->
					<tr height="20px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<!--<td colspan="4" width="80%">&nbsp;</td>-->
						<td colspan="6" width="90%" align="right">Total Amount Before Tax</td>
						<td align="right">
						<?php 
						//echo round($data_sale['sales_order_amount'], 2); 
						echo number_format(round($data_sale['sales_order_amount'],0),2,'.','');
						?></td>
					</tr>
					<?php
					 if($data_sale['trade_discount_rate'] != "" and $data_sale['trade_discount_rate'] != 0)
 					 {
 					?>
					<tr>
						<td colspan="6" align="right">
						Trade Discount <?php echo $data_sale['trade_discount_rate']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['cst'], 2); 
							echo number_format(round($data_sale['less_trade_discount'],0),2,'.','');
							//$add_on_amnt = $add_on_amnt + $data_sale['less_trade_discount'];
							?>
						</td>
					</tr>
					
					<?php
				 	}
					/*if($data_cust['party_cst_pcent'] != "" and $data_cust['party_cst_pcent'] != 0)
					//if($data_sale['cgst'] != 0.00)
 					{
 					?>
					<tr>
						<td colspan="6" align="right">
						ADD CGST @<?php echo $data_cust['party_cst_pcent']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['cst'], 2); 
							echo number_format(round($data_sale['cst'],0),2,'.','');
							$add_on_amnt = $add_on_amnt + $data_sale['cgst'];
							?>
						</td>
					</tr>
					<tr height="20px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<td colspan="5">&nbsp;</td>
						<td align="right">Total</td>
						<td align="right">
						<?php 
						$add_on_amnt = $add_on_amnt + $data_sale['sales_order_amount'];
					    //echo round($add_on_amnt);
						//echo str_pad($add_on_amnt,2,"00",STR_PAD_RIGHT);
					    echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>
					<?php
					}*/
					//if($data_cust['party_cst_pcent'] != "" and $data_cust['party_cst_pcent'] != 0)
					if($data_sale['cgst'] != 0.00)
 					{
 					?>
					<tr>
						<td colspan="6" align="right">
						ADD CGST @<?php echo $data_cust['party_cgst_pcent']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['cst'], 2); 
							echo number_format(round($data_sale['cgst'],0),2,'.','');
							$add_on_amnt = $add_on_amnt + $data_sale['cgst'];
							?>
						</td>
					</tr>
					<tr>
						<td colspan="6" align="right">
						ADD SGST @<?php echo $data_cust['party_sgst_pcent']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['cst'], 2); 
							echo number_format(round($data_sale['sgst'],0),2,'.','');
							$add_on_amnt = $add_on_amnt + $data_sale['sgst'];
							?>
						</td>
					</tr>
					<tr height="20px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<!--<td colspan="4">&nbsp;</td>-->
						<td colspan="6" width="90%" align="right">Total Amount After Tax</td>
						<td align="right">
						<?php 
						$add_on_amnt = $add_on_amnt + $data_sale['sales_order_amount'];
					    //echo round($add_on_amnt);
						//echo str_pad($add_on_amnt,2,"00",STR_PAD_RIGHT);
					    echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>
					<?php
					}
					//if($data_cust['party_vat_pcent'] != "" and $data_cust['party_vat_pcent'] != 0)
					if($data_sale['igst'] != 0.00)
					{
					?>
					<tr>
						<td colspan="6" align="right">
						ADD IGST @<?php echo $data_cust['party_igst_pcent']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['vat'], 2); 
							echo number_format(round($data_sale['igst'],0),2,'.','');
							$add_on_amnt = $add_on_amnt + $data_sale['igst'];
							?>
						</td>
					</tr>
					<tr height="20px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<!--<td colspan="4">&nbsp;</td>-->
						<td colspan="6" width="90%" align="right">Total Amount After Tax</td>
						<td align="right">
						<?php 
						$add_on_amnt = $add_on_amnt + $data_sale['sales_order_amount'];
						//echo round($add_on_amnt);
						echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>
					<?php
					}
					
					if($data_cust['party_tax_deposit'] != "" and $data_cust['party_tax_deposit'] != 0)
					{
					?>
					<tr>
						<td colspan="6" align="right">
						ADD TAX DEPOSITED @<?php echo $data_cust['party_tax_deposit']; ?>%
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['tax_deposit'], 2); 
							echo number_format(round($data_sale['tax_deposit'],0),2,'.','');
							$add_on_amnt = $add_on_amnt + $data_sale['tax_deposit'];
							?>
						</td>
					</tr>
					<tr height="20px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<td colspan="5">&nbsp;</td>
						<td align="right">Total</td>
						<td align="right">
						<?php 
						//$add_on_amnt = $add_on_amnt + $data_sale['sales_order_amount'];
						//echo round($add_on_amnt);
						echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>
					<?php
					}
					
					if($data_sale['discount'] != "" and $data_sale['discount'] != 0)
					{
					?>
					<tr>
						<td colspan="6" align="right">
						LESS DISCOUNT
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['discount'], 2); 
							echo number_format(round($data_sale['discount'],0),2,'.','');
							$add_on_amnt = $add_on_amnt - $data_sale['discount'];
							?>
						</td>
					</tr>
					<tr height="20px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<td colspan="5">&nbsp;</td>
						<td align="right">Total</td>
						<td align="right">
						<?php 
						//$data_sale = $data_sale['sales_order_amount'] - $add_on_amnt;
						//echo round($add_on_amnt);
						echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>
					<?php
					}
					
					 
					?>
					<tr>
						<td colspan="6" align="right">
						<span id="fr_label">ADD FREIGHT</span>
						</td>
						<td align="right">
							<?php 
							//echo round($data_sale['freight'], 2); 
							//echo number_format(round($data_sale['freight'],0),2,'.',',');
							 $add_on_amnt = $add_on_amnt + $data_sale['freight'];
							?>
							
							<input name="freight" class="input" id="freight" tabindex="12"  size="10" value="<?php echo  $data_sale['freight'];?>"     style="border:none;text-align:right" />
							<input name="old_freight" type="hidden" id="old_freight" value="<?php echo  $data_sale['freight'];?>"    />
						</td>
					</tr>
					<?php
					 if($data_sale['cgst'] != 0.00)
 					{
 					?>
					<tr>
						<td colspan="6" align="right">
						<span id="fr_label_cgst">ADD CGST @<?php echo $data_cust['party_cgst_pcent']; ?>% On Freight</span>
						</td>
						<td align="right">
							<input name="freight_cgst" class="input" id="freight_cgst" tabindex="13"  size="10" value="<?php  echo number_format(round($data_sale['freight_cgst'],0),2,'.','');?>"     style="border:none;text-align:right" readonly />
							<input name="old_freight_cgst" type="hidden" id="old_freight_cgst" value="<?php echo  $data_sale['freight_cgst'];?>"/>
						</td>
					</tr>
					<tr>
						<td colspan="6" align="right">
						<span id="fr_label_sgst">ADD SGST @<?php echo $data_cust['party_sgst_pcent']; ?>% On Freight</span>
						</td>
						<td align="right">
							<input name="freight_sgst" class="input" id="freight_sgst" tabindex="14"  size="10" value="<?php  echo number_format(round($data_sale['freight_sgst'],0),2,'.','');?>"     style="border:none;text-align:right" readonly />
							<input name="old_freight_sgst" type="hidden" id="old_freight_sgst" value="<?php echo  $data_sale['freight_sgst'];?>"/>
						</td>
					</tr>
					<!--<tr height="20px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<!--<td colspan="4">&nbsp;</td>-->
						<!--<td colspan="6" width="90%" align="right">Total Amount After Tax On Freight</td>
						<td align="right">
						<?php 
						//$add_on_amnt = $add_on_amnt + $data_sale['sales_order_amount'];
					    //echo round($add_on_amnt);
						//echo str_pad($add_on_amnt,2,"00",STR_PAD_RIGHT);
					    echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>-->
					<?php
					}
					//if($data_cust['party_vat_pcent'] != "" and $data_cust['party_vat_pcent'] != 0)
					if($data_sale['igst'] != 0.00)
					{
					?>
					<tr>
						<td colspan="6" align="right">
						<span id="fr_label_igst">ADD IGST @<?php echo $data_cust['party_igst_pcent']; ?>% On Freight</span>
						</td>
						<td align="right">
							<input name="freight_igst" class="input" id="freight_igst" tabindex="15"  size="10" value="<?php  echo number_format(round($data_sale['freight_igst'],0),2,'.','');?>"     style="border:none;text-align:right" readonly />
							<input name="old_freight_igst" type="hidden" id="old_freight_igst" value="<?php echo  $data_sale['freight_igst'];?>"/>
						</td>
					</tr>
					<!--<tr height="20px;">
						<!--<td colspan="5">AMOUNT</td>-->
						<!--<td colspan="4">&nbsp;</td>-->
						<!--<td colspan="6" width="90%" align="right">Total Amount After Tax On Freight</td>
						<td align="right">
						<?php 
						//$add_on_amnt = $add_on_amnt + $data_sale['sales_order_amount'];
						//echo round($add_on_amnt);
						echo number_format(round($add_on_amnt,0),2,'.','');
						?>
						</td>
					</tr>-->
					<?php
					}
					?>
					<!-- Dynamic Row END -->
					
					<tr height="20px;">
						<td colspan="5">Rupees <span id="amtinwords"> <?php $final_amount=round($add_on_amnt , 0);
					  $conversion_obj = new Convert(round($data_sale['net_amount'],0), $currency="Only"); $conversion_obj->display(); ?></span>.</td>
						<td align="right">Net Total</td>
						<td align="right"><input name="net_amount" class="input"  id="net_amount" tabindex="112"  size="10" value="<?php echo number_format(round($data_sale['net_amount'],0),2,'.','');?>"  onChange="calculateSaleTotals();" style="border:none;text-align:right" readonly="readonly" /> </td>
					</tr>
					
					<?php
					
					if($data_sale['remarks'] != NULL and $data_sale['remarks'] != "")
					{
						?>
						<tr height="20px;">
							<td colspan="7">REMARKS: <?php echo $data_sale['remarks'] ?></td>	
						</tr>
						<?php
					}
					?>
					
				</table>
			</td>
		</tr>
		<tr><td valign="bottom">
		<?php bill_footer(1); ?>
		</td>
		</tr>
		
		
	
</body>
</html>
<?php 
function bill_footer($prn)
{
$strout= '
				<table width="100%" border="1px solid black;">
					<tr>
						<td width="50%" valign="bottom">
							<ol>
								<li>No Claims for shortage, damage, breakage etc will be entertained after the goods leaves our premises, if not insured.</li>
								<li>Goods once sold can not be taken back.</li>
								<li>All disputes subject to Kolkata jurisdiction only.</li>
								<li>Interest @18% will be charged if payment is not made within 30 days.</li>
							</ol>
						</td>
						<td align="center">
							E.& O.E.<br />
							<span><i>For</i> <strong>Associated Scientific Mfg. Industries</strong>
							<br />
							<br /><br /><br /><br />
							<strong>AUTHORISED SIGNATORY</strong></span>';
							if($prn==1){
							$strout.= '
							<div style="border:0px!important;position:relative">
		 <input type="button" value="Print" id="Print" onClick="javascript:saveExtra();this.style.display=\'none\';document.getElementById(\'bck\').style.display=\'none\';window.print();">&nbsp;<input id="bck" type="button" value="Back to Sales" onClick="javascript:window.location.href=\'./sales_list.php\'"> 
		 </div>';
		 }
						$strout.= '</td>
					</tr>
				</table>
			</td>
		</tr></table>';
		echo $strout;
}
function bill_header($data_sale,$pg)
{
$strout='<table border="1px solid black" width="100%" height="600">

		 	
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="35%">Page :'.$pg.'&nbsp; &nbsp;</td>
						<td align="center">
							<span style="font-size:20px; font-weight:bold;">TAX INVOICE</span>
						</td>
						<td width="35%" align="right">Original/Duplicate/Triplicate</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td align="center">
							<span style="font-size:27px; font-weight:bold;">Associated Scientific Mfg. Industries</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table border="1px solid black" width="100%">
					<tr>
						<td width="27%">
							<table width="100%">
								<tr>
									<td>GSTIN NO. : 19AFMPB6687D1Z6</td>
								</tr>
								<!--<tr>
									<td>C.S.T.NO. : 19301344211</td>
								</tr>-->
								<tr>
									<td>PAN No.&nbsp;&nbsp;&nbsp; : AFMPB6687D</td>
								</tr>
								<!--<tr>
									<td>E.M.NO.&nbsp;&nbsp;&nbsp; : 190171105238</td>
								</tr>-->
							</table>
						</td>
						<td width="73%">
							<table width="100%">
								<tr>
									<td>
									<!--	<h1 style="color:#336699">Associated Scientific Mfg. Industries</h1>	-->
										<span>Spl. In: Sintered Glass Ware &amp; Micro Filtration Assembly</span><br />
										10, Bhairab Mukherjee Lane, Kolkata - 700 004 (Opp. R.G. Kar Hospital Emergency Building)<br />
										West Bengal, State Code : <b>19 (WB)</b><br/>
										Phone : 033-2554-8602, 2543-1010 &amp; Mobile: +91 9830606770<br />
										E-mail: info@sinteredglassware.com / associatedscientificmfgind@yahoo.co.in<br />
										Web Site : www.sinteredglassware.com
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table border="1px solid black" width="100%">
					<tr height="20px;">
						<td width="50%">TAX INVOICE No. '.$data_sale['bill_no'].'</td>
						<td width="15%">DATE</td>
						<td width="15%">'.date('d-m-Y', strtotime($data_sale['bill_date'])).'</td>
 						
						<td width="10%" align="right">Pkg(s)</td>
						<td valign="top"><input name="packing_qty" class="input" id="packing_qty" tabindex="7" value="'.$data_sale["packing_qty"].'" size="20"/></td>
						
						
						
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="49%" valign="top" height="100%">
							<table width="100%" height="100%">';
								 
									$sql_cust = "select * from party_master where party_id = '" . $data_sale['party_id'] . "'";
									$tok_cust = mysql_query($sql_cust);
									$data_cust = mysql_fetch_assoc($tok_cust);
								 
									$sql_cust1 = "select * from states where id = '" . $data_cust['party_state'] . "'";
									$tok_cust1 = mysql_query($sql_cust1);
									$data_cust1 = mysql_fetch_assoc($tok_cust1);
									
									$sql_cust2 = "select * from cities where id = '" . $data_cust['party_city'] . "'";
									$tok_cust2 = mysql_query($sql_cust2);
									$data_cust2 = mysql_fetch_assoc($tok_cust2);
									
								$strout.= '<tr>
									<td height="100%">
										To<br />'.
										$data_cust['party_name'].'
										<br />
										'. $data_cust['party_address'].', '.strtoupper($data_cust1['name']).', '.strtoupper($data_cust2['name']).' - '.$data_cust['party_pin'].'
										<br />
										'.$data_cust1["name"].', State Code: <b>' . $data_cust1['state_code'].' ('.$data_cust1['state_abbr'].')'. 
										'</b><br />';
											if(trim($data_cust['party_email']) != '')
											{
												$strout.= 'Email-Id.: ' . $data_cust['party_email'] . '<br />';
											}
											
											if(trim($data_cust['party_phone']) != '')
											{
												$strout.= 'Phone No.: ' . $data_cust['party_phone'] . '<br />';
											}
											
											if(trim($data_cust['party_mobile']) != '')
											{
												$strout.= 'Mobile No.: ' . $data_cust['party_mobile']. '<br />';
											}
										 
											if(trim($data_cust['party_gst_no']) != '')
											{
												
												$strout.= 'GSTIN No.&nbsp;&nbsp;&nbsp;: ' . $data_cust['party_gst_no'];
												$strout.= '<br />';
											}
										 
											/*if(trim($data_cust['party_cst_no']) != '')
											{
												$strout.= '<br />';
												$strout.= 'CST No.&nbsp;&nbsp;&nbsp;: ' . $data_cust['party_cst_no'];
											}*/
										 
									$strout.= '</td>
								</tr>
							</table>
						</td>
						<td style="padding-left:5px;">
							<table width="100%">
								<tr>
									<td>
										<table border="1px solid black" width="100%" height="100%">
											<tr>
												<td width="25%">ORDER NO.</td>
												<td width="42%">'.$data_sale['sales_order_id'].'</td>
												<td width="13%">DATE</td>
												<td width="20%">'.date('d-m-Y', strtotime($data_sale['sales_order_date'])).'</td>
											</tr>
											<tr>
												<td width="25%">CHALLAN NO.</td>
												<td>'.$data_sale['challan_no'].'</td>
												<td width="13%">DATE</td>
												<td>'.date('d-m-Y', strtotime($data_sale['challan_date'])).'</td>
											</tr>
											<tr>
												<td width="25%">R.R./CN NO.</td>
												<td valign="top"><input name="r_r_cn_no" class="input" id="r_r_cn_no" tabindex="7" value="'.$data_sale["r_r_cn_no"].'" size="20"/></td>
												<td width="13%">DATE</td>
												<td valign="top">'.date('d-m-Y', strtotime($data_sale['r_r_cn_date'])).'</td>
											</tr>
											<tr>
												<td width="25%">TRANSPORTER</td>
												<td valign="top" colspan="3">';
												$sql_tra = "select transporter_name from transporter_master where transporter_id = '" . $data_sale['transporter_id'] . "'";
														$tok_tra = mysql_query($sql_tra);
														$data_tra = mysql_fetch_assoc($tok_tra);
														$strout.= $data_tra['transporter_name'];
													$strout.= '</td>
											</tr>
										</table>
									</td>
											</tr>
										</table>
										</td>
											</tr>
										</table>
										</td>
											</tr>
											 
			';
					
					echo $strout;

}

function sub_head()
{

		$strout='
					<tr>
			<td>
				<table border="1px solid black" width="100%"><tr>
						<td width="5%">SL. NO.</td>
						<td width="60%">DESCRIPTION OF MATERIALS</td>
						<td width="7%" align="center">HSN CODE</td>
						<td width="5%" align="center">POROSITY</td>
						<td width="5%" align="center">CAPACITY</td>
						<td width="8%" align="center">QUANTITY</td>
						<td width="5%" align="center">RATE PER UNIT</td>
						<td width="5%" align="center">AMOUNT</td>
					</tr>';
					echo $strout;
}
?>
