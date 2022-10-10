<?php  
 //$connect = mysqli_connect("localhost", "root", "", "testing");
//require('includes/application_top.php'); //for zen_href_link
//require('includes/classes/currencies.php'); 
require('includes/classes/currencies.php'); 
?>
<!-- for date time calendar https://www.jqueryscript.net/time-clock/Easy-Customizable-Date-Time-Picker-Plugin-For-jQuery-jSunPicker.html -->

<!-- <script src="//code.jquery.com/jquery-1.12.1.min.js"></script> -->
<!-- <script src="//code.jquery.com/jquery-2.1.0.min.js"></script> alread included in admin/includes/headerDOTphp -->
<link href="includes/jSunPicker.v1.css" rel="stylesheet"></script>
<script src="includes/jSunPicker.v1.min.js"></script>
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script> -->
<?php 
function myprint_r($arr){
	echo '<pre>';
		print_r($arr);
	echo '</pre>';	
}
global $wpdb;

 $output = '';
 $k = 10000; //created to differentiate rowIDs over script
 $sql = 'SELECT * ' 
		. ' from wp1m_invoice i'  
		. ' order by i.invoice_purchase_date DESC; '; 
 $invoice_table_result = $wpdb->get_results(
									"'SELECT * ' 
									. ' from wpm1_invoice i'  
									. ' order by i.invoice_purchase_date DESC; ';"
								);
// print_r($invoice_table_result);





// SELECT ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count
// FROM plankeye_woo.wp1m_posts
// ;


// SELECT p.products_pebs_invoice, sum(p.products_cost * p.products_quantity) as instocksum, sum(p.products_cost *op.products_quantity) as invoicecogs'
// 							. ' from products p'
// 							. ' left join orders_products op on p.products_id = op.products_id'							
// 							. ' WHERE op.products_id = p.products_id'
// 							. ' or '
// 							. ' p.products_quantity>0'
// 							. ' GROUP BY p.products_pebs_invoice'
// 							. ' ORDER BY SUBSTRING(`products_pebs_invoice`, 5, 2) DESC, SUBSTRING(`products_pebs_invoice`, 1, 2) DESC, SUBSTRING(`products_pebs_invoice`, 3, 2) DESC;
							
						
// --select post_title and product cost and invoice namea and post status

// SELECT p.ID, p.post_date, p.post_title, p.post_status, pm.meta_value, pm.meta_key
// FROM plankeye_woo.wp1m_posts p
// join 
// wp1m_postmeta pm on
// p.ID = pm.post_id 
// where 
// pm.meta_key='invoice' or pm.meta_key = 'cost'
// ;


// SELECT p.ID, p.post_date, p.post_title, p.post_status, pm.meta_value, pm.meta_key
// FROM plankeye_woo.wp1m_posts p
// join 
// wp1m_postmeta pm on
// p.ID = pm.post_id 
// where 
// pm.meta_key='invoice' or pm.meta_key = 'cost'












//JOIN PRODUCTS AND ORDERS_PRODUCTS TABLE 
	$invoice_cogs = $wpdb->get_results(
							"'SELECT p.products_pebs_invoice, sum(p.products_cost * p.products_quantity) as instocksum, sum(p.products_cost *op.products_quantity) as invoicecogs'
							. ' from products p'
							. ' left join orders_products op on p.products_id = op.products_id'							
							. ' WHERE op.products_id = p.products_id'
							. ' or '
							. ' p.products_quantity>0'
							. ' GROUP BY p.products_pebs_invoice'
							. ' ORDER BY SUBSTRING(`products_pebs_invoice`, 5, 2) DESC, SUBSTRING(`products_pebs_invoice`, 1, 2) DESC, SUBSTRING(`products_pebs_invoice`, 3, 2) DESC;';"
						);

