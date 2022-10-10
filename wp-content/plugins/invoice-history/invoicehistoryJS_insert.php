<?php  
//$connect = mysqli_connect("localhost", "root", "", "testing");

//DATA COMES FROM INVOICEHISTORYJS.PHP INSIDE...
//<script>  
//$(document).ready(function(){  
//^^^INSIDE THIS
global $wpdb;

	echo '<pre>';
		print_r($_POST);
	echo '</pre>';

//$sql = "INSERT INTO 'invoice' ('invoice_id', 'invoice_date', 'invoice_name', 'invoice_purchase_date', 'invoice_dist', 'invoice_dist_order_count', 'invoice_import_date', 'invoice_total', 'invoice_shipping', 'invoice_comments') VALUES (NULL, '".$_POST["invoice_date"]."', '".$_POST["invoice_name"]."', '0000-00-00', '".$_POST["invoice_dist"]."', NULL, '', '', '', '".$_POST["invoice_comments"]."');";
//echo $sql;
/*	echo '<pre>';
		print_r($sql);
	echo '</pre>';*/
$result_check = $wpdb->insert('invoice', 
					array('invoice_id' => null,
						 'invoice_date' => '".$_POST["invoice_date"]."',
				  		 'invoice_name' => '".$_POST["invoice_name"]."',
				  	     'invoice_purchase_date' => '0000-00-00',
				    	 'invoice_dist' => '".$_POST["invoice_dist"]."',
					 	 'invoice_dist_order_count' => NULL,
					  	 'invoice_import_date' => '',
					   	 'invoice_total' => '',
					     'invoice_shipping' => '',
						 'invoice_comments'=> '".$_POST["invoice_comments"]."'));
if($result_check){
	//successfully inserted.
	echo 'Data Inserted';  
}else{
//something gone wrong
echo $sql;
}
 ?>