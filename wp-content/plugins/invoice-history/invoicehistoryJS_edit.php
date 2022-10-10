<?php  
	//$connect = mysqli_connect("localhost", "root", "", "testing");
	
	
//DATA COMES FROM INVOICEHISTORYJS.PHP INSIDE...
//<script>  
//$(document).ready(function(){  
//^^^INSIDE THIS


	
	$connect = mysqli_connect("localhost","plankeye_mudhut","Proverbs35!","plankeye_newest");
	$id = $_POST["id"];  
	$text = $_POST["text"];  
	$column_name = $_POST["column_name"];  
	echo '<pre>';
		print_r($_POST);
	echo '</pre>';
	$sql = "UPDATE invoice SET ".$column_name."='".$text."' WHERE invoice_id='".$id."'";
	echo $sql;
	if(mysqli_query($connect, $sql))  
	{  
		echo 'Data Updated';  
	}  
 ?>