//calculate hasQuantitySum which is the sum of things with a quantity > 0 * cost
	$invoice_hasQuanSum_query = 'SELECT products_pebs_invoice, sum(p.products_quantity * p.products_cost) as hasQuanSum
									FROM products p
									WHERE p.products_quantity > 0
									GROUP BY products_pebs_invoice';
									
		

//COGS RESULT
  $invoices_cogs = mysqli_query($connect, $invoice_cogs_query);

  $invoices_hasQuanSum_results = $db->Execute($invoice_hasQuanSum_query);
  $invoices_cogs_for_list = mysqli_query($connect, $invoice_cogs_query);
 
 //BUILD TABLE HEADERS
 $output .= '  
      <!--<div class="table-responsive">  -->
      <div class="table-nonresponsive">  
           <!--<table class="getit table table-bordered table-striped sticky-header floatThead-table">-->
           <table class="table table-striped sticky-header getit table-bordered">
			  <thead>
                <tr>  					
                     <th id="1" class="imported">Imp-ort-ed		</th>
                     <th id="2" width="10px">Id					</th>
                     <th id="3" width="10px">Invoice Date			</th>
                     <th id="4" width="25px">Invoice Name			</th>  
                     <th id="5" width="25px">Comments				</th>  
                     <th id="6" width="25px">Invoice Dist			</th>  
                     <th id="7" width="10px>Purchase Total			</th>  
                     <th id="8" width="10px">Actual Total			</th>  
                     <th id="9" width="10px">Shipping				</th>  
                     <th id="10" width="10px">Payment Method		</th>  
                     <th id="11" width="10px">Has Quan Sum			</th>  
                     <th id="12" width="10px">COGS					</th>  
                     <th id="13" width="10px">Calc\'d Invoice Total	</th>  
                     <th id="14" width="10px">Delete<br />or Add	</th>  
                </tr>
			  </thead>';
				
