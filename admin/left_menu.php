 <table width="190" border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
<tr><td colspan="2"><hr size="1px" width="100%" /></td></tr>
		  <tr>
            <td colspan="2" class="header"><strong>Master</strong></td>
</tr>
          <tr><td colspan="2"><hr size="1px" width="100%" /></td></tr>
<tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./user_list.php" >User Master</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./unit_list.php">Unit Master</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./capacity_list.php">Capacity Master</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./category_list.php">Porosity Master</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./item_list.php">Item Master</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./party_list.php">Party Master</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./transporter_list.php">Transporter Master</a></td>
          </tr>
<tr><td colspan="2"><hr size="1px" width="100%" /></td></tr>
		  <tr>
            <td colspan="2" class="header"><strong>Transaction</strong></td>
  </tr>
<tr><td colspan="2"><hr size="1px" width="100%" /></td></tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./item_requisition_list.php">Item Requisition</a></td>
          </tr>
         <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./wastage_list.php">Wastage / Damage</a></td>
          </tr> 
		 <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./purchase_list.php">Purchase</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./purchase_return_list.php">Purchase Return</a></td>
          </tr>
		  <?php
		  if($_SESSION['fin_year']>='2018'){
			  $res = 'style="display:none;"';
			}elseif($_SESSION['fin_year']=='2017'){
			  $res='';
			  $data = 'VAT Sales';
			}else{
			  $res='';
			  $data = 'Sales';
			}
		  ?>
		  <tr <?php echo $res;?>>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./sales_list_vat.php"><?php echo $data;?></a></td>
          </tr>
		  <?php
		  if($_SESSION['fin_year']>'2017'){
			  $res = '';
			  $data = 'Sales';
		  }elseif($_SESSION['fin_year']=='2017'){
			  $res='';
			  $data = 'GST Sales';
		  }else{
			  $res='style="display:none;"';
		  }
		  ?>
		  <tr <?php echo $res;?>>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./sales_list.php"><?php echo $data;?></a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./sales_return_list.php">Sale Return</a></td>
          </tr> 
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./receipt_payment_list.php">Receipts / Payment</a></td>
          </tr>
<tr><td colspan="2"><hr size="1px" width="100%" /></td></tr>
		  <tr>
            <td colspan="2" class="header"><strong>Report</strong></td>
  </tr>
<tr><td colspan="2"><hr size="1px" width="100%" /></td></tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./party_search_list.php">Party List</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./item_search_list.php">Item List</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./party_wise_sale.php">Party wise Sale </a></td>
          </tr>
          <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./party_wise_purchase.php">Party wise Purchase </a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./item_wise_purchase.php">Item wise Purchase </a></td>
          </tr>
		  <?php
		  if($_SESSION['fin_year']>='2018'){
			  $res = 'style="display:none;"';
			}elseif($_SESSION['fin_year']=='2017'){
			  $res='';
			  $data = 'Consolidated VAT Sales';
			}else{
			  $res='';
			  $data = 'Consolidated Sales';
			}
		  ?>
          <tr <?php echo $res;?>>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./consolidated_sale_vat.php"><?php echo $data;?> </a></td>
          </tr>
		   <?php
		   if($_SESSION['fin_year']>'2017'){
			  $res = '';
			  $data = 'Consolidated Sales';
		   }elseif($_SESSION['fin_year']=='2017'){
			  $res='';
			  $data = 'Consolidated GST Sales';
		   }else{
			  $res='style="display:none;"';
		   }
		   ?>
		  <tr <?php echo $res; ?>>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./consolidated_sale.php"><?php echo $data; ?> </a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./item_wise_sale.php">Item Wise Sale </a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./party_ledger.php">Party Ledger</a></td>
          </tr>
            <tr><td colspan="2"><hr size="1px" width="100%" /></td></tr>
		  <tr>
            <td colspan="2" class="header"><strong>Utilities</strong></td>
  		  </tr>
<tr><td colspan="2"><hr size="1px" width="100%" /></td></tr>
		  <!--<tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./#">Change Password</a></td>
          </tr>-->
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a target="_blank" href="./download.php">Backup</a></td>
          </tr>
		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./downloadlist.php">Backup List</a></td>
          </tr>
		  <!--<tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./restore_master.php">Restore</a></td>
          </tr>-->
 		  <tr>
            <td><img src="images/arrow.gif" width="8" height="7" /></td>
            <td height="20"><a href="./logout.php">Logout  </a></td>
          </tr>
		  		  
          <tr>
            <td>&nbsp;</td>
            <td height="20">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td height="20">&nbsp;</td>
          </tr>
        </table>