//BUILD INPUT FIELDS FOR ADDING TO INVOICE TABLE
	$output .= '
		<tr>  
			<td id="1" ></td>
			<td id="2" >' . $k . '</td>
			<td id="3" ></td>
			<td class="4" id="invoice_name_' . $k . '" data-nid1="'. $k . '" contenteditable>Type_Future_Invoice_To_Import_In_HERE</td>  
			<td class="5" id="invoice_comments_' . $k . '" data-nid1="'. $k . '" contenteditable>This Comment does work; single quotes do not work</td>  
			<td id="5" id="invoice_dist_' . $k . '" data-nid1="'. $k . '" contenteditable></td>
			<td id="6" ></td>
			<td id="7" ></td>
			<td id="8" ></td>
			<td id="9" ></td>
			<td id="10" ></td>
			<td id="11" ></td>
			<td class="12"><button type="button" name="btn_add" id="btn_add" data-nid1="'. $k . '" class="btn btn-xs btn-success">+</button></td>  
		</tr>';
		
 $rows = mysqli_num_rows($invoice_table_result);
 $rows2 = mysqli_num_rows($invoices_cogs); 
 //$invoices_hasQuanSum_results = $db->Execute($invoices_hasQuanSum); //not sure why but...
 $invoices_that_have_QuanSum_results = array();
 foreach($invoices_hasQuanSum_results as $invoices_hasQuanSum_result){
	//echo '<pre>';
	//print_r($invoices_hasQuanSum_results);
	$invoices_that_have_QuanSum_results[$invoices_hasQuanSum_result['products_pebs_invoice']]['hasQuanSum'] = $invoices_hasQuanSum_result['hasQuanSum'];
	//echo($invoices_hasQuanSum_result['hasQuanSum']). '<br/>';
	$invoices_that_have_QuanSum_results[$invoices_hasQuanSum_result->fields['products_pebs_invoice']]['hi'];
//	$invoices_that_have_QuanSum_results[$invoices_hasQuanSum_result['products_pebs_invoice']]['hasQuanSum'][$invoices_hasQuanSum_result['hasQuanSum']];
	//$invoices_hasQuanSum_results->MoveNext();
}
//echo '<pre>';
//print_r($invoices_that_have_QuanSum_results);
 $arrayOfInvoiceNames = array();
 if($rows > 0)  
{
	
//BUILD arrayOfInvoiceNames WITH INVOICE TABLE RESULT
      while($row_of_invoice_table_result = mysqli_fetch_array($invoice_table_result))  
      {
		  //$arrayOfInvoiceNames[] = trim($row_of_invoice_table_result["invoice_name"]); //for in_array
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_id'] = trim($row_of_invoice_table_result["invoice_id"]);
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_name'] = trim($row_of_invoice_table_result["invoice_name"]);
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_comments'] = trim($row_of_invoice_table_result["invoice_comments"]);
		  //$arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_purchase_date'] = trim($row_of_invoice_table_result["invoice_purchase_date"]);
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_date'] = $row_of_invoice_table_result["invoice_date"];
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_dist'] = trim($row_of_invoice_table_result["invoice_dist"]);
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_dist_order_count'] = trim($row_of_invoice_table_result["invoice_dist_order_count"]);
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_purchase_date'] = trim($row_of_invoice_table_result["invoice_purchase_date"]);
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_total'] = trim($row_of_invoice_table_result["invoice_total"]);
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_payment'] = trim($row_of_invoice_table_result["invoice_payment"]);
		  $arrayOfInvoiceNames[trim($row_of_invoice_table_result["invoice_name"])]['invoice_shipping'] = trim($row_of_invoice_table_result["invoice_shipping"]);
      }
//BUILD INVOICES_COGS_DEAL array to know what's in the system (i think this means in website not invoice table) //aka in_array a few lines below this	  
while ($array_list_of_invoices_in_products = mysqli_fetch_array($invoices_cogs_for_list, MYSQLI_ASSOC)){
	$listOfInvoicesInProducts[] = trim($array_list_of_invoices_in_products['products_pebs_invoice']);
}

//BUILD INVOICES_COGS_DEAL array to know what's in the system //aka in_array a few lines below this	  
/*while ($array_list_of_invoices_in_products = mysqli_fetch_array($invoices_cogs_for_list, MYSQLI_ASSOC)){
	$listOfInvoicesInProducts[] = trim($array_list_of_invoices_in_products['products_pebs_invoice']);
}*/

//echo $invoices_cogs_for_list;
	  
//myprint_r($listOfInvoicesInProducts);
//myprint_r($arrayOfInvoiceNames);

//add things only in invoice table not website		
foreach ($arrayOfInvoiceNames as $arrayOfInvoice => $values){
			  $rawdate = $arrayOfInvoiceNames[$arrayOfInvoice]['invoice_date'];
			  $i = $arrayOfInvoiceNames[$arrayOfInvoice]['invoice_id']; //note not k++ here
			  $date = substr($rawdate,5,2) . '-' . substr($rawdate, 8,2) . '-' . substr($rawdate, 0,4);
			  if(in_array($arrayOfInvoiceNames[$arrayOfInvoice]['invoice_name'], $listOfInvoicesInProducts) != TRUE){
				$output .= '
					<tr class="invoiceTableOnly">
						 <td class="imported">X</td>  
						 <td class="invoice_table_only_id">'. $i . '</td>  
						 <td class="invoice_date_td" data-id1="' . $i . '" contentNONeditable><input type="text" name="invoice_date"' . $rawdate . '" class="invoice_date" id="invoice_date" data-id1=" . $i . contenteditable date"></td>
						 <td class="invoice_name" data-id1="' . $i . '" contenteditable>' . $arrayOfInvoice . '</td>
						 <td class="invoice_comments" data-nid5="' . $i . '" contenteditable>' . $arrayOfInvoiceNames[$arrayOfInvoice]['invoice_comments'] . '</td>
						 <td class="invoice_dist" data-id2="' . $i . '" contentNONeditable>' . $arrayOfInvoice . '</td>
						 <td class="invoice_total" data-nid5="' . $i . '" contenteditable>' . $arrayOfInvoiceNames[$arrayOfInvoice]['invoice_total'] .'</td>
						 <td class="invoice_shipping" data-id1="' . $i . '" contenteditable>' . $arrayOfInvoiceNames[$arrayOfInvoice]['invoice_shipping'] .'</td>
						 <td class="invoice_payment" data-id1="' . $i . '" contenteditable>' . $arrayOfInvoiceNames[$arrayOfInvoice]['invoice_payment'] . '</td>
						 <td class="filler1">NEEDS IMPORTED</td>
						 <td class="filler2">N/A</td>
						 <td class="filler4"> '. $rawdate . '</td>
						 <td><button type="button" name="delete_btn" data-id3="' . $i . '" class="btn btn-xs btn-danger btn_delete">x</button></td>  
					</tr>';			  
			}			
		}
		
	  //"FOR EACH" INVOICES COGS RESULT
      while($invoices_cogs_deal = mysqli_fetch_array($invoices_cogs)) //mysqli_fetch_array returns an array of strings that corresponds to the fetched row or NULL if no more in resultset
	  {
		$currencies = new currencies(); //otherwise global scope wrong
		
		//IF EXISTS IN INVOICE TABLE AND WEBSITE
		if(isset($arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_id'])){
			$i = $arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_id'];
			$insiteSum = number_format((float)($invoices_cogs_deal['invoicecogs']+$invoices_that_have_QuanSum_results[$invoices_cogs_deal['products_pebs_invoice']]['hasQuanSum']), 2, '.', '');
			$inInvoiceTableSum = number_format((float)($arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_total']), 2, '.','');
			//$inInvoiceTableSum = number_format($arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_total'] + $arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_shipping'], 2, '.', '');
			//IF COGS_RESULT CONTAINS INVOICES NAMES FROM INVOICE TABLE
				$output .= '
				   <tr class=' . (!empty($inInvoiceTableSum) &&($insiteSum == $inInvoiceTableSum) ?
							'rowEqual' : 
							'rowNotEqual') . '>
						<td class="imported"></td>
						<td class="invoice_row" id="invoice_id" data-nid1="'. $i . '" contentNONeditable>' . $i . ' </td>
						<td class="invoice_date" id="invoice_date_' . $i . '" data-nid1="'. $i . '" contentNONeditable>20' . substr($invoices_cogs_deal['products_pebs_invoice'],4,2) . '-' . substr($invoices_cogs_deal['products_pebs_invoice'],0,2) . '-' . substr($invoices_cogs_deal['products_pebs_invoice'],2,2) . '</td>
						<td class="invoice_name" id="invoice_name_' . $i . '" data-nid1="'. $i . '" contentNONeditable>' . $invoices_cogs_deal['products_pebs_invoice'] . '</td>
						<td class="invoice_comments" id="invoice_comments_' . $i . '" data-nid5="'. $i . '" contenteditable>' . $arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_comments'] . ' ' . $insiteSum . ' vs ' . $inInvoiceTableSum . ' </td>
						<td class="invoice_dist" id="invoice_dist_' . $i . '" data-nid1="'. $i . '" contentNONeditable><a href="' . zen_href_link('category_product_listing.php', 'search=' . $invoices_cogs_deal["products_pebs_invoice"] . '&sortbyinvoice=1', 'NONSSL', true, false). '" target="_BLANK">' . $invoices_cogs_deal['products_pebs_invoice'] . '</a></td>
						<td class="invoice_total" id="invoice_total_' . $i . '" data-nid5="'. $i . '" contenteditable>' . $arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_total'] . ' </td>
						<td class="invoice_shipping" id="invoice_shipping_' . $i . '" data-nid5="'. $i . '" contenteditable>' . $arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_shipping'] . ' </td>
						<td class="invoice_payment" id="invoice_payment_' . $i . '" data-nid5="'. $i . '" contenteditable>' . $arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_payment'] . ' </td>
						<td class="invoice_hasQuansum" id="invoice_instocksum_' . $i . '" data-nid1="'. $i . '" contentNONeditable>' . number_format((float)$invoices_that_have_QuanSum_results[$invoices_cogs_deal['products_pebs_invoice']]['hasQuanSum'], 2, '.', '') . '</td>
						<td class="invoice_cogs" id="invoice_cogs_' . $i . '" data-nid1="'. $i . '" contentNONeditable>' . number_format((float)$invoices_cogs_deal['invoicecogs'], 2, '.', '') . '</td>
						<td class="invoice_calcd_total" id="invoice_cogs_' . $i . '" data-nid1="'. $i . '" contentNONeditable>' . number_format($insiteSum, 2, '.', '') . '</td>
						<td>✔</td>
					</tr>';
				}else{
					$k++;
		  		//IF COGS_RESULT DOES NOT CONTAIN INVOICES NAMES FROM INVOICE TABLE
				$output .= '
				   <tr>
						<td class="imported"></td>
						<td class="invoice_row" id="invoice_row_id" data-nid1="'. $k . '" contentNONeditable>' . $k . ' </td>
						<td class="invoice_date" id="invoice_date_' . $k . '" data-nid1="'. $k . '" contentNONeditable>20' . substr($invoices_cogs_deal['products_pebs_invoice'],4,2) . '-' . substr($invoices_cogs_deal['products_pebs_invoice'],0,2) . '-' . substr($invoices_cogs_deal['products_pebs_invoice'],2,2) . '</td>
						<td class="invoice_name" id="invoice_name_' . $k . '" data-nid1="'. $k . '" contentNONeditable>' . $invoices_cogs_deal['products_pebs_invoice'] . '</td>
						<td class="invoice_comments" id="invoice_comments_' . $k . '" data-nid1="'. $k . '" contenteditable>' . $arrayOfInvoiceNames[$invoices_cogs_deal['products_pebs_invoice']]['invoice_comments'] . ' </td>
						<td class="invoice_dist" id="invoice_dist_' . $k . '" data-nid1="'. $k . '" contentNONeditable><a href="' . zen_href_link('category_product_listing.php', 'search=' . $invoices_cogs_deal["products_pebs_invoice"] . '&sortbyinvoice=1', 'NONSSL', true, false). '" target="_BLANK">' . $invoices_cogs_deal['products_pebs_invoice'] . '</a></td>
						<td class="filler1"></td>
						<td class="filler2"></td>
						<td class="fillerz3"></td>
						<td class="invoice_instocksum" id="invoice_instocksum_' . $k . '" data-nid1="'. $k . '" contentNONeditable>' . number_format((float)$invoices_cogs_deal['instocksum'], 2, '.', '') . '</td>
						<td class="invoice_cogs" id="invoice_cogs_' . $k . '" data-nid1="'. $k . '" contentNONeditable>' . number_format((float)$invoices_cogs_deal['invoicecogs'], 2, '.', '') . '</td>
						<td class="invoice_calcd_total" id="invoice_cogs_' . $k . '" data-nid1="'. $k . '" contentNONeditable>' . number_format((float)($invoices_cogs_deal['invoicecogs']+(float)$invoices_cogs_deal['instocksum']), 2, '.', '') . '</td>
						<td><button type="button" data-nid1="'. $k . '" name="btn_add" id="btn_add" class="btn btn-xs btn-success">+</button></td>
					</tr>';
				} //end else
		  } //END "FOR EACH" INVOICES COGS RESULT
	  } //END IF $ROWS > 0

 $output .= '</table>  
      </div>';  
 echo $output;
 ?>
 
  <script type="text/javascript" src="includes/stickyTableHeaders.js"></